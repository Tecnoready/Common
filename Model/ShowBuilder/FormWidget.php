<?php

namespace Tecnoready\Common\Model\ShowBuilder;

/**
 * Widget para renderizar formularios
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class FormWidget extends ModelShowWidget {

    /**
     * Contenido del formulario
     * @var string
     */
    protected $content;
    
    public function __construct() {
        parent::__construct("form");
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

}
