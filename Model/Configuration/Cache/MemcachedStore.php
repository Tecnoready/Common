<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Configuration\Cache;

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
    
    private $options;

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
        if($value === false && $this->memcached->getResultCode() === Memcached::RES_NOTFOUND){
            return false;
        }
        return true;
    }

    public function delete($key, $wrapperName) {
        return $this->memcached->delete($this->getId($key, $wrapperName));
    }

    public function fetch($key, $wrapperName) {
        $result = $this->memcached->get($this->getId($key, $wrapperName));
        if($result !== false){
            $result = unserialize($this->decrypt($result));
        }
        return $result;
    }

    public function flush() {
        return $this->memcached->flush();
    }

    public function getConfiguration($key, $wrapperName) {
         if($this->contains($key, $wrapperName)){
            $data = $this->fetch($key, $wrapperName);
            $configuration = $this->adapter->createNew();
            $configuration->setValue($data["value"]);
            $configuration->setType($data["type"]);
            $configuration->setDataType($data["data_type"]);
            return $configuration;
        }
        return null;
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
//            var_dump($configuration->getKey());
            $this->save($configuration->getKey(), $configuration->getNameWrapper(),$data);
//            $this->save($configuration->getKey(), $configuration->getNameWrapper(), var_export($data,true));
        }
    }
    
    protected function getId($key, $wrapperName) {
        return $this->options["prefix"].parent::getId($key, $wrapperName);
    }
    
    /**
     * Encriptar text
     * @see https://gist.github.com/odan/c1dc2798ef9cedb9fedd09cdfe6e8e76
     * @param type $data
     * @return type
     */
    public function encrypt($data)
    {
        $ivSize = openssl_cipher_iv_length($this->options["method_encrypt"]);
        $iv = openssl_random_pseudo_bytes($ivSize);

        $encrypted = openssl_encrypt($data, $this->options["method_encrypt"], $this->options["key"], OPENSSL_RAW_DATA, $iv);

        // For storage/transmission, we simply concatenate the IV and cipher text
        $encrypted = base64_encode($iv . $encrypted);

        return $encrypted;
    }

    public function decrypt($data)
    {
        $data = base64_decode($data);
        $ivSize = openssl_cipher_iv_length($this->options["method_encrypt"]);
        $iv = substr($data, 0, $ivSize);
        $data = openssl_decrypt(substr($data, $ivSize), $this->options["method_encrypt"], $this->options["key"], OPENSSL_RAW_DATA, $iv);

        return $data;
    }
}
