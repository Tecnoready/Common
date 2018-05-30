<?php

namespace Tecnoready\Common\Service\Email;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Swift_Mailer;
use Swift_Preferences;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Environment;
use Tecnoready\Common\Model\Email\ORM\ModelEmailQueue;
use Tecnoready\Common\Exception\UnsupportedException;

/**
 * Servicio para enviar correo con una plantilla twig (pandco.mailer.twig_swift)
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
     * @var Registry
     */
    protected $doctrine;
    protected $options;

    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig, Registry $doctrine, array $options = []) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->doctrine = $doctrine;
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
            "extra_params" => true,
            "skeleton_email" => "skeleton_email.html.twig",//TODO falta agregar ruta completa
        ]);
        $resolver->setRequired([
            "debug_mail", 
            "env", 
            "email_queue_class", 
            "email_template_class", 
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
    
    public function emailQueue($id,$context,$toEmail,array $attachs = [],array $extras = []) {
        $context = $this->buildDocumentContext($id, $context, $toEmail, $attachs);
        $templateName = $this->options["skeleton_email"];
        $email = $this->buildEmail($templateName, $context, $toEmail);
        $email->setAttachs($attachs);
        $email->setExtras($extras);
        $email->setEnvironment($this->options["env"]);
        
        $em = $this->doctrine->getManager();
        $em->persist($email);
        $em->flush();
        
        return $email;
    }
    
    /**
     * Envia un correo almacenado en la base de datos
     * @param EmailQueue $emailQueue
     * @return type
     */
    public function sendEmailQueue(ModelEmailQueue $emailQueue) {
        $fromEmail = array('no-responder@mpandco.com' => "mPandco");
        $message = $this->getSwiftMessage()
            ->setSubject($emailQueue->getSubject())
            ->setFrom($emailQueue->getFromEmail())
            ->setTo($emailQueue->getToEmail());

        $message->setBody($emailQueue->getBody(), 'text/html');
        $attachDocuments = $emailQueue->getExtraData(ModelEmailQueue::ATTACH_DOCUMENTS);
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
     * @return ModelEmailQueue
     */
    protected function buildEmail($templateName, $context, $toEmail,$fromEmail = null) {
        $context['toEmail'] = $toEmail;

        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);
        
        if ($fromEmail === null) {
            $fromEmail = array($this->options["from_email"] => $this->options["from_name"]);
        }
        
        $email = new $this->options["email_queue_class"]();
        $email
                ->setStatus(ModelEmailQueue::STATUS_NOT_SENT)
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
      
        $document = $this->doctrine->getManager()->find($this->options["email_template_class"], $id);
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
        $subject = $bodyDocument->getTitle();
        
        $headerString = html_entity_decode($headerString);
        $baseString = html_entity_decode($baseString);
        $footerString = html_entity_decode($footerString);
        $body = html_entity_decode($body);
        $subject = html_entity_decode($subject);
        $subject = strip_tags(html_entity_decode($subject));
        
        $context = array_merge($context,[
            "headerString" => $headerString,
            "baseString" => $baseString,
            "footerString" => $footerString,
            "bodyString" => $body,
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

    

}
