<?php

namespace Tecnoready\Common\Service\ObjectManager\DocumentManager;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Interfaz base del adaptador de archivo
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface FilesAdapterInterface
{
    /**
     * Obtiene un archivo
     * @param type $fileName
     * @return type
     * @throws RuntimeException
     */
    public function get($fileName);
    
    /**
     * Elimina un archivo
     * @param type $fileName
     * @return type
     */
    public function delete($fileName);
    
    /**
     * Sube un archivo
     * @param File $file
     * @return boolean
     * @throws RuntimeException
     */
    public function upload(File $file);
    
    /**
     * Obtiene todos los archivos de la carpeta.
     * @return Finder
     */
    public function getAll();
}
