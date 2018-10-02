<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Tests\Service\ConfigurationService\DataTransformer;

use Tecnoready\Common\Tests\Service\ConfigurationService\BaseConfigurationServiceTest;
use Tecnoready\Common\Model\Configuration\Wrapper\DefaultConfigurationWrapper;
use Tecnocreaciones\Bundle\ToolsBundle\Entity\Configuration\Configuration;
use Tecnoready\Common\Model\DemoClass;

/**
 * Test de transformer de entidades de doctrine
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMTransformerTest extends BaseConfigurationServiceTest
{
    
    protected function setUp()
    {
        parent::setUp();
        $this->em->createQuery(sprintf('DELETE %s p',Configuration::class))->execute();
    }
    
    /**
     * Prueba que da error al intentar convertir un objeto no manejado
     */
    public function testErrorTransformer() {
        $configurationManager = $this->getManager();
        
        $noManaged = new DemoClass();
        $wrapper = $configurationManager->getWrapper(DefaultConfigurationWrapper::getName());
        try {
            $wrapper->set("EXAMPLE",$noManaged);
        } catch (\Exception $ex) {
            $this->assertEquals("Object of class Tecnoready\Common\Model\DemoClass could not be converted to string", $ex->getMessage());
        }
    }
    
    public function testTransformer() {
        $em = $this->em;
        
        $configuration = new Configuration();
        $configuration
            ->setKey("Demo_abc")
            ->setValue("Valor")
            ->setNameWrapper("WRAPPER")
            ->setDescription("Descripcion")
            ->setCreatedAt(new \DateTime())
                ;
        $em->persist($configuration);
        $em->flush();
        
        $configurationManager = $this->getManager();
        $wrapper = $configurationManager->getWrapper(DefaultConfigurationWrapper::getName());
        
        $wrapper->set("EXAMPLE_ENTITY",$configuration,"Descripcion de valor");
        
        $configurationManager->flush();
        
        $config = $wrapper->get("EXAMPLE_ENTITY");
        $this->assertEquals(get_class($configuration),get_class($config));
        $this->assertEquals($configuration->getId(),$config->getId());
        $this->assertEquals($configuration->getDescription(),$config->getDescription());
    }
}
