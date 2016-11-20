<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Configuration\BaseEntity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Modelo de configuracion
 *
 * @author Carlos Mendoza <inhack20@tecnocreaciones.com>
 * @ORM\MappedSuperclass()
 * @ORM\HasLifecycleCallbacks()
 */
abstract class DoctrineORMConfiguration implements ConfigurationInterface
{
    /**
     * Indice de configuracion
     * 
     * @var string
     * @ORM\Column(name="`key`", type="string",length=200,nullable=false)
     */
    protected $key;
    
    /**
     * Valor de configuracion
     * 
     * @var string
     * @ORM\Column(name="`value`", type="text")
     */
    protected $value;
    
    /**
     * Nombre del contenedor
     * 
     * @var string
     * @ORM\Column(name="name_wrapper", type="string",length=200,nullable=false)
     */
    protected $nameWrapper;
    
    /**
     * Descripcion de la configuracion
     * 
     * @var string
     * @ORM\Column(name="description", type="string",length=200)
     */
    protected $description;
    
    /**
     * ¿Habilitado?
     * 
     * @var boolean
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled = true;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime",nullable=true)
     */
    protected $updatedAt;
    
    /**
     * 
     * @param \DateTime $createdAt
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
        
        return $this;
    }
    
    /**
     * 
     * @param \DateTime $createdAt
     * @ORM\PreUpdate
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
        
        return $this;
    }

    public function getKey() {
        return $this->key;
    }

    public function getValue() {
        return $this->value;
    }

    public function getNameWrapper() {
        return $this->nameWrapper;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getEnabled() {
        return $this->enabled;
    }

    public function setKey($key) {
        $this->key = $key;
        return $this;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function setNameWrapper($nameWrapper) {
        $this->nameWrapper = $nameWrapper;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setEnabled($enabled) {
        $this->enabled = $enabled;
        return $this;
    }

    public function getCreatedAt(): \DateTime {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime {
        return $this->updatedAt;
    }

    public function __toString() {
        return $this->getDescription()?: '-';
    }
}
