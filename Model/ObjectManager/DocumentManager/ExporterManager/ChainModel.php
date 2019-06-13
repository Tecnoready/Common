<?php

namespace Tecnoready\Common\Model\ObjectManager\DocumentManager\ExporterManager;

use RuntimeException;

/**
 * Agrupa los modelos de exportacion de un modulo
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ChainModel
{
    /**
     * Tipo de objeto que genera el historial (Facturas,Presupuestos,Contratos)
     * @var string
     */
    private $objectType;
    
    /**
     * Identificador unico del objeto dueno de los archivos (14114,DF-23454)
     * @var string 
     */
    private $objectId;
    

    /**
     * Clase que soporta el modelo
     * @var type 
     */
    private $className;

    /**
     * Documentos
     * @var ModelDocument
     */
    private $models;

    public function __construct($objectType, $className)
    {
        $this->objectType = $objectType;
        $this->className = $className;
        $this->models = [];
    }

    /**
     * Añade un modelo de documentos
     * @param \AppBundle\Model\Core\Exporter\ModelDocument $modelDocument
     * @return $this
     * @throws \RuntimeException
     */
    public function add(ModelDocumentExporter $modelDocument)
    {
        if (isset($this->models[$modelDocument->getName()])) {
            throw new RuntimeException(sprintf("The model document name '%s' is already added in module '%s'", $modelDocument->getName(), $this->objectType));
        }
        $this->models[$modelDocument->getName()] = $modelDocument;
        return $this;
    }

    /**
     * Retorna el modelo de un documento por su nombre
     * @param type $name
     * @return ModelDocument
     * @throws RuntimeException
     */
    public function getModel($name)
    {
        if (!isset($this->models[$name])) {
            throw new RuntimeException(sprintf("The model document name '%s' is not added in chain model '%s'", $name, $this->objectType));
        }
        return $this->models[$name];
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getModels()
    {
        $models = [];
        foreach ($this->models as $k => $v) {
            $models[] = $this->getModel($k);
        }
        return $models;
    }

    public function getObjectType()
    {
        return $this->objectType;
    }
    
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
        return $this;
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }
}
