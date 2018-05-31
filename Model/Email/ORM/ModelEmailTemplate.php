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
 * Plantilla de correo
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelEmailTemplate implements \Tecnoready\Common\Model\Email\EmailTemplateInterface
{
    /**
     * @var string
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     */
    protected $id;
    
    /**
     * @ORM\Column(name="status",type="string",length=30,nullable=false)
     */
    protected $status;
    /**
     * Titulo del correo para identificarlo apidamente (no lleva parametros)
     * @ORM\Column(name="title",type="string",length=150,nullable=false)
     */
    protected $title;
    /**
     * Asunto del correo (esto puede llevar parametros)
     * @ORM\Column(name="subject",type="text",nullable=false)
     */
    protected $subject;
    
    /**
     * @var ModelComponent
     */
    protected $base;
    /**
     * @var ModelComponent
     */
    protected $header;
    /**
     * @var ModelComponent
     */
    protected $body;
    /**
     * @var ModelComponent
     */
    protected $footer;
    /**
     * @ORM\Column(name="locale",type="string",length=10,nullable=false)
     */
    protected $locale;
    
    public function getId() {
        return $this->id;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getBase() {
        return $this->base;
    }

    public function getHeader() {
        return $this->header;
    }

    public function getBody() {
        return $this->body;
    }

    public function getFooter() {
        return $this->footer;
    }

    public function getLocale() {
        return $this->locale;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setBase(ModelComponent $base) {
        $this->base = $base;
        return $this;
    }

    public function setHeader(ModelComponent $header) {
        $this->header = $header;
        return $this;
    }

    public function setBody(ModelComponent $body) {
        $this->body = $body;
        return $this;
    }

    public function setFooter(ModelComponent $footer) {
        $this->footer = $footer;
        return $this;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
        return $this;
    }
    
    public function getSubject() {
        return $this->subject;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }
        
    public function __toString() {
        return $this->title?:"-";
    }
}
