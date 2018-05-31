<?php

namespace Tecnoready\Common\Service\Email;

use Swift_Mailer;
use Swift_Preferences;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Environment;
use Tecnoready\Common\Exception\UnsupportedException;
use Tecnoready\Common\Service\Email\Adapter\EmailAdapterInterface;
use Tecnoready\Common\Model\Email\EmailQueueInterface;

/**
 * Servicio para enviar correo con una plantilla twig (tecnoready.swiftmailer)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TwigSwiftMailer {

    use \Symfony\Component\DependencyInjection\ContainerAwareTrait;

    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var EmailAdapterInterface
     */
    protected $adapter;

    protected $options;

    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig,EmailAdapterInterface $adapter, array $options = []) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->adapter = $adapter;
        
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
            "extra_params" => null,
            "skeleton_email" => "skeleton_email.html.twig",//TODO falta agregar ruta completa
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

        $preferences = Swift_Preferences::getInstance();
        if (@is_writable($tmpDir = sys_get_temp_dir())) {
            $preferences->setTempDir($tmpDir)->setCacheType('disk');
            putenv('TMPDIR=' . $tmpDir);
        }
    }
    
    /**
     * Programa la construccion de un correo y lo envia a la base de datos
     * @param type $id
     * @param type $context
     * @param type $toEmail
     * @param array $attachs
     * @param array $extras
     * @return EmailQueueInterface
     */
    public function emailQueue($id,$context,$toEmail,array $attachs = [],array $extras = []) {
        $context = $this->buildDocumentContext($id, $context, $toEmail, $attachs);
        $templateName = $this->options["skeleton_email"];
        $templateSource = <<<EOF
                {% extends template_from_string(baseString) %}
                
                {% block subject %}{% include (template_from_string(subjectString)) %}{% endblock subject %}

                {% block header %}{% include template_from_string(headerString) %}{% endblock %}

                {% block content_html %}{% include(template_from_string(bodyString)) with _context %}{% endblock %}

                {% block footer %}{% include(template_from_string(footerString)) with _context %}{% endblock %}
EOF;
        
        $template =$this->twig->createTemplate($templateSource);
        $email = $this->buildEmail($template, $context, $toEmail);
        $email->setAttachs($attachs);
        $email->setExtras($extras);
        $email->setEnvironment($this->options["env"]);
        
        $this->adapter->persist($email);
        $this->adapter->flush();
        
        return $email;
    }
    
    /**
     * Envia un correo almacenado en la base de datos
     * @param EmailQueue $emailQueue
     * @return type
     */
    public function sendEmailQueue(EmailQueueInterface $emailQueue) {
        $message = $this->getSwiftMessage()
            ->setSubject($emailQueue->getSubject())
            ->setFrom($emailQueue->getFromEmail())
            ->setTo($emailQueue->getToEmail());

        $message->setBody($emailQueue->getBody(), 'text/html');
        $attachDocuments = $emailQueue->getExtraData(EmailQueueInterface::ATTACH_DOCUMENTS);
        $attachs = [];
        if($attachDocuments !== null && is_array($attachDocuments)){
            throw new UnsupportedException();
//            $exporter = $this->getExporterManager();
//            foreach ($attachDocuments as $attachDocument) {
//                $path = $exporter->generateWithSource($attachDocument["id"],$attachDocument["chain"],$attachDocument["name"]);
//                $attachs[basename($path)] = $path;
//            }
        }
        foreach ($attachs as $name => $path) {
            $message->attach(\Swift_Attachment::fromPath($path)->setFilename($name));
        }
        return $this->send($message);
    }
    
    /**
     * Construye un correo a partir de la plantilla
     * @param type $templateName
     * @param type $context
     * @param type $toEmail
     * @return EmailQueueInterface
     */
    protected function buildEmail($templateName, $context, $toEmail,$fromEmail = null) {
        $context['toEmail'] = $toEmail;
        
        if($templateName instanceof \Twig_Template){
            $template = $templateName;
        }else{
            $template = $this->twig->loadTemplate($templateName);
        }
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);
        if ($fromEmail === null) {
            $fromEmail = array($this->options["from_email"] => $this->options["from_name"]);
        }
        
        $email = $this->adapter->createEmailQueue();
        $email
                ->setStatus(EmailQueueInterface::STATUS_NOT_SENT)
                ->setSubject($subject)
                ->setFromEmail($fromEmail)
                ->setToEmail($toEmail)
        ;
        if (!empty($htmlBody)) {
            $email->setBody($htmlBody);
        } else {
            $email->setBody($textBody);
        }
        return $email;
    }
    
    protected function buildDocumentContext($id,$context,$toEmail,array $attachs = []) {
        $idExp = explode("/",$id);
        if(count($idExp) > 0){
            $id = $idExp[count($idExp) - 1];
        }
        $document = $this->adapter->find($id);
        if($document === null){
            throw new \RuntimeException(sprintf("Document '%s' not found.",$id));
//            if($this->options["debug"] === true){
//                
//            }else{
//                return null;
//            }
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
            $baseString = "{% block subject '' %}{% block body_text '' %}{% block body_html %}".$base->getBody()."{% endblock %}";
        }else {
            $baseString = "{% block subject '' %}{% block body_text '' %}{% block body_html %}{% endblock %}";
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

    /**
     * Renderizar una plantilla twig para enviarla por correo
     * @param type $templateName
     * @param array $context parametros a pasar a la plantilla
     * @param type $toEmail Direccion o direcciones destino del mensaje
     * @param type $fromEmail Direccion de origen de correo
     * @return type
     */
    public function renderMessage($templateName, $context, $toEmail, $fromEmail = null) {
//        if($toEmail instanceof \Application\Sonata\UserBundle\Entity\User ){
//            $toEmail = array(
//                $toEmail->getEmail() => (string)$toEmail,
//            );
//        }
        $context['toEmail'] = $toEmail;

        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        if ($fromEmail === null) {
            $fromEmail = array($this->options["from_email"] => $this->options["from_name"]);
        }

        $message = $this->getSwiftMessage()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                    ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }
        return $message;
    }
    
    /**
     * @return EmailAdapterInterface
     */
    public function getAdapter() {
        return $this->adapter;
    }
}