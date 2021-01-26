<?php

namespace Tecnoready\Common\Service\Template\Adapter;

use Tecnoready\Common\Model\Template\TemplateInterface;

/**
 * Motor manejador de entidades
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface AdapterInterface
{
    /**
     * Busca una plantilla desde una fuente
     * @param type $className
     * @param type $id
     * @return TemplateInterface
     */
    public function find($id) : TemplateInterface;
}
