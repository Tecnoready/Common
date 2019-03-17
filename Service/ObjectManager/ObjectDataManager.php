<?php

namespace Tecnoready\Common\Service\ObjectManager;

use Tecnoready\Common\Service\ObjectManager\ConfigureInterface;
use Tecnoready\Common\Service\ObjectManager\DocumentManager\ExporterManager\ExporterManager;

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
     * Manejador de notas
     * @var NoteManager\NoteManager
     */
    private $noteManager;
    
    /**
     * Exportador de documentos
     * @var ExporterManager
     */
    private $exporterManager;
    
    /**
     * @var StatisticManager\StatisticsManager
     */
    private $statisticsManager;
    
    public function __construct(DocumentManager\DocumentManager $documentManager, HistoryManager\HistoryManager $historyManager, NoteManager\NoteManager $noteManager,ExporterManager $exporterManager)
    {
        $this->documentManager = $documentManager;
        $this->historyManager = $historyManager;
        $this->noteManager = $noteManager;
        $this->exporterManager = $exporterManager;
    }

    
    /**
     * Configura el servicio para manejar un objeto y tipo en especifico
     * @param type $objectId
     * @param type $objectType
     * @return \Tecnoready\Common\Service\ObjectManager\ObjectDataManager
     */
    public function configure($objectId, $objectType)
    {
        if($this->documentManager){
            $this->documentManager->configure($objectId, $objectType);
        }
        if($this->historyManager){
            $this->historyManager->configure($objectId, $objectType);
        }
        if($this->noteManager){
            $this->noteManager->configure($objectId, $objectType);
        }
        if($this->exporterManager){
            $this->exporterManager->configure($objectId, $objectType);
        }
        if($this->statisticsManager){
            $this->statisticsManager->configure($objectId, $objectType);
        }

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
    
    /**
     * Retorna el manejador de notas
     * @return NoteManager\NoteManager
     */
    public function notes()
    {
        return $this->noteManager;
    }
    
    /**
     * Retorn ael exportaror de documentos
     * @return ExporterManager
     */
    public function exporter()
    {
        return $this->exporterManager;
    }
}
