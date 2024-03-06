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
     * @var array
     */
    protected $id;

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
     * (color) Color del texto (color dinamico de tema)
     * @var string|null
     */
    protected $textColorResource;
    
    /**
     * (Tamaño) Texto a mostrar
     * @var int|null
     */
    protected $textFontSize;
    
    /**
     * Ancho del elemento
     * @var string
     */
    protected $widthRequest;
    
    public function __construct($widgetName)
    {
        $this->widget = $widgetName;
    }

    public function setId(?string $id)
    {
        $this->id = $id;

        return $this;
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
    
    public function getTextColorResource() {
        return $this->textColorResource;
    }

    public function getWidthRequest() {
        return $this->widthRequest;
    }

    public function setTextColorResource($textColorResource) {
        $this->textColorResource = $textColorResource;
        return $this;
    }

    public function setWidthRequest($widthRequest) {
        $this->widthRequest = $widthRequest;
        return $this;
    }

        
    public function jsonSerialize() {
        $arr = get_object_vars( $this );
        
        return $arr;
    }

}
