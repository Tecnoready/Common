<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Tests\Service\ConfigurationService\Cache;

use Tecnoready\Common\Tests\Service\ConfigurationService\BaseConfigurationServiceTest;
use Tecnoready\Common\Model\Configuration\Cache\MemcachedStore;
use Memcached;

/**
 * Prueba de cache con memcached
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class MemcachedStoreTest extends BaseConfigurationServiceTest
{
    protected function setUp()
    {
        parent::setUp();
        $memcached = new Memcached();
        $memcached->addServer("localhost","11211");
        $this->store = new MemcachedStore($memcached,[
            "password" => "03f4f012ef498e33a6737369f3d3afe4",
        ]);
        $this->store->setAdapter(new \Tecnoready\Common\Service\ConfigurationService\Adapter\DummyAdapter());
    }
    
    public function testWarmUp() {
        $configurations = $this->getConfigurations1();
        
        $this->store->warmUp($configurations);
    }
    
    public function testStore() {
        parent::excecuteStore();
    }
}
