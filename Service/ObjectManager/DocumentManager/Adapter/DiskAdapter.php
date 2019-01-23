<?php

namespace Tecnoready\Common\Service\ObjectManager\DocumentManager\Adapter;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    use \Tecnoready\Common\Service\ObjectManager\TraitConfigure;
    
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
     * Sub carpeta a trabajar (si es null se trabaja en la raiz)
     * @var string 
     */
    private $folder = null;
    
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
     * @return File
     * @throws RuntimeException
     */
    public function get($fileName)
    {
        $fullPath = $this->getBasePath($fileName);
        $file = new File($fullPath,false);
        
        if(!$this->fs->exists($fullPath)){
            $file = null;
        }else{
            if(!$file->isReadable()){
                throw new RuntimeException(sprintf("The file '%s' is not readable",$file->getPathname()));
            }
            if($file->isDir()){
                throw new RuntimeException(sprintf("The file pass '%s' is a dir",$file->getPathname()));
            }
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
    public function upload(File $file,$name = null,$overwrite = false)
    {
        if($name === null && $file instanceof UploadedFile){
            $name = $file->getClientOriginalName();
        }
        $fileExist = $this->get($name);
//        var_dump("fileExist $overwrite: ".$fileExist);
        if($overwrite === false && $fileExist !== null){//El archivo ya existe
            return false;
        }
        $basePath = $this->getBasePath();
        
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
        $basePath = sprintf('%s'.$ds.'%s'.$ds.'%s'.$ds.'%s', $this->options['documents_path'], $this->options['env'],  $this->objectType,$this->objectId);
        if(empty($this->folder)){
            throw new \RuntimeException(sprintf("The 'folder' is not set in Document Manager."));
        }
        $basePath .= $ds.$this->folder;
        if(!$this->fs->exists($basePath)){
            $this->fs->mkdir($basePath);
        }
        if(!empty($fileName)){
            $basePath .= $ds.$fileName;
        }
        return $basePath;
    }
    
    public function folder($subPath)
    {
        $this->folder = $subPath;
        return $this;
    }
}
