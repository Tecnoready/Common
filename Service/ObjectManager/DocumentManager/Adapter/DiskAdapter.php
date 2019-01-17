<?php

namespace Tecnoready\Common\Service\ObjectManager\DocumentManager\Adapter;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Finder\Finder;
use RuntimeException;

/**
 * Adaptador para guardar los documentos en el disco
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DiskAdapter implements DocumentAdapterInterface
{

    /**
     * Opciones de configuracion
     * @var array
     */
    private $options;

    /**
     * Manipulador de archivos
     * @var Filesystem 
     */
    private $fs;
    
    /**
     * Sub carpeta donde se guardara el documento (Facturas,Presupuestos,Contratos)
     * @var string
     */
    private $folder;
    
    /**
     * Identificador unico del objeto dueno de los archivos (14114,DF-23454)
     * @var string 
     */
    private $id;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
        ]);
        $resolver->setDefined(["documents_path", "env"]);
        $resolver->addAllowedTypes("documents_path", "string");
        $resolver->addAllowedTypes("env", "string");
        $resolver->setRequired(["debug", "documents_path", "env"]);
        $this->options = $resolver->resolve($options);

        $this->fs = new Filesystem();
    }

    /**
     * Elimina un archivo
     * @param type $fileName
     * @return type
     */
    public function delete($fileName)
    {
        $fullPath = $this->getBasePath($fileName);
        $file = new File($fullPath);
        $this->fs->remove($file);
        return !$this->fs->exists($fullPath);
    }
    
    /**
     * Obtiene un archivo
     * @param type $fileName
     * @return type
     * @throws RuntimeException
     */
    public function get($fileName)
    {
        $fullPath = $this->getBasePath($fileName);
        $file = new File($fullPath,false);
        
        if(!$this->fs->exists($fullPath)){
            $file = null;
        }else if(!$file->isReadable()){
            throw new RuntimeException(sprintf("The file '%s' is not readable",$file->getPathname()));
        }
        return $file;
    }

    /**
     * Obtiene todos los archivos de la carpeta.
     * @return Finder
     */
    public function getAll()
    {
        $finder = new Finder();
        $finder->in($this->getBasePath())->depth(0)->files();
        return $finder;
    }

    /**
     * Sube un archivo
     * @param File $file
     * @return boolean
     * @throws RuntimeException
     */
    public function upload(File $file)
    {
        $fileExist = $this->get($file->getFilename());
        if($fileExist !== null){//El archivo ya existe
            return false;
        }
        $basePath = $this->getBasePath();
        $name = $file->getFilename();
        
        $file = $file->move($basePath,$name);
        if(!$file->isReadable()){
            throw new RuntimeException(sprintf("The file '%s' is not readable",$file->getPathname()));
        }
        return $file;
    }
    
    /**
     * Retorna el base path donde se guardara los archivos
     * @param type $fileName
     * @return string
     */
    private function getBasePath($fileName = null)
    {
        $ds = DIRECTORY_SEPARATOR;
        $basePath = sprintf('%s'.$ds.'%s'.$ds.'%s'.$ds.'%s', $this->options['documents_path'], $this->options['env'],  $this->folder,$this->id);
        if(!empty($fileName)){
            $basePath .= $ds.$fileName;
        }
        return $basePath;
    }
    
    public function setType($folder)
    {
        $this->folder = $folder;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

}
