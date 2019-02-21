<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Email\ORM;

use Doctrine\ORM\Mapping as ORM;

/**
 * Componente de correo
 * @ORM\MappedSuperclass()
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ModelComponent implements \Tecnoready\Common\Model\Email\ComponentInterface 
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=36, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;
    /**
     * @ORM\Column(name="type_component",type="string",length=30,nullable=false)
     */
    protected $typeComponent;
    
    /**
     * Titulo del componente para ubicarlo facilmente
     * @ORM\Column(name="title",type="string",length=150,nullable=false)
     */
    protected $title;
    
    /**
     * @ORM\Column(name="body",type="text",nullable=false)
     */
    protected $body;
    
    /** 
     * Idioma
     * @ORM\Column(name="locale",type="string",length=10,nullable=false)
     */
    protected $locale;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bases = new \Doctrine\Common\Collections\ArrayCollection();
        $this->headers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bodys = new \Doctrine\Common\Collections\ArrayCollection();
        $this->footers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getId() {
        return $this->id;
    }

    public function getTypeComponent() {
        return $this->typeComponent;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getBody() {
        return $this->body;
    }

    public function getLocale() {
        return $this->locale;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setTypeComponent($typeComponent) {
        $this->typeComponent = $typeComponent;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setBody($body) {
        $this->body = $body;
        return $this;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
        return $this;
    }
    
    /**
     * Add basis
     *
     * @param \Tecnoready\Common\Model\Email\EmailTemplateInterface $basis
     *
     * @return Component
     */
    public function addBase(\Tecnoready\Common\Model\Email\EmailTemplateInterface $basis)
    {
        $basis->setBase($this);
        $this->bases[] = $basis;

        return $this;
    }

    /**
     * Remove basis
     *
     * @param \Tecnoready\Common\Model\Email\EmailTemplateInterface $basis
     */
    public function removeBase(\Tecnoready\Common\Model\Email\EmailTemplateInterface $basis)
    {
        $this->bases->removeElement($basis);
    }

    /**
     * Get bases
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBases()
    {
        return $this->bases;
    }

    /**
     * Add header
     *
     * @param \Tecnoready\Common\Model\Email\EmailTemplateInterface $header
     *
     * @return Component
     */
    public function addHeader(\Tecnoready\Common\Model\Email\EmailTemplateInterface $header)
    {
        $header->setHeader($this);
        $this->headers[] = $header;

        return $this;
    }

    /**
     * Remove header
     *
     * @param \Tecnoready\Common\Model\Email\EmailTemplateInterface $header
     */
    public function removeHeader(\Tecnoready\Common\Model\Email\EmailTemplateInterface $header)
    {
        $this->headers->removeElement($header);
    }

    /**
     * Get headers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Add body
     *
     * @param \Tecnoready\Common\Model\Email\EmailTemplateInterface $body
     *
     * @return Component
     */
    public function addBody(\Tecnoready\Common\Model\Email\EmailTemplateInterface $body)
    {
        $body->setBody($this);
        $this->bodys[] = $body;

        return $this;
    }

    /**
     * Remove body
     *
     * @param \Tecnoready\Common\Model\Email\EmailTemplateInterface $body
     */
    public function removeBody(\Tecnoready\Common\Model\Email\EmailTemplateInterface $body)
    {
        $this->bodys->removeElement($body);
    }

    /**
     * Get bodys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBodys()
    {
        return $this->bodys;
    }

    /**
     * Add footer
     *
     * @param \Tecnoready\Common\Model\Email\EmailTemplateInterface $footer
     *
     * @return Component
     */
    public function addFooter(\Tecnoready\Common\Model\Email\EmailTemplateInterface $footer)
    {
        $footer->setFooter($this);
        $this->footers[] = $footer;

        return $this;
    }

    /**
     * Remove footer
     *
     * @param \Tecnoready\Common\Model\Email\EmailTemplateInterface $footer
     */
    public function removeFooter(\Tecnoready\Common\Model\Email\EmailTemplateInterface $footer)
    {
        $this->footers->removeElement($footer);
    }

    /**
     * Get footers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFooters()
    {
        return $this->footers;
    }
        
    public function __toString() {
        return $this->title?:"-";
    }
    
    /**
     * Retorna los estatus del componente
     * @return array
     */
    public static function getTypesLabels() {
        return [
            "email.component.type.base" => self::TYPE_COMPONENT_BASE,
            "email.component.type.header" => self::TYPE_COMPONENT_HEADER,
            "email.component.type.body" => self::TYPE_COMPONENT_BODY,
            "email.component.type.footer" => self::TYPE_COMPONENT_FOOTER,
        ];
    }
    
    /**
     * Retorna la etiqueta del estatus
     * @return string
     */
    public function getTypeLabel() {
        $type = $this->getTypeComponent();
        $types = self::getTypesLabels();
        return $types === null ? : array_search($type,$types);
    }
}
