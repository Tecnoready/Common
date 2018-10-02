<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Configuration;

use Tecnoready\Common\Service\ConfigurationService\CacheInterface;

/**
 * Base para cache
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class BaseCache implements CacheInterface 
{
    protected $options;

    /**
     * @var \Tecnoready\Common\Service\ConfigurationService\Adapter\ConfigurationAdapterInterface 
     */
    protected $adapter;
    
    protected function getId($key,$wrapperName) {
        return \Tecnoready\Common\Util\ConfigurationUtil::generateId($wrapperName,$key);
    }
    
    public function setAdapter(\Tecnoready\Common\Service\ConfigurationService\Adapter\ConfigurationAdapterInterface $adapter) {
        $this->adapter = $adapter;
        return $this;
    }
    
    /**
     * Encriptar text
     * @see https://gist.github.com/odan/c1dc2798ef9cedb9fedd09cdfe6e8e76
     * @param type $data
     * @return type
     */
    protected function encrypt($data)
    {
        $ivSize = openssl_cipher_iv_length($this->options["method_encrypt"]);
        $iv = openssl_random_pseudo_bytes($ivSize);

        $encrypted = openssl_encrypt($data, $this->options["method_encrypt"], $this->options["key"], OPENSSL_RAW_DATA, $iv);

        // For storage/transmission, we simply concatenate the IV and cipher text
        $encrypted = base64_encode($iv . $encrypted);

        return $encrypted;
    }

    protected function decrypt($data)
    {
        $data = base64_decode($data);
        $ivSize = openssl_cipher_iv_length($this->options["method_encrypt"]);
        $iv = substr($data, 0, $ivSize);
        $data = openssl_decrypt(substr($data, $ivSize), $this->options["method_encrypt"], $this->options["key"], OPENSSL_RAW_DATA, $iv);

        return $data;
    }
}
