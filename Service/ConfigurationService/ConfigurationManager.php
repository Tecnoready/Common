<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\ConfigurationService;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Manejador de configuracion
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ConfigurationManager {
    /**
     * @var array
     */
    protected $options = array();
    
    /**
     * Configuraciones disponibles
     * @var \Tecnoready\Common\Model\Configuration\ConfigurationCacheAvailable
     */
    private $configurationCacheAvailable;
            
    /**
     * @var \Tecnoready\Common\Model\Configuration\ConfigurationWrapper
     */
    private $configurationsWrapper = null;
    /**
            
     * Adaptador origen de los datos
     * @var Adapter\ConfigurationAdapterInterface
     */
    private $adapter;
    
    function __construct(\Tecnoready\Common\Service\SequenceGenerator\Adapter\ConfigurationAdapterInterface $adapter,array $options = array())
    {
        if(!class_exists("Symfony\Component\Config\ConfigCache")){
            throw new \Exception("The package '%s' is required, please install https://packagist.org/packages/symfony/config",'"symfony/config": "^3.1"');
        }
        if(!class_exists("Symfony\Component\OptionsResolver\OptionsResolver")){
            throw new \Exception("The package '%s' is required, please install https://packagist.org/packages/symfony/options-resolver",'"symfony/options-resolver": "^3.1"');
        }
        $this->setOptions($options);
        $this->adapter = $adapter;
        $this->configurationsWrapper = [];
    }
    
    /**
     * Añade un grupo de configuracion
     * @param \Tecnoready\Common\Model\Configuration\ConfigurationWrapper $configuration
     * @return \Tecnoready\Common\Service\ConfigurationService\ConfigurationManager
     * @throws \RuntimeException
     */
    public function addConfiguration(\Tecnoready\Common\Model\Configuration\ConfigurationWrapper $configuration) 
    {
        if(isset($this->configurationsWrapper[$configuration->getName()])){
            throw new \RuntimeException(sprintf("The configuration name '%s' already added",$configuration->getName()));
        }
        $this->configurationsWrapper[$configuration->getName()] = $configuration;
        return $this;
    }

    /**
     * Sets options.
     *
     * Available options:
     *
     *   * cache_dir:     The cache directory (or null to disable caching)
     *   * debug:         Whether to enable debugging or not (false by default)
     *   * resource_type: Type hint for the main resource (optional)
     *
     * @param array $options An array of options
     *
     * @throws \InvalidArgumentException When unsupported option is provided
     */
    public function setOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'cache_dir'              => null,
            'debug'                  => false,
            'configuration_dumper_class' => 'Tecnoready\\Common\\Dumper\\Configuration\\PhpConfigurationDumper',
            'configuration_base_dumper_class' => 'Tecnoready\\Common\\Model\\Configuration\\ConfigurationCacheAvailable',
            'configuration_cache_class'  => 'ProjectConfigurationAvailable',
            'configuration_class'  => null,
        ]);
        
        $resolver->setRequired(["cache_dir"]);
        
//        if ($invalid) {
//            throw new \InvalidArgumentException(sprintf('The Configuration does not support the following options: "%s".', implode('", "', $invalid)));
//        }
        $this->options = $resolver->resolve($options);
    }
    
    /**
     * Gets the Configuration Value instance associated with this Confurations.
     * 
     * @return \Tecnocreaciones\Bundle\ToolsBundle\Model\Configuration\ConfigurationAvailable
     */
    public function getAvailableConfiguration()
    {
        if (null !== $this->configurationCacheAvailable) {
            return $this->configurationCacheAvailable;
        }
        $class = $this->options['configuration_cache_class'];
        $cache = $this->getConfigCache();
        if (!$cache->isFresh()) {
            $dumper = $this->getAvailableConfigurationDumperInstance();

            $options = array(
                'class'      => $class,
                'base_class'      => $this->options['configuration_base_dumper_class']
            );
            $cache->write($dumper->dump($options));
        }

        require_once $cache;

        return $this->configurationCacheAvailable = new $class();
    }
    
    /**
     * Retorna la clase que maneja la cache
     * 
     * @return \Symfony\Component\Config\ConfigCache
     */
    private function getConfigCache()
    {
        $class = $this->options['configuration_cache_class'];
        return new ConfigCache($this->options['cache_dir'].'/tecnoready_tools/'.$class.'.php', $this->options['debug']);
    }
    
    /**
     * Retorna el valor de la configuracion de la base de datos
     * 
     * @param string $key Indice de la configuracion
     * @param mixed $default Valor que se retornara en caso de que no exista el indice
     * @return mixed
     */
    function get($key,$default = null) {
        return $this->getAvailableConfiguration()->get($key,$default);
    }
    
    /**
     * Establece el valor de una configuracion
     * 
     * @param string $key indice de la configuracion
     * @param mixed $value valor de la configuracion
     * @param string|null $description Descripcion de la configuracion|null para actualizar solo el key
     */
    function set($key,$value = null,$description = null,$wrapperName = "default")
    {
        if(!isset($this->configurationsWrapper[$wrapperName])){
            throw new \InvalidArgumentException(sprintf("The configurationWrapper '%s' dont exist.",$wrapperName));
        }
        $success = $this->adapter->update($key, $value, $description,$wrapperName);
        return $success;
    }
    
    /**
     * Guarda los cambios en la base de datos
     */
    function flush($andClearCache = true)
    {
        $this->adapter->flush();
        if($andClearCache){
            $this->clearCache();
        }
    }
    
    /**
     * Crea la cache
     */
    function warmUp()
    {
        $this->getAvailableConfiguration();
    }
    
    /**
     * Limpia la cache
     */
    function clearCache()
    {
        $this->configurationCacheAvailable = null;
        $cache = $this->getConfigCache();
        @unlink($cache);
        $this->warmUp();
    }
    
    /**
     * @return MatcherDumperInterface
     */
    protected function getAvailableConfigurationDumperInstance()
    {
        $entities = $this->adapter->findAll();
        return new $this->options['configuration_dumper_class']($entities);
    }
    
    /**
     * Retorna la entidad de la base de datos de la configuracion
     * @param type $id
     * @return \Tecnocreaciones\Bundle\ToolsBundle\Model\Configuration\Configuration
     */
    protected function getConfiguration($id)
    {
        if(null === $this->configurations){
            $this->getConfigurations();
        }
        if(isset($this->configurations[$id])){
            return $this->configurations[$id];
        }
        return null;
    }
    
    /**
     * Configuraciones actuales en la base de datos
     * 
     * @return \Tecnocreaciones\Bundle\ToolsBundle\Model\Configuration\Configuration
     */
    protected function getConfigurations()
    {
        if(null !== $this->configurations){
            return $this->configurations;
        }
        $configurations = $this->adapter->findAll();
        $this->configurations = array();
        foreach ($configurations as $entity) {
            $this->configurations[$entity->getId()] = $entity;
        }
        return $this->configurations;
    }
    
}
