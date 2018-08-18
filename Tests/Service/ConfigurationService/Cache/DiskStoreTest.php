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
use Tecnoready\Common\Model\Configuration\Cache\DiskStore;


/**
 * Pruebas de cache de disco de configuraciones
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DiskStoreTest extends BaseConfigurationServiceTest
{
    protected function setUp()
    {
        parent::setUp();
        $tmp = $this->getTempPath("ConfigurationService");
        $this->store = new DiskStore([
            "cache_dir" => $tmp,
        ]);
        $this->store->setAdapter(new \Tecnoready\Common\Service\ConfigurationService\Adapter\DummyAdapter());
    }
    
    public function testWarmUp() {
        $configurations = [];
        
        $this->store->warmUp($configurations);
    }
    
    public function testStore() {
        parent::excecuteStore();
    }
}
