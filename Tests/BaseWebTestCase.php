<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */ 

namespace Tecnoready\Common\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tecnocreaciones\Bundle\ToolsBundle\Entity\Configuration\Configuration;

/**
 * Base de pruebas
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class BaseWebTestCase extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    protected $tmpPath;
    
    protected function setUp():void
    {
        $this->client = static::createClient();
        
        $this->em = $this->client->getContainer()->get("doctrine")->getManager();
        $this->em->createQuery(sprintf('DELETE %s p',Configuration::class))->execute();
        
        $tmp = realpath(__DIR__."/Temp");
        $this->tmpPath = $tmp;
       
    }
    
    
    protected function getTempPath($dirname) {
        $tmp = $this->tmpPath."/".$dirname;
        @mkdir($tmp, 0777,true);
        return $tmp;
    }
}
