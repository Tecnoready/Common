<?php

namespace Tecnoready\Common\Service\Email;

use Swift_Mailer;
use Swift_Preferences;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Environment;
use Tecnoready\Common\Exception\UnsupportedException;
use Tecnoready\Common\Service\Email\Adapter\EmailAdapterInterface;
use Tecnoready\Common\Model\Email\EmailQueueInterface;
use Tecnoready\Common\Service\ObjectManager\ObjectDataManager;

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
    
    protected $templateSource;
    
    /**
     * @var ObjectDataManager 
     */
    protected $objectDataManager;

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
        
                $this->templateSource = <<<EOF
                {% extends template_from_string(baseString) %}
                
                {% block header %}{% include template_from_string(headerString) %}{% endblock %}

                {% block content_html %}{% include(template_from_string(bodyString)) with _context %}{% endblock %}
                        
                {% block footer %}{% include(template_from_string(footerString)) with _context %}{% endblock %}
EOF;
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
        $template =$this->twig->createTemplate($this->templateSource);
        $email = $this->buildEmail($template, $context, $toEmail);
        $email->setAttachs($attachs);
        $email->setExtras($extras);
        $email->setEnvironment($this->options["env"]);
        
        $this->adapter->persist($email);
        $this->adapter->flush();
        
        return $email;
    }
    
    /**
     * Construye un correo almacenado en la base de datos (lo manda al spool para ser enviado luego)
     * @param EmailQueue $emailQueue
     * @return type
     */
    public function sendEmailQueue(EmailQueueInterface $emailQueue,array $attachs = []) {
        $v = \Swift::VERSION;
        $r = version_compare("6.0.0", $v);
        if ($r === -1 || $r === 0) {
            $message = new \Swift_Message();
        } else {
            $message = \Swift_Message::newInstance();
        }
        $message
            ->setSubject($emailQueue->getSubject())
            ->setFrom($emailQueue->getFromEmail())
            ->setTo($emailQueue->getToEmail());

        $message->setBody($emailQueue->getBody(), 'text/html');
        $attachDocuments = $emailQueue->getExtraData(EmailQueueInterface::ATTACH_DOCUMENTS);
        if($attachDocuments !== null && is_array($attachDocuments)){
            if(!$this->container->has(ObjectDataManager::class)){
                throw new UnsupportedException(sprintf("El servicio para exportar con '%s' no esta configurado.",ObjectDataManager::class));
            }
            $objectDataManager = $this->container->get(ObjectDataManager::class);
            if(false){
                $objectDataManager = new ObjectDataManager();
            }
            
            foreach ($attachDocuments as $attachDocument) {
                $objectDataManager->configure($attachDocument["id"],$attachDocument["chain"]);
                
                $file = $objectDataManager->exporter()->generateWithSource($attachDocument["name"],[
                ],true);
                $path = $file->getPathname();
                $attachs[basename($path)] = $path;
            }
        }
        foreach ($attachs as $name => $path) {
            $message->attach(\Swift_Attachment::fromPath($path)->setFilename($name));
        }
        return $this->send($message);
    }
    
    /**
     * Envia un email
     * @param type $id
     * @param type $context
     * @param type $toEmail
     * @param array $attachs
     * @return boolean
     */
    public function sendDocumentMessage($id,$context,$toEmail,array $attachs = [])
    {
        $context = $this->buildDocumentContext($id, $context, $toEmail, $attachs);           
        $template =$this->twig->createTemplate($this->templateSource);  
        $email = $this->buildEmail($template, $context, $toEmail); 
        return $this->sendEmailQueue($email,$attachs);          
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
        $context['appName'] = $this->options["from_name"];
        
        if(class_exists("FOS\UserBundle\Model\UserInterface") && $toEmail instanceof \FOS\UserBundle\Model\UserInterface){
            $toEmail = $toEmail->getEmail();
        }
        if(is_object($toEmail) && method_exists($toEmail,"getEmail")){
            $toEmail = $toEmail->getEmail();
        }

//        var_dump(get_class($toEmail));
//        die;
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

        if ($fromEmail === null) {
            $fromEmail = array($this->options["from_email"] => $this->options["from_name"]);
        }
        $toEmail = (array)$toEmail;
        $email = $this->adapter->createEmailQueue();
        $email
                ->setStatus(EmailQueueInterface::STATUS_NOT_SENT)
                ->setSubject($subject)
                ->setFromEmail($fromEmail)
                ->setToEmail($toEmail)
        ;                
        $email->setBody($htmlBody);
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

    
    private function send($message = null) {
        if($message === null){
            return true;
        }
        if($this->options["debug"] === true){
            $message->setTo([
                $this->options["debug_mail"] => "Demo Debug"
            ]);
        }
        return $this->mailer->send($message);
    }
    
    /**
     * @return EmailAdapterInterface
     */
    public function getAdapter() {
        return $this->adapter;
    }
}
