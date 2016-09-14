<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\ConfigurationService\Adapter;

/**
 * Adaptador de las configuraciones
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface ConfigurationAdapterInterface {
    public function find($key);
    public function findAll();
    
    /*
     * Guarda los cambios en la base de datos
     */
    public function flush();
    
    public function update($key,$value,$description,$nameConfiguration);
    
    public function createNew();
}
