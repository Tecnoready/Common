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
use DateTime;

/**
 * Test de Date time transformer
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DateTimeTransformerTest extends BaseConfigurationServiceTest
{
    public function testTransformer(){
        $format = "Y-m-d H:i:s";
        $value = "2009-02-15 15:16:17";
        $data = DateTime::createFromFormat($format,$value);
        $configurationManager = $this->getManager();
        $configurationManager->addTransformer(new \Tecnoready\Common\Service\ConfigurationService\DataTransformer\DateTimeTransformer());
        $wrapper = $configurationManager->getWrapper(DefaultConfigurationWrapper::getName());
        
        $wrapper->set("EXAMPLE_DATETIME",$data,"Descripcion de valor");
        
        $configurationManager->flush();
        
        $dataDate = $wrapper->get("EXAMPLE_DATETIME");
        $this->assertTrue($dataDate instanceof DateTime);
        
        $this->assertEquals("2009",$data->format("Y"));
        $this->assertEquals($value,$data->format($value));
    }
}
