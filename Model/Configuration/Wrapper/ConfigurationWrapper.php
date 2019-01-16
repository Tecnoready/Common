<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Configuration\Wrapper;

/**
 * Grupo de configuracion
 *
 * @author Carlos Mendoza <inhack20@tecnocreaciones.com>
 */
abstract class ConfigurationWrapper
{    
    /**
     * @var \Tecnoready\Common\Service\ConfigurationService\ConfigurationManager
     */
    private $manager;
    
    /**
     * Guarda o actualiza la configuracion en la base de datos y regenera la cache
     * 
     * @param type $key
     * @param type $value
     * @param type $description
     * @return Configuration
     */
    public function set($key,$value = null,$description = null)
    {
        return $this->manager->set($key, $value,$this->getName(),$description);
    }
    
    /**
     * Obtiene el valor del indice
     * 
     * @param type $key
     * @param type $default
     * @return type
     */
    public function get($key,$default = null)
    {
        return $this->manager->get($key,$this->getName(),$default);
    }
    
    
    public final function clearCache() {
        $this->manager->clearCache();
        $this->manager->warmUp();
    }


    public function setManager(\Tecnoready\Common\Service\ConfigurationService\ConfigurationManager $manager) {
        $this->manager = $manager;
        return $this;
    }
    
    public abstract static function getName();
    
    /**
     * Retorna la configuracion para el formulario de edicion de una propiedad
     * Example:
     * return [
            "APP_NAME" => [\Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, function() {
            return [];
        }],
            "APP_NAME_2" => [\Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, []],
        ];
     * @return array
     */
    public function getAllFormEditConfig(){
        return [];
    }
}
