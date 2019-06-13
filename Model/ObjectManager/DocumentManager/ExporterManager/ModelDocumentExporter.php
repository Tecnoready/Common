<?php

namespace Tecnoready\Common\Model\ObjectManager\DocumentManager\ExporterManager;

use InvalidArgumentException;

/**
 * Modelo de exportador de documento base
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class ModelDocumentExporter
{
    /**
     * @var ChainModel
     */
    private $chainModel;
    
    /**
     * Nombre a mostrar
     * @var string 
     */
    private $name;
    
    /**
     * Nombre final que tendra el archivo.
     * @var string 
     */
    protected $fileName;
    
    /**
     * Ruta del archivo contenido del documento
     * @var string
     */
    private $filePathContent = null;
    /**
     * Ruta del archivo de la cabecera del documento
     * @var string 
     */
    private $filePathHeader = null;
    
    /**
     * Ruta del archivo generado.<b>Importante:Setear la ruta de este archivo luego de crearlo con el write</b>
     * @var string
     */
    protected $pathFileOut;

    public function __construct($name) {
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }

    /**
     * Busca la ruta del archivo que se usara como contenido
     * @return type
     * @throws InvalidArgumentException
     */
    public function getFilePathContent() {
        if($this->filePathContent === null){
            throw new InvalidArgumentException(sprintf("The filePathContent must be setter."));
        }
        return $this->filePathContent;
    }
    
    /**
     * Busca la ruta del archivo que se usara como encabezado
     * @return type
     * @throws InvalidArgumentException
     */
    public function getFilePathHeader() {
        if($this->filePathHeader === null){
            throw new InvalidArgumentException(sprintf("The filePathContent must be setter."));
        }
        return $this->filePathHeader;
    }
    
    /**
     * Hay ruta en el archivo de contenido?
     * @return boolean
     */
    public function hasFilePathContent() {
        return $this->filePathContent !== null;
    }
    /**
     * ¿Hay ruta de archivo de la cabecera?
     * @return boolean
     */
    public function hasFilePathHeader() {
        return $this->filePathHeader !== null;
    }
    
    public function setFilePathContent($filePathContent) {
        $this->filePathContent = $filePathContent;
        return $this;
    }

    public function setFilePathHeader($filePathHeader) {
        $this->filePathHeader = $filePathHeader;
        return $this;
    }

    
    /**
     * Retorna la ruta completa del archivo
     * @return string
     */
    protected function getDocumentPath(array $parameters = []) {
        return tempnam(sys_get_temp_dir(),"md_exp"); // good ;
    }

    /**
     * Retorna el tipo de document (PDF,XLS,DOC,TXT)
     * @return string
     */
    public abstract function getFormat();
    
    /**
     * Escribe el archivo en el disco
     */
    public abstract function write(array $parameters = []);
    
    public function setChainModel(ChainModel $chainModel) {
        $this->chainModel = $chainModel;
        return $this;
    }

    /**
     * @return ChainModel
     */
    protected function getChainModel()
    {
        return $this->chainModel;
    }
    
    public function getFileName()
    {
        return $this->fileName;
    }
}
