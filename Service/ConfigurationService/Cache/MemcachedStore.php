<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\ConfigurationService\Cache;

use Tecnoready\Common\Model\Configuration\BaseCache;
use Memcached;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Cache de memcached de configuraciones
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class MemcachedStore extends BaseCache 
{
    /**
     * @var \Memcached
     */
    private $memcached;
    
    /**
     * Errores que puede dar al consultar un recurso
     * @var array 
     */
    private $errorCodes = [
        Memcached::RES_NOTFOUND,
        Memcached::RES_PARTIAL_READ,
        Memcached::RES_SOME_ERRORS,
    ];

    /**
     * Constructor.
     *
     * List of available options:
     *  * prefix: The prefix to use for the memcached keys in order to avoid collision
     *  * expiretime: The time to live in seconds.
     *
     * @param \Memcached $memcached A \Memcached instance
     * @param array      $options   An associative array of Memcached options
     *
     * @throws \InvalidArgumentException When unsupported options are passed
     */
    public function __construct(Memcached $memcached, array $options = array())
    {
        $this->memcached = $memcached;
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'debug' => false,
            'prefix' => "cfms_",//Key prefix for shared environments
            'expiretime' => 86400,
            'method_encrypt' => "AES-256-CBC",
        ]);
        $resolver->setRequired(["prefix","method_encrypt","password"]);
        $this->options = $resolver->resolve($options);
        
        $this->options["key"] = hash('sha256', $this->options["password"]);
    }

    public function contains($key, $wrapperName) {
        $value = $this->memcached->get($this->getId($key, $wrapperName));
        $code = $this->memcached->getResultCode();
        if(in_array($code,$this->errorCodes)){
            $value = $this->memcached->get($this->getId($key, $wrapperName));
            $code = $this->memcached->getResultCode();
        }
        if($value === false && $code === Memcached::RES_NOTFOUND){
            return false;
        }
        return true;
    }

    public function delete($key, $wrapperName) {
        return $this->memcached->delete($this->getId($key, $wrapperName));
    }

    public function fetch($key, $wrapperName) {
        $result = $this->memcached->get($this->getId($key, $wrapperName));
        $code = $this->memcached->getResultCode();
        if(in_array($code,$this->errorCodes)){
            $result = $this->memcached->get($this->getId($key, $wrapperName));
        }
        if($result !== false){
            $result = unserialize($this->decrypt($result));
        }
        return $result;
    }

    public function flush() {
        return $this->memcached->flush();
    }

    public function save($key, $wrapperName, $data, $lifeTime = 0) {
        $data = $this->encrypt(serialize($data));
        return $this->memcached->set($this->getId($key, $wrapperName),$data);
    }

    public function warmUp(array $configurations) {
        $this->flush();
        foreach ($configurations as $key => $configuration) {
            $data = array();
            $data['value'] = $configuration->getValue();
            $data['type'] = $configuration->getType();
            $data['data_type'] = $configuration->getDataType();
            $data['description'] = $configuration->getDescription();
            $this->save($configuration->getKey(), $configuration->getNameWrapper(),$data);
        }
    }
    
    protected function getId($key, $wrapperName) {
        return $this->options["prefix"].parent::getId($key, $wrapperName);
    }
    
    
}
