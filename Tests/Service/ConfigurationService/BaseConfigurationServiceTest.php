<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Tests\Service\ConfigurationService;

use Tecnoready\Common\Tests\BaseWebTestCase;
use Tecnoready\Common\Service\ConfigurationService\ConfigurationManager;
use Tecnoready\Common\Service\ConfigurationService\DataTransformer\DoctrineORMTransformer;
use Tecnoready\Common\Service\ConfigurationService\Adapter\DoctrineORMAdapter;
use Tecnocreaciones\Bundle\ToolsBundle\Entity\Configuration\Configuration;
use Tecnoready\Common\Service\ConfigurationService\Cache\DiskStore;

/**
 * Base de test para servicio de configuracion
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class BaseConfigurationServiceTest extends BaseWebTestCase
{
    /**
     * @var \Tecnoready\Common\Model\Configuration\CacheInterface
     */
    protected $store;
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    /**
     * @param ConfigurationAdapterInterface $adapter
     * @return ConfigurationManager
     * @param ConfigurationAdapterInterface $adapter
     * @return ConfigurationManager
     */
    protected function getConfigurationManager(\Tecnoready\Common\Service\ConfigurationService\Adapter\ConfigurationAdapterInterface $adapter) {
        $container = $this->client->getContainer();
        $cache = new DiskStore([
            "cache_dir" => $container->getParameter("kernel.cache_dir"),
            "password" => "2dd1ceb4cdf768d97484a9c89cf81a83",
        ]);
//        $memcached = new\Memcached();
//        $memcached->addServer("localhost","11211");
//        $cache = new \Tecnoready\Common\Model\Configuration\Cache\MemcachedStore($memcached,[
//            "password" => "03f4f012ef498e33a6737369f3d3afe4",
//        ]);
        $configurationManager = new \Tecnoready\Common\Service\ConfigurationService\ConfigurationManager($adapter,$cache,[
            "add_default_wrapper" => true,
        ]);
        return $configurationManager;
    }
    
    /**
     * @return ConfigurationManager
     */
    protected function getManager() {
        $em = $this->em;
        $adapter = new DoctrineORMAdapter($em,Configuration::class);
        $configurationManager = $this->getConfigurationManager($adapter);
        $doctrineORMTransformer = new DoctrineORMTransformer($em);
        $configurationManager->addTransformer($doctrineORMTransformer);
        return $configurationManager;
    }
    
    protected function getConfigurations1() {
        $configurations = [];
        $configuration = new Configuration();
        $configuration
            ->setKey("Demo_abc")
            ->setValue("Valor")
            ->setNameWrapper("CORE")
            ->setDescription("Descripcion")
            ->setCreatedAt(new \DateTime())
                ;
        $configurations[] = $configuration;
        $configuration = new Configuration();
        $configuration
            ->setKey("KEY_2")
            ->setValue("Valor a guadar")
            ->setNameWrapper("DEMO")
            ->setDescription("Descripcion")
            ->setCreatedAt(new \DateTime())
                ;
        $configurations[] = $configuration;
        
        return $configurations;
    }
    
    public function excecuteStore() {
        $configurations = $this->getConfigurations1();
        
        $this->store->warmUp($configurations);
        $data = $this->store->fetch($configurations[0]->getKey(), $configurations[0]->getNameWrapper());
        $this->assertArrayHasKey("value",$data);
        $this->assertArrayHasKey("type",$data);
        $this->assertArrayHasKey("data_type",$data);
        
        $configuration = $this->store->getConfiguration("INVALID", $configurations[0]->getNameWrapper());
        $this->assertNull($configuration);
        
        $configuration = $this->store->getConfiguration($configurations[0]->getKey(), $configurations[0]->getNameWrapper());
        $this->assertNotNull($configuration);
        $this->assertEquals($configurations[0]->getValue(), $configuration->getValue());
        $this->assertNotEquals($configurations[1]->getValue(), $configuration->getValue());
        
        //Actualizando valor de key en cache
        $data["value"] = "actualizado";
        $this->store->save($configurations[0]->getKey(), $configurations[0]->getNameWrapper(), $data);
        $data = $this->store->fetch($configurations[0]->getKey(), $configurations[0]->getNameWrapper());
        $this->assertEquals("actualizado",$data["value"]);
        
        //Eliminando valor de key en cache
        $this->store->delete($configurations[0]->getKey(), $configurations[0]->getNameWrapper());
        $this->assertFalse($this->store->contains($configurations[0]->getKey(), $configurations[0]->getNameWrapper()));
        
        //Limpiando cache
        $this->store->flush();
        $this->assertFalse($this->store->contains($configurations[1]->getKey(), $configurations[1]->getNameWrapper()));
    }
}
