<?php

namespace Tecnoready\Common\Service\ObjectManager;

use Tecnoready\Common\Service\ObjectManager\ConfigureInterface;

/**
 * Administrador de datos de un objeto (documentos,notas,historial)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ObjectDataManager implements ConfigureInterface
{
    /**
     * Manejador de documentos
     * @var DocumentManager\DocumentManager
     */
    private $documentManager;
    
    /**
     * Manejador del historial
     * @var HistoryManager\HistoryManager
     */
    private $historyManager;
    
    /**
     * Configura el servicio para manejar un objeto y tipo en especifico
     * @param type $objectId
     * @param type $objectType
     * @return \Tecnoready\Common\Service\ObjectManager\ObjectDataManager
     */
    public function configure($objectId, $objectType)
    {
        $this->documentManager->configure($objectId, $objectType);
        $this->historyManager->configure($objectId, $objectType);
        return $this;
    }
    
    /**
     * Retorna el manejador de documentos
     * @return DocumentManager\DocumentManager
     */
    public function documents()
    {
        return $this->documentManager;
    }
    
    /**
     * Retorna el manejador de historiales
     * @return HistoryManager\HistoryManager
     */
    public function histories()
    {
        return $this->historyManager;
    }
}
