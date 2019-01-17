<?php

namespace Tecnoready\Common\Service\ObjectManager;

/**
 * Administrador de datos de un objeto (documentos,notas,historial)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ObjectDataManager
{
    /**
     * @var DocumentManager\DocumentManager
     */
    private $documentManager;
    
    /**
     * Configura el servicio para manejar un objeto y tipo en especifico
     * @param type $id
     * @param type $type
     */
    public function configure($id,$type)
    {
        $this->documentManager->configure($id, $type);
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
}
