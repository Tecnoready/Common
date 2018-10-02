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

/**
 * Prueba la transformacion de arraya base de datos
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ArrayDataTransformerTest extends BaseConfigurationServiceTest 
{
    public function testTransformer(){
        $data = ["clave" => "valor","aja","otra" => "dale"];
        
        $configurationManager = $this->getManager();
        $configurationManager->addTransformer(new \Tecnoready\Common\Service\ConfigurationService\DataTransformer\ArrayDataTransformer());
        $wrapper = $configurationManager->getWrapper(DefaultConfigurationWrapper::getName());
        
        $wrapper->set("EXAMPLE_ARRAY",$data,"Descripcion de valor");
        
        $configurationManager->flush();
        
        $dataArray = $wrapper->get("EXAMPLE_ARRAY");
        $this->assertTrue(is_array($dataArray));
        $this->assertArrayHasKey("clave",$dataArray);
        $this->assertArrayHasKey("otra",$dataArray);
        $this->assertEquals("valor",$dataArray["clave"]);
    }
}
