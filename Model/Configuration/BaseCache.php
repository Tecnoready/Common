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

/**
 * Base para cache
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class BaseCache implements CacheInterface 
{
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
}
