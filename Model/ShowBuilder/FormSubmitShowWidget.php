<?php

namespace Tecnoready\Common\Model\ShowBuilder;

/**
 * Boton para enviar formularios
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class FormSubmitShowWidget extends ModelShowWidget {
    
    public function __construct()
    {
        parent::__construct("form_submit");
        $this->text = "Submit";
    }
}
