<?php

namespace Tecnoready\Common\Model\ShowBuilder;

/**
 * Wiget imagen
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class Image extends ModelShowWidget
{
    /**
     * Ancho
     * @var string
     */
    protected $width;
    
    /**
     * Alto
     * @var string
     */
    protected $height;
    
    public function __construct()
    {
        parent::__construct("image");
    }
    
    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }
}
