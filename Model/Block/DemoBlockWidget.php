<?php

namespace Tecnoready\Common\Model\Block;

use Tecnoready\Common\Model\Block\BaseWidget;

/**
 * Bloque de prueba
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DemoBlockWidget extends BaseWidget
{
    const NAME_DEMO = "widget.group.demo.demo";
    
    public function getGroup() {
        return "widget.group.demo";
    }

    public function getNames() {
        return array(
            self::NAME_DEMO => array(
                'rol' => null,
            ),
           
        );
    }
    
    public function getDefaults()
    {
        return [self::NAME_DEMO];
    }

    public function getTemplates()
    {
        return array(
            '@TecnocreacionesTools/WidgetBox/widget_demo.html.twig' => 'default',
        );
    }

    public function getType() {
        return 'block.widget.demo';
    }

}
