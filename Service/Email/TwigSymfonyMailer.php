<?php

namespace Tecnoready\Common\Service\Email;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;
use Tecnoready\Common\Service\Email\Adapter\EmailAdapterInterface;

/**
 * Servicio para enviar correo con una plantilla twig
 *
 * @author Máximo Sojo <maxojo13@gmail.com>
 */
class TwigSymfonyMailer
{
	/**
     * @var MailerInterface
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

    public function __construct(MailerInterface $mailer,Environment $twig, EmailAdapterInterface $adapter, array $options = [])
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->twig->addExtension(new \Twig\Extension\StringLoaderExtension());

        $this->adapter = $adapter;

        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
            "extra_params" => null,
            "skeleton_email" => "skeleton_email.html.twig"
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

    public function email($templateName, $toEmail, $context, array $attachs = [])
    {
        $message = $this->render($templateName,$toEmail,$context,$attachs);
        foreach ($attachs as $name => $path) {
            $message->attachFromPath($path,$name);
        }
        $this->send($message);
    }

    private function render($templateName, $toEmail, $context,array $attachs = [])
    {
        $context = $this->buildDocumentContext($templateName, $context, $toEmail, $attachs);
    	$template = $this->twig->createTemplate($this->templateSource);
        $message = $this->buildEmail($template, $toEmail, $context);
        return $message;
    }

    private function send($message)
    {
        try {
            $r = $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending; display an
            // error message or try to resend the message
        }
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

        $fromEmail = new Address($this->options["from_email"], $this->options["from_name"]);
        // Message
        $message = (new Email())
            ->from($fromEmail)
            ->to($toEmail)
            ->subject($subject)
            ->html($htmlBody);

        return $message;
    }

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