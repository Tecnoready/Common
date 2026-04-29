<?php

namespace Tecnoready\Common\Service\Email;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;
use Tecnoready\Common\Service\Email\Adapter\EmailAdapterInterface;
use Tecnoready\Common\Model\Email\EmailQueueInterface;

/**
 * Servicio para enviar correo con una plantilla twig
 *
 * @author Máximo Sojo <maxojo13@gmail.com>
 */
class TwigSymfonyMailer
{
    use \Symfony\Component\DependencyInjection\ContainerAwareTrait;

	/**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var EmailAdapterInterface
     */
    protected $adapter;
    
    /**
     * Opciones
     * @var array
     */
    private $options;

    /**
     * Plantilla base Twig renderizada en runtime.
     *
     * @var string
     */
    private $templateSource;

    /**
     * @var EmailProviderOrchestrator|null
     */
    private $emailProviderOrchestrator;

    public function __construct(MailerInterface $mailer, Environment $twig, EmailAdapterInterface $adapter, array $options = [], ?EmailProviderOrchestrator $emailProviderOrchestrator = null)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->twig->addExtension(new \Twig\Extension\StringLoaderExtension());

        $this->adapter = $adapter;
        $this->emailProviderOrchestrator = $emailProviderOrchestrator;

        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
            "extra_params" => null,
            "skeleton_email" => "skeleton_email.html.twig",
            "domain_blacklist" => []
        ]);
        $resolver->setRequired([
            "debug_mail", 
            "env", 
            "from_email", 
            "from_name",
            "skeleton_email"]);
        $resolver->setDefined(["extra_params"]);
        $resolver->setAllowedTypes("debug", "boolean");
        $resolver->setAllowedTypes("debug_mail", "string");

        $this->options = $resolver->resolve($options);
        
        $this->templateSource = <<<EOF
        {% extends template_from_string(baseString) %}
        
        {% block header %}{% include template_from_string(headerString) %}{% endblock %}

        {% block content_html %}{% include(template_from_string(bodyString)) with _context %}{% endblock %}
                
        {% block footer %}{% include(template_from_string(footerString)) with _context %}{% endblock %}
EOF;
    }

    /**
     * Genera un email y lo guarda en base de datos para enviar luego
     *
     * @param   string $templateName
     * @param   string | array $toEmail
     * @param   array $context
     * @param   array  $attachs
     * @param   array  $extras
     *
     * @return  []
     */
    public function emailQueue(string $templateName, $toEmail, array $context, array $attachs = [], array $extras = []): EmailQueueInterface
    {
        $email = $this->render($templateName,$toEmail,$context,$attachs);
        
        if ($email) {
            $email->setAttachs($attachs);
            $email->setExtras($extras);
            $email->setEnvironment($this->options["env"]);
            $this->adapter->persist($email);
        }

        return $email === null ? false : $email;
    }

    /**
     * Envia un email guardado en base de datos
     *
     * @param   EmailQueueInterface  $emailQueue
     * @param   array                $attachs
     *
     * @return  bool
     */
    public function sendEmailQueue(EmailQueueInterface $emailQueue = null, array $attachs = []): bool
    {
        $success = false;

        try {
            if (empty($attachs) && method_exists($emailQueue, 'getAttachs')) {
                $attachs = $emailQueue->getAttachs();
                if (!is_array($attachs)) {
                    $attachs = [];
                }
            }

            // Message
            $fromEmail = null;
            foreach ($emailQueue->getFromEmail() as $address => $name) {
                $fromEmail = new Address($address,$name);
            }
            
            // Prepare and send message
            foreach ($emailQueue->getToEmail() as $to) {
                $email = (new Email());
                $email
                    ->from($fromEmail)
                    ->to($to)
                    ->subject($emailQueue->getSubject())
                    ->html($emailQueue->getBody())
                ;

                // Attachments
                foreach ($attachs as $name => $path) {
                    $email->attachFromPath($path,$name);
                }

                if ($this->emailProviderOrchestrator !== null && $this->emailProviderOrchestrator->isEnabled()) {
                    $this->sendByOrchestrator($this->emailProviderOrchestrator, $emailQueue, $email);
                } else {
                    $this->send($email);
                }
            }

            $success = true;

            // Mark success
            $emailQueue->onSendSuccessAt();
        } catch (\Exception $exc) {
            // Mark error
            $emailQueue->onSendErrorAt();
            // throw $exc;
        }

        return $success;
    }

    /**
     * Envia un email sin guardar en base de datos
     *
     * @param   string $templateName
     * @param   string $toEmail
     * @param   array  $context
     * @param   array  $attachs
     * @param   array  $extras
     *
     * @return  bool
     */
    public function sendEmail(string $templateName, $toEmail, array $context, array $attachs = [], array $extras = []): bool
    {
        $email = $this->render($templateName,$toEmail,$context,$attachs);
        return $this->sendEmailQueue($email,$attachs);
    }

    /**
     * Construye y envia un email inmediatamente
     */
    public function email($templateName, $toEmail, $context, array $attachs = [])
    {
        $message = $this->render($templateName,$toEmail,$context,$attachs);
        foreach ($attachs as $name => $path) {
            $message->attachFromPath($path,$name);
        }
        return $this->send($message);
    }

    /**
     * Renderiza la plantilla del email
     */
    public function render($templateName, $toEmail, $context,array $attachs = [])
    {
        $context = $this->buildDocumentContext($templateName, $context, $toEmail, $attachs);
    	$template = $this->twig->createTemplate($this->templateSource);
        $message = $this->buildEmail($template, $toEmail, $context);
        return $message;
    }

    /**
     * Envia el email usando el trasport
     */
    private function send($message): bool
    {
        try {
            $this->mailer->send($message);
            return true;
        } catch (TransportExceptionInterface $e) {
           throw $e;
        }
    }

    /**
     * Envia correo a traves de un orquestador de proveedores.
     */
    private function sendByOrchestrator(EmailProviderOrchestrator $orchestrator, EmailQueueInterface $emailQueue, Email $email): bool
    {
        $result = $orchestrator->sendQueueEmail($emailQueue, $email);
        if ($result !== true) {
            throw new \RuntimeException('Email delivery failed for all configured providers.');
        }

        return true;
    }

	/**
     * Construye un correo a partir de la plantilla
     * @param type $templateName
     * @param type $context
     * @param type $toEmail
     * @return EmailQueueInterface
     */
    private function buildEmail($templateName, $toEmail, $context)
    {
        if($this->options["debug"] === true){
            $toEmail = $this->options["debug_mail"];
        }

        if(!is_array($toEmail)){
            $toEmail = [$toEmail];
        }

        $context['toEmail'] = $toEmail;   
        $context['appName'] = $this->options["from_name"];
        
        if($templateName instanceof \Twig_Template){
            $template = $templateName;            
        }elseif((class_exists("Twig\TemplateWrapper") && $templateName instanceof \Twig\TemplateWrapper) ||
                 (class_exists("Twig_TemplateWrapper") && $templateName instanceof \Twig_TemplateWrapper)){
            $template = $templateName;
        }else{
            $template = $this->twig->loadTemplate($templateName);               
        }
        
        $tplSubjet = $this->twig->createTemplate("{% block subject %}{% include (template_from_string(subjectString)) %}{% endblock subject %}");
        $subject = $tplSubjet->renderBlock('subject', $context);
        $htmlBody = $template->render($context);
        
        if (isset($context['_subjectSuffix']) && !empty($context['_subjectSuffix'])) {
            $subject .= $context['_subjectSuffix'];
        }

        $fromEmail = array($this->options["from_email"] => $this->options["from_name"]);

        $email = $this->adapter->createEmailQueue();
        $email
                ->setStatus(EmailQueueInterface::STATUS_NOT_SENT)
                ->setSubject($subject)
                ->setFromEmail($fromEmail)
                ->setToEmail($toEmail)
                ->setRetries(0)
                ->onCreatedAt()
        ;
        $email->setBody($htmlBody);
        
        return $email;
    }

    /**
     * Arma el documento cuerpo del email
     */
    private function buildDocumentContext($id,$context,$toEmail,array $attachs = [])
    {
        $idExp = explode("/",$id);
        if(count($idExp) > 0){
            $id = $idExp[count($idExp) - 1];
        }
        
        $document = $this->adapter->find($id);
        if($document === null){
            throw new \RuntimeException(sprintf("Document '%s' not found.",$id));
        }
        
        $headerString = $baseString = $footerString = "";
        $header = $document->getHeader();
        $base = $document->getBase();
        $footer = $document->getFooter();
        $subject = $document->getSubject();
        $bodyDocument = $document->getBody();
        
        if($header){
            $headerString = $header->getBody();
        }

        if($base){
            $baseString = "{% block body_html %}".$base->getBody()."{% endblock body_html %}";
        }else {
            $baseString = "{% block body_html %}{% endblock body_html %}";
        }

        if($footer){
            $footerString = $footer->getBody();
        }

        $body = $bodyDocument->getBody();
        $headerString = html_entity_decode($headerString);
        $baseString = html_entity_decode($baseString);
        $footerString = html_entity_decode($footerString);
        $bodyString = html_entity_decode($body);
        $subject = strip_tags(html_entity_decode($subject));
        $context = array_merge($context,[
            "headerString" => $headerString,
            "baseString" => $baseString,
            "footerString" => $footerString,
            "bodyString" => $bodyString,
            "subjectString" => $subject,
        ]);

        return $context;
    }
}