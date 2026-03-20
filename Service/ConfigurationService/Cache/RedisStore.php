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
use Redis;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Cache de Redis de configuraciones
 *
 * @author Rafael Rivero <rafalejandrorivero@gmail.com>
 */
class RedisStore extends BaseCache 
{
    /**
     * @var \Redis
     */
    private $redis;

    /**
     * Constructor.
     *
     * List of available options:
     *  * prefix: The prefix to use for the memcached keys in order to avoid collision
     *  * expiretime: The time to live in seconds.
     *
     * @param \Redis $redis A \Redis instance
     * @param array      $options   An associative array of Redis options
     *
     * @throws \InvalidArgumentException When unsupported options are passed
     */
    public function __construct($host, $port, $password, array $options = array())
    {
        $redis = new \Redis();
        $redis->connect($host, $port);
        if ($password !== null && $password !== '') {
            $redis->auth($password);
        }
        $this->redis = $redis;
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'debug' => false,
            'prefix' => "cfms_",//Key prefix for shared environments
            'expiretime' => 86400,
            'method_encrypt' => "AES-256-CBC",
        ]);
        $resolver->setRequired(["prefix","method_encrypt"]);
        $this->options = $resolver->resolve($options);
        $this->options["key"] = hash('sha256', $password);
    }

    public function contains($key, $wrapperName)
    {
        $value = $this->redis->get($this->getId($key, $wrapperName));

        if ($value === false) {
            return false;
        }

        return true;
    }

    public function delete($key, $wrapperName)
    {
        $deleted = $this->redis->del($this->getId($key, $wrapperName));

        return $deleted > 0;
    }

    public function fetch($key, $wrapperName)
    {
        $result = $this->redis->get($this->getId($key, $wrapperName));

        if ($result !== false) {
            $result = unserialize($this->decrypt($result));
        }

        return $result;
    }

    public function flush()
    {
        return $this->redis->flushDB();
    }

    public function save($key, $wrapperName, $data, $lifeTime = 0)
    {
        $data = $this->encrypt(serialize($data));
        $id = $this->getId($key, $wrapperName);

        if ($lifeTime > 0) {
            return (bool) $this->redis->setex($id, $lifeTime, $data);
        }

        return (bool) $this->redis->set($id, $data);
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
        return sprintf('%s:%s:%s', $this->options['prefix'], $wrapperName, $key);
    }
    
}
