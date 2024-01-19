<?php

namespace Tecnoready\Common\Service\ObjectManager\DocumentManager;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Tecnoready\Common\Service\ObjectManager\DocumentManager\Adapter\DocumentAdapterInterface;
use Symfony\Component\HttpFoundation\File\File;
use RuntimeException;
use SplFileInfo;

/**
 * Administrador de documentos
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DocumentManager implements DocumentAdapterInterface
{
    /**
     * Opciones de configuracion
     * @var array
     */
    private $options;
    
    /**
     * Adaptador
     * @var DocumentAdapterInterface 
     */
    private $adapter;
    
    /**
     * @param DocumentAdapterInterface $adapter adaptador a usar para consultas
     * @param array $options
     */
    public function __construct(DocumentAdapterInterface $adapter,array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
            "allowed_extensions" => ["txt","zip","rar","docx","doc","xls","xlsx","png","jpeg","jpg"],
            "allow_folders" => ["uploaded","generated"],
        ]);
        $resolver->addAllowedTypes("allow_folders", "array");
        $this->options = $resolver->resolve($options);
        $this->adapter = $adapter;
    }
    
    /**
     * Configura el servicio para manejar un objeto y tipo en especifico
     * @param type $id
     * @param type $type
     */
    public function configure($objectId, $objectType,array $options = [])
    {
        $this->adapter->configure($objectId, $objectType,$options);
        $this->adapter->folder(null);
    }

    public function delete($fileName)
    {
        return $this->adapter->delete($fileName);
    }

    public function get($fileName)
    {
        return $this->adapter->get($fileName);
    }

    public function getAll()
    {
        return $this->adapter->getAll();
    }

    public function toArray(\Symfony\Component\Finder\SplFileInfo $file)
    {
        return $this->adapter->toArray($file);
    }

    /**
     * Sube un documento
     * @param File $file
     * @param array $options
     * @return File
     */
    public function upload(File $file,array $options = [])
    {
        return $this->adapter->upload($file,$options);
    }
    
    /**
     * Retorna la meta indormacion de un archivo
     * @param File $file
     * @return array
     */
    public function getMetadata(SplFileInfo $file){
        return $this->adapter->getMetadata($file);
    }
    
    /**
     * 
     * @param type $subPath
     * @return type
     * @throws RuntimeException
     */
    public function folder($subPath)
    {
        if(!in_array($subPath,$this->options["allow_folders"])){
            throw new RuntimeException(sprintf("The sub folder '%s' is not allowed. Olny are '%s'",$subPath,implode(",",$this->options["allow_folders"])));
        }
        $this->adapter->folder($subPath);
        
        return $this;
    }
}
