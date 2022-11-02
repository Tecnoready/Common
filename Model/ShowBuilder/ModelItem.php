<?php

namespace Tecnoready\Common\Model\ShowBuilder;

/**
 * Modelo de campos comunes
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class ModelItem
{
    const WIDGET_TITLE = "title";
    const WIDGET_ITEM = "item";
    const WIDGET_IMAGE = "image";
    
    /**
     * Elemnto a renderizar
     * @var string
     */
    protected $widget;
    
    /**
     * Icono en fuente
     * @var string
     */
    protected $icon;
    
    /**
     * Texto a mostrar
     * @var string
     */
    protected $text;
    
    
    public function getIcon()
    {
        return $this->icon;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

}
