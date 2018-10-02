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
     * @ORM\Column(name="item_key", type="string",length=200,nullable=false)
     */
    protected $key;
    
    /**
     * Valor de configuracion
     * 
     * @var string
     * @ORM\Column(name="item_value", type="text")
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
     * @ORM\Column(name="description", type="string",length=250)
     */
    protected $description;
    
    /**
     * Tipo de dato
     * 
     * @var string
     * @ORM\Column(name="type", type="string",length=20,nullable=true)
     */
    protected $type;
    
    /**
     * Datos del tipo de dato (objeto: nombre de clase)
     * 
     * @var string
     * @ORM\Column(name="data_type", type="text",nullable=true)
     */
    protected $dataType;
    
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

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }
    
    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }
    
    public function getDataType() {
        return $this->dataType;
    }

    public function setDataType($dataType) {
        $this->dataType = $dataType;
        return $this;
    }
        
    public function __toString() {
        return $this->getDescription()?: '-';
    }
}
