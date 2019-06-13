<?php

namespace Tecnoready\Common\Service\ObjectManager\DocumentManager\Adapter;

use Tecnoready\Common\Service\ObjectManager\ConfigureInterface;
use Symfony\Component\HttpFoundation\File\File;
use SplFileInfo;

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
     * @param string $name Nombre opcional para reemplazar el nombre original
     * @param boolean overwrite ¿Sobrescribir archivo si existe?
     * @return boolean
     * @throws RuntimeException
     */
    public function upload(File $file,array $options = []);
    
    /**
     * Obtiene todos los archivos de la carpeta.
     * @return Finder
     */
    public function getAll();
    
    /**
     * Establece la sub carpeta a leeer
     */
    public function folder($subPath);
    
    public function getMetadata(SplFileInfo $file);
}
