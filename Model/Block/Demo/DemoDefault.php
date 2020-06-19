<?php

namespace Tecnoready\Common\Model\Block\Demo;

use Tecnoready\Common\Model\Block\BaseWidget;

/**
 * Bloque de prueba
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DemoDefault extends BaseWidget
{
    const NAME_DEMO = "widget.group.default";
    
    public function getGroup() {
        return "widget.group.default";
    }

    public function getNames() {
        return array(
            self::NAME_DEMO => array(
                'rol' => null,
            ),
           
        );
    }
    
    public function configureSettings(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        parent::configureSettings($resolver);
        $resolver->setDefaults([
            "title" => "title.widget.default.info",
            "icon" => "fas fa-info-circle",
        ]);
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
        return 'block.widget.default';
    }

}
