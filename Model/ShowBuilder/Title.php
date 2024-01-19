<?php

namespace Tecnoready\Common\Model\ShowBuilder;

/**
 * Titulo
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class Title extends ModelShowWidget
{
    /**
     * Sub titulo a mostrar
     * @var string
     */
    protected $subTitle;
    
    public function __construct()
    {
        parent::__construct("title");
    }
    
    public function getSubTitle() {
        return $this->subTitle;
    }

    public function setSubTitle($subTitle) {
        $this->subTitle = $subTitle;
        return $this;
    }

}
