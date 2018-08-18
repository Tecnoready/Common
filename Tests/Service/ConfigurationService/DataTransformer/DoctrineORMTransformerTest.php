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

use Tecnoready\Common\Service\ConfigurationService\ConfigurationManager;
use Tecnoready\Common\Service\ConfigurationService\DataTransformer\DoctrineORMTransformer;
use Tecnoready\Common\Service\ConfigurationService\Adapter\ConfigurationAdapterInterface;
use Tecnocreaciones\Bundle\ToolsBundle\Service\ConfigurationService\Adapter\DoctrineORMAdapter;
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
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    
    protected function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get("doctrine")->getManager();
        
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
    
    public function testEntityTransformer() {
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
        
        $wrapper->set("EXAMPLE",$configuration,"Descripcion de valor");
        
        $configurationManager->flush();
    }
}
