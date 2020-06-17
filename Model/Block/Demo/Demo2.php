<?php

namespace Tecnoready\Common\Model\Block\Demo;

use Tecnoready\Common\Model\Block\BaseWidget;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Bloque de prueba
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class Demo2 extends BaseWidget
{
    const NAME_DEMO = "widget.group.demo.2";
    
    public function getGroup() {
        return "widget.group.demo";
    }
    
    public function configureSettings(OptionsResolver $resolver)
    {
        parent::configureSettings($resolver);
        $resolver->setDefaults([
            "isTransparent" => true,
        ]);
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
        return 'block.widget.demo.2';
    }

}
