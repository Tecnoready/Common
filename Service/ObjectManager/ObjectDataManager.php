<?php

namespace Tecnoready\Common\Service\ObjectManager;

use Tecnoready\Common\Service\ObjectManager\ConfigureInterface;
use Tecnoready\Common\Service\ObjectManager\DocumentManager\ExporterManager\ExporterManager;
use Tecnoready\Common\Exception\UnconfiguredException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Administrador de datos de un objeto (documentos,notas,historial,estadisticas)
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
    
    public function __construct(DocumentManager\DocumentManager $documentManager,
            HistoryManager\HistoryManager $historyManager, NoteManager\NoteManager $noteManager,
            ExporterManager $exporterManager,StatisticManager\StatisticsManager $statisticsManager)
    {
        $this->documentManager = $documentManager;
        $this->historyManager = $historyManager;
        $this->noteManager = $noteManager;
        $this->exporterManager = $exporterManager;
        $this->statisticsManager = $statisticsManager;
    }

    
    /**
     * Configura el servicio para manejar un objeto y tipo en especifico
     * @param type $objectId
     * @param type $objectType
     * @return \Tecnoready\Common\Service\ObjectManager\ObjectDataManager
     */
    public function configure($objectId, $objectType,array $options = [])
    {
        $resolver = new OptionsResolver();
        $defaults = [
            "document" => [],
            "history" => [],
            "note" => [],
            "exporter" => [],
            "statistics" => [],
        ];
        $resolver->setDefaults($defaults);
        foreach ($defaults as $option => $value) {
            $resolver->setAllowedTypes($option,"array");
        }
        $options = $resolver->resolve($options);
        if($this->documentManager){
            $this->documentManager->configure($objectId, $objectType,$options["document"]);
        }
        if($this->historyManager){
            $this->historyManager->configure($objectId, $objectType,$options["history"]);
        }
        if($this->noteManager){
            $this->noteManager->configure($objectId, $objectType,$options["note"]);
        }
        if($this->exporterManager){
            $this->exporterManager->configure($objectId, $objectType,$options["exporter"]);
        }
        if($this->statisticsManager){
            $this->statisticsManager->configure($objectId, $objectType,$options["statistics"]);
        }

        return $this;
    }
    
    /**
     * Retorna el manejador de documentos
     * @return DocumentManager\DocumentManager
     */
    public function documents()
    {
        if(!$this->documentManager){
            throw new UnconfiguredException(sprintf("El '%s' no esta configurado para usar esta caracteristica.",DocumentManager\DocumentManager::class));
        }
        return $this->documentManager;
    }
    
    /**
     * Retorna el manejador de historiales
     * @return HistoryManager\HistoryManager
     */
    public function histories()
    {
        if(!$this->historyManager){
            throw new UnconfiguredException(sprintf("El '%s' no esta configurado para usar esta caracteristica.", HistoryManager\HistoryManager::class));
        }
        return $this->historyManager;
    }
    
    /**
     * Retorna el manejador de notas
     * @return NoteManager\NoteManager
     */
    public function notes()
    {
        if(!$this->noteManager){
            throw new UnconfiguredException(sprintf("El '%s' no esta configurado para usar esta caracteristica.", NoteManager\NoteManager::class));
        }
        return $this->noteManager;
    }
    
    /**
     * Retorn ael exportaror de documentos
     * @return ExporterManager
     */
    public function exporter()
    {
        if(!$this->exporterManager){
            throw new UnconfiguredException(sprintf("El '%s' no esta configurado para usar esta caracteristica.", ExporterManager::class));
        }
        return $this->exporterManager;
    }
    
    /**
     * @return StatisticManager\StatisticsManager
     * @throws UnconfiguredException
     */
    public function statistics()
    {
        if(!$this->statisticsManager){
            throw new UnconfiguredException(sprintf("El '%s' no esta configurado para usar esta caracteristica.", StatisticManager\StatisticsManager::class));
        }
        return $this->statisticsManager;
    }
}
