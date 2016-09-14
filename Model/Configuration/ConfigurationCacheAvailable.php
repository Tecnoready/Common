<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Configuration;

/**
 * Configuraciones
 *
 * @author Carlos Mendoza <inhack20@tecnocreaciones.com>
 */
abstract class ConfigurationCacheAvailable
{
    protected $configurations;
            
    function get($key,$default = null,$wrapperName = null)
    {
        $id = \Tecnoready\Common\Util\ConfigurationUtil::generateId($wrapperName,$key);
        if(isset($this->configurations[$id])){
            return $this->configurations[$id]['value'];
        }
        return $default;
    }
    
    function getIdByKey($key)
    {
        if(isset($this->configurations[$key])){
            return $this->configurations[$key]['id'];
        }
        return null;
    }
    
    function hasKey($key)
    {
        if(isset($this->configurations[$key])){
            return true;
        }
        return false;
    }
}
