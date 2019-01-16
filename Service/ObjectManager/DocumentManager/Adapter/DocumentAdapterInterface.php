<?php

namespace Tecnoready\Common\Service\ObjectManager\DocumentManager\Adapter;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Intefaz de manejador de documentos
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface DocumentAdapterInterface
{
    public function get($fileName);
    public function delete($fileName);
    public function upload(File $file);
    public function getAll();
    
    public function setFolder($folder);
    
    public function setId($id);
}
