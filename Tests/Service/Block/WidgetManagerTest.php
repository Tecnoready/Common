<?php

namespace Tecnoready\Common\Tests\Service\Block;

use Tecnoready\Common\Service\Block\WidgetManager;
use Tecnoready\Common\Model\Block\Adapter\WidgetORMAdapter;
use Tecnoready\Common\Tests\BaseWebTestCase;
use Tecnoready\Common\Model\Block\DemoBlockWidget;

/**
 * Pruebas del manejador de widget
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class WidgetManagerTest extends BaseWebTestCase
{
    public function testBuild()
    {
        $container = $this->client->getContainer();
        $adapter = new WidgetORMAdapter(\App\Entity\M\Core\BlockWidgetBox::class);
        $adapter->setContainer($container);
        
        $demo = new DemoBlockWidget("tecno.block.widget.demo", $container->get("templating"));
        $demo->setContainer($container);
        
        $widgetManager = new WidgetManager($adapter);
        
        $widgetManager->addDefinitionsBlockGrid($demo);
    }
}
