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
 * Grupo de configuracion
 *
 * @author Carlos Mendoza <inhack20@tecnocreaciones.com>
 */
abstract class ConfigurationWrapper
{    
    /**
     *
     * @var \Tecnoready\Common\Service\ConfigurationService\ConfigurationManager
     */
    private $configurationManager;
    
    public function __construct(\Tecnoready\Common\Service\ConfigurationService\ConfigurationManager $configurationManager) {
        $this->configurationManager = $configurationManager;
    }
    
    /**
     * Guarda o actualiza la configuracion en la base de datos y regenera la cache
     * 
     * @param type $key
     * @param type $value
     * @param type $description
     * @return Configuration
     */
    protected function set($key,$value = null,$description = null,\Tecnocreaciones\Bundle\ToolsBundle\Entity\Configuration\BaseGroup $group = null)
    {
        $this->getConfigurationManager()->set($key, $value, $description,$group);
        
        return $this;
    }
    
    /**
     * Obtiene el valor del indice
     * 
     * @param type $key
     * @param type $default
     * @return type
     */
    protected function get($key,$default = null)
    {
        return $this->getConfigurationManager()->get($key, $default);
    }
    
    
    /**
     * Retorna el servicio que maneja la configuracion del sistema
     * @return \Tecnoready\Common\Service\ConfigurationService\ConfigurationManager
     */
    protected function getConfigurationManager() {
        return $this->container->get('tecnocreaciones_tools.configuration_service');
    }
    
    public static function getName();
}
