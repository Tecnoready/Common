<?php

namespace Tecnoready\Common\Service\ObjectManager\DocumentManager\Adapter;

use Tecnoready\Common\Service\ObjectManager\DocumentManager\FilesAdapterInterface;


/**
 * Intefaz de manejador de documentos
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface DocumentAdapterInterface extends FilesAdapterInterface
{
    public function setType($folder);
    
    public function setId($id);
}
