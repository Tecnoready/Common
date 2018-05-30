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
class ModelComponent {
    
    const TYPE_COMPONENT_HEADER = "header";
    const TYPE_COMPONENT_FOOTER = "footer";
    const TYPE_COMPONENT_BODY = "body";
    const TYPE_COMPONENT_BASE = "base";
    
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
        
    public function __toString() {
        return $this->title?:"-";
    }
}
