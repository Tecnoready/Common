<?php

namespace Tecnoready\Common\Service\ObjectManager\DocumentManager\ExporterManager;

use Tecnoready\Common\Service\ObjectManager\ConfigureInterface;
use RuntimeException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use InvalidArgumentException;
use Tecnoready\Common\Service\ObjectManager\DocumentManager\ExporterManager\Adapter\ExporterAdapterInterface;
use Tecnoready\Common\Service\ObjectManager\DocumentManager\DocumentManager;
use Tecnoready\Common\Model\ObjectManager\DocumentManager\ExporterManager\ChainModel;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Servicio para generar y exportar documentos PDF, XLS, DOC, TXT de los modulos (app.service.exporter)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ExporterManager implements ConfigureInterface
{
    use \Tecnoready\Common\Service\ObjectManager\TraitConfigure;
    
    /**
     * Modelos disponibles para exportar
     * @var ChainModel ChainModel
     */
    private $chainModels;
    
    /**
     * Opciones de configuracion
     * @var array
     */
    private $options;
    
    /**
     * Adaptador para buscar en bases de datos
     * @var ExporterAdapterInterface
     */
    private $adapter;
    
    /**
     * @var DocumentManager 
     */
    private $documentManager;
    
    public function __construct(DocumentManager $documentManager,array $options = []) {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
        ]);
        $resolver->setRequired(["debug"]);
        $this->options = $resolver->resolve($options);
        $this->chainModels = [];
        $this->documentManager = $documentManager;
    }
    
    public function configure($objectId, $objectType)
    {
        $this->objectId = $objectId;
        $this->objectType = $objectType;
        $this->documentManager->configure($objectId, $objectType);
        return $this;
    }
    
    /**
     * Establece el adaptador a usar para consultas
     * @param ExporterAdapterInterface $adapter
     * @return $this
     * @required
     */
    public function setAdapter(ExporterAdapterInterface $adapter) 
    {
        $this->adapter = $adapter;
        return $this;
    }
    
    /**
     * Agrega un modelo de exportacion
     * @param ChainModel $chainModel
     * @throws InvalidArgumentException
     */
    public function addChainModel(ChainModel $chainModel) {
        if(isset($this->chainModels[$chainModel->getObjectType()])){
           throw new InvalidArgumentException(sprintf("The chain model to '%s' is already added, please add you model to tag '%s'",$chainModel->getClassName(),$chainModel->getClassName())); 
        }
        $this->chainModels[$chainModel->getObjectType()] = $chainModel;
    }
    
    /**
     * Verifica si existe un modelo de exportacion
     * @param type $className
     * @return type
     */
    public function hasChainModel($className)
    {
        return isset($this->chainModels[$className]);
    }
    
    /**
     * Retorna un modelo de exportacion
     * @param type $id
     * @return ChainModel
     * @throws InvalidArgumentException
     */
    protected function getChainModel($id) {
        if(!isset($this->chainModels[$id])){
           throw new InvalidArgumentException(sprintf("The chain model is not added or the id '%s' is invalid.",$id)); 
        }
        $this->chainModels[$id]->setObjectId($this->objectId);
        return $this->chainModels[$id];
    }
    
    /**
     * Retorna una opcion
     * @param type $name
     * @return type
     * @throws InvalidArgumentException
     */
    public function getOption($name) {
        if(!isset($this->options[$name])){
            throw new InvalidArgumentException(sprintf("The option name '%s' is invalid, available are %s.",$name, implode(",",array_keys($this->options))));
        }
        return $this->options[$name];
    }
    
    /**
     * Genera un documento de un modulo
     * @param type $objectType
     * @param string $name Nombre del documento pre-definido
     * @param array $options
     * @return File El archivo generado
     * @throws RuntimeException
     */
    public function generate($name,array $options = [],$overwrite = false) {
        $chainModel = $this->resolveChainModel($options);
        
        $modelDocument = $chainModel->getModel($name);
        
        $pathFileOut = $modelDocument
                ->setChainModel($chainModel)
                ->write($options["data"]);
        if($pathFileOut === null){
            throw new RuntimeException(sprintf("Failed to generate document '%s' with name '%s'",$this->objectType,$name));
        }
        $fileName = $modelDocument->getFileName();
        if(isset($options["fileName"]) && !empty($options["fileName"])){
            $fileName = $options["fileName"];
            $fileName .= ".".$modelDocument->getFormat();
            unset($options["fileName"]);
        }
        if(empty($fileName)){
            throw new RuntimeException(sprintf("The fileName can not be empty."));
        }
        if(!is_readable($pathFileOut)){
            throw new RuntimeException(sprintf("Failed to generate document '%s' with name '%s'. File '%s' is not readable.",$this->objectType,$name,$pathFileOut));
        }
        $file = new File($pathFileOut);
        $file = $this->documentManager->upload($file,[
            "overwrite" => $overwrite,
            "name" => $fileName,
        ]);
        return $file;
    }
    
    /**
     * Genera un documento a partir de un id
     * @param type $id
     * @param type $objectType
     * @param type $name
     * @param array $options
     * @return File La ruta del archivo generado
     * @throws RuntimeException
     */
    public function generateWithSource($name,array $options = [],$overwrite = false) {
        if(!$this->adapter){
            throw new RuntimeException(sprintf("The adapter must be set for enable this feature."));
        }
        $chainModel = $this->getChainModel($this->objectType);
        $className = $chainModel->getClassName();
        $entity = $this->adapter->find($chainModel->getClassName(),$this->objectId);
        if(!$entity){
            throw new RuntimeException(sprintf("The source '%s' with '%s' not found.",$className,$this->objectId));
        }
        $options["data"]["entity"] = $entity;
        return $this->generate($name,$options,$overwrite);
    }
    
    /**
     * Resuelve el modelo de exportacion y le establece los parametros
     * @param type $objectType
     * @param array $options
     * @return ChainModel
     */
    public function resolveChainModel(array $options = []) {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "data" => [],
            "fileName" => null,
        ]);
        $resolver->setAllowedTypes("data","array");
        $options = $resolver->resolve($options);
        $chainModel = $this->getChainModel($this->objectType);
        return $chainModel;
    }
    
    /**
     * @return DocumentManager
     */
    public function documents()
    {
        $this->documentManager->folder("generated");
        return $this->documentManager;
    }


}
