<?php

namespace Tecnoready\Common\Service\ObjectManager\DocumentManager\Adapter;

use Tecnoready\Common\Service\ObjectManager\ConfigureInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Intefaz de manejador de documentos
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface DocumentAdapterInterface extends ConfigureInterface
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
    public function upload(UploadedFile $file);
    
    /**
     * Obtiene todos los archivos de la carpeta.
     * @return Finder
     */
    public function getAll();
    
    /**
     * Establece la sub carpeta a leeer
     */
    public function folder($subPath);
}
