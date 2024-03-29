<?php

namespace Tecnoready\Common\Service\ConfigurationService;

use Tecnoready\Common\Service\ConfigurationService\CacheInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use RuntimeException;

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
     * @var \Tecnoready\Common\Model\Configuration\Wrapper\ConfigurationWrapper
     */
    private $configurationsWrapper = null;
    /**
            
     * Adaptador origen de los datos
     * @var Adapter\ConfigurationAdapterInterface
     */
    private $adapter;
    
    /**
     * @var CacheInterface
     */
    private $cache;
    
    /**
     * Transformadores de data
     * @var DataTransformerInterface
     */
    private $transformers;
            
    function __construct(Adapter\ConfigurationAdapterInterface $adapter,CacheInterface $cache,array $options = array())
    {
        if(!class_exists("Symfony\Component\Config\ConfigCache")){
            throw new \Exception(sprintf("The package '%s' is required, please install https://packagist.org/packages/symfony/config",'"symfony/config": "^3.1"'));
        }
        if(!class_exists("Symfony\Component\OptionsResolver\OptionsResolver")){
            throw new \Exception(sprintf("The package '%s' is required, please install https://packagist.org/packages/symfony/options-resolver",'"symfony/options-resolver": "^3.1"'));
        }
        $this->setOptions($options);
        $this->adapter = $adapter;
        $this->cache = $cache;
        $this->configurationsWrapper = [];
        if($this->options["add_default_wrapper"] === true){
            $this->addWrapper(new \Tecnoready\Common\Model\Configuration\Wrapper\DefaultConfigurationWrapper());
        }
        $this->transformers = [];
    }
    
    /**
     * Añade un transformador de valores
     * @param \Tecnoready\Common\Service\ConfigurationService\DataTransformerInterface $transformer
     */
    public function addTransformer(DataTransformerInterface $transformer) {
        $this->transformers[] = $transformer;
    }
    
    /**
     * Añade un grupo de configuracion
     * @param \Tecnoready\Common\Model\Configuration\ConfigurationWrapper $configuration
     * @return \Tecnoready\Common\Model\Configuration\Wrapper\ConfigurationWrapper
     * @throws \RuntimeException
     */
    public function addWrapper(\Tecnoready\Common\Model\Configuration\Wrapper\ConfigurationWrapper $configuration) 
    {
        $name = strtoupper($configuration->getName());
        if($this->hasWrapper($name)){
            throw new \RuntimeException(sprintf("The configuration name '%s' already added",$configuration->getName()));
        }
        $configuration->setManager($this);
        $this->configurationsWrapper[$name] = $configuration;
        return $this;
    }
    /**
     * Retorna el wrapper de una configuracion
     * @param type $name
     * @return \Tecnoready\Common\Model\Configuration\Wrapper\ConfigurationWrapper
     * @throws \RuntimeException
     */
    public function getWrapper($name)
    {
        $name = strtoupper($name);
        if(!$this->hasWrapper($name)){
            throw new \RuntimeException(sprintf("The configuration name '%s' is not added",$name));
        }
        return $this->configurationsWrapper[$name];
    }
    
    public function hasWrapper($wrapperName,$throwException = false) {
        $wrapperName = strtoupper($wrapperName);
        $added = false;
        if(isset($this->configurationsWrapper[$wrapperName])){
            $added = true;
        }else{
            if($throwException === true){
                throw new \InvalidArgumentException(sprintf("The ConfigurationWrapper with name '%s' dont exist.",$wrapperName));
            }
        }
        return $added;
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
            'debug'                  => false,
            'add_default_wrapper'  => false,
        ]);
        $resolver->addAllowedTypes("add_default_wrapper","boolean");
        
        $this->options = $resolver->resolve($options);
    }
    
    /**
     * Retorna el valor de la configuracion de la base de datos
     * 
     * @param string $key Indice de la configuracion
     * @param mixed $default Valor que se retornara en caso de que no exista el indice
     * @return mixed
     */
    function get($key,$wrapperName = null,$default = null) {
        $configuration = $this->getConfiguration($key,$wrapperName);
        if($configuration !== null){
            $value = $this->reverseTransform($configuration->getValue(), $configuration);
        }else{
            $value = $default;
        }
        return $value;
    }
    
    /**
     * Establece el valor de una configuracion
     * 
     * @param string $key indice de la configuracion
     * @param mixed $value valor de la configuracion
     * @param string|null $description Descripcion de la configuracion|null para actualizar solo el key
     */
    function set($key,$value = null,$wrapperName = null,$description = null,$clearCache = false)
    {
        if($wrapperName === null){
            $wrapperName = \Tecnoready\Common\Model\Configuration\Wrapper\DefaultConfigurationWrapper::getName();
        }
        $key = strtoupper($key);
        $wrapperName = strtoupper($wrapperName);
        $this->hasWrapper($wrapperName,true);
        $configuration = $this->adapter->find($key);
        if($configuration === null){
            $configuration = $this->adapter->createNew();
            $configuration->setKey($key);
            $configuration->setValue($value);
            $configuration->setDescription($description);
            $configuration->setNameWrapper($wrapperName);
            $configuration->setCreatedAt(new \DateTime());
        }else{
            //Actualizacion de la descripcion
            if($description !== null){
                $configuration->setDescription($description);
            }
            $configuration->setUpdatedAt();
        }
        $type = gettype($value);
        $configuration->setType($type);
        $valueTransformed = $this->transform($value, $configuration);
        $configuration->setValue($valueTransformed);
        $this->adapter->persist($configuration);
        $success = $this->adapter->flush();
        
        $isWarmUp = false;
        //Sino existe el valor en la cache se debe refrescar en base la informacion actualizada
        if(!$this->cache->contains($key, $wrapperName)){
            $this->cache->flush();
            $this->warmUp();
            $isWarmUp = true;
        }else{
            $this->cache->save($key, $wrapperName, $valueTransformed);
        }
        if($success === true && $clearCache){
            $this->clearCache();
            if(!$isWarmUp){
                $this->warmUp();
            }
        }
        return $success;
    }
    
    /**
     * Busca la descripcion de una configuracion
     * @param type $key
     * @param type $wrapperName
     * @return type
     */
    public function getDescription($key,$wrapperName = null) {
        $configuration = $this->getConfiguration($key,$wrapperName);
        if($configuration !== null){
            $value = $configuration->getDescription();
        }else{
            $value = null;
        }
        return $value;
    }
    
    /**
     * Busca la configuracion del formulario de edicion por un indice
     * @param type $key
     * @return type
     * @throws RuntimeException
     */
    public function getFormEditConfig($key)
    {
        $config = null;
        foreach ($this->configurationsWrapper as $wrapper) {
            $allConfig = $wrapper->getAllFormEditConfig();
            if(isset($allConfig[$key])){
                if(!is_string($allConfig[$key][0])){
                    throw new RuntimeException(sprintf("The config parameter '0' must be a string type class."));
                }
                if(is_callable($allConfig[$key][1])){
                    $allConfig[$key][1] = call_user_func_array($allConfig[$key][1],[$this]);
                    
                }else if(!is_array($allConfig[$key][1])){
                    throw new RuntimeException(sprintf("The config parameter '1' must be a array o callable."));
                }
                $config = $allConfig[$key];
                break;
            }
        }
        return $config;
    }
    
    /**
     * Guarda los cambios en la base de datos
     */
    function flush($andClearCache = true)
    {
        $this->adapter->flush();
        if($andClearCache){
            $this->clearCache();
            $this->warmUp();
        }
    }
    
    /**
     * Crea la cache
     */
    function warmUp()
    {
        $configurations = $this->adapter->findAll();
//        foreach ($configurations as $configuration) {
//            $configuration->setValue($this->transform($value, $configuration));
//        }
        $this->cache->warmUp($configurations);
        return $this;
    }
    
    /**
     * Limpia la cache
     */
    function clearCache()
    {
        $this->cache->flush();
        return $this;
    }
    
    /**
     * Retorna la configuracion
     * @param type $key
     * @param type $wrapperName
     * @return \Tecnoready\Common\Model\Configuration\BaseEntity\ConfigurationInterface
     */
    private function getConfiguration($key,$wrapperName = null) {
        if($wrapperName === null){
            $wrapperName = \Tecnoready\Common\Model\Configuration\Wrapper\DefaultConfigurationWrapper::getName();
        }
        $key = strtoupper($key);
        $wrapperName = strtoupper($wrapperName);
        $this->cache->setAdapter($this->adapter);
        if(!$this->cache->contains($key, $wrapperName)){
            $this->clearCache();//Pre-calencar cache
            $this->warmUp();//Pre-calencar cache
        }
        return $this->cache->getConfiguration($key, $wrapperName);
    }
    
    /**
     * Convierte el valor transformado a el valor original
     * @param type $value
     * @param type $configuration
     * @return type
     */
    private function reverseTransform($value,$configuration) {
        for ($i = \count($this->transformers) - 1; $i >= 0; --$i) {
            $value = $this->transformers[$i]->reverseTransform($value,$configuration);
        }
        return $value;
    }
    
    /**
     * Transforma un valor a su valor serializable
     * @param type $value
     * @param type $configuration
     * @return type
     */
    private function transform($value, $configuration) {
        foreach ($this->transformers as $transformer) {
            $value = $transformer->transform($value, $configuration);
        }
        return $value;
    }
}
