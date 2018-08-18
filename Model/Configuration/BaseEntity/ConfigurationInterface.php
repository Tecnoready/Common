<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Configuration\BaseEntity;

/**
 * Interfaz de configuracion
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface ConfigurationInterface {
    public function getKey();

    public function getValue();

    public function setKey($key);

    public function setValue($value);
    
    public function getEnabled();

    public function getCreatedAt();

    public function getUpdatedAt();

    public function setEnabled($enabled);
    
    /**
     * 
     * @param \DateTime $createdAt
     */
    public function setCreatedAt();
    
    /**
     * 
     * @param \DateTime $createdAt
     */
    public function setUpdatedAt();

    
    public function getDescription();

    public function setDescription($description);
    
    
    public function getNameWrapper();

    public function setNameWrapper($nameWrapper);
    
    public function getType();

    public function setType($type);
    
    
    public function getDataType();

    public function setDataType($dataType);
}
