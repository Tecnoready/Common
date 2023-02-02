<?php

namespace Tecnoready\Common\Model\ShowBuilder;

/**
 * Modelo de campos comunes
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class ModelShowWidget implements \JsonSerializable
{
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
    
    /**
     * (color) Color del texto
     * @var string|null
     */
    protected $textColor;
    
    /**
     * (Tamaño) Texto a mostrar
     * @var int|null
     */
    protected $textFontSize;
    
    public function __construct($widgetName) {
        $this->widget = $widgetName;
    }
    
    
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
    
     public function getTextColor(): ?string {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor) {
        $this->textColor = $textColor;
        return $this;
    }
    
    public function getTextFontSize(): ?int {
        return $this->textFontSize;
    }

    public function setTextFontSize(?int $textFontSize) {
        $this->textFontSize = $textFontSize;
        return $this;
    }
    
    public function jsonSerialize() {
        $arr = get_object_vars( $this );
        
        return $arr;
    }

}
