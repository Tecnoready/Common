<?php

namespace Tecnoready\Common\Service\ObjectManager;

/**
 * Trait de campos de configuracion
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
trait TraitConfigure
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
    
    public function configure($objectId, $objectType,array $options = [])
    {
        $this->objectId = $objectId;
        $this->objectType = $objectType;
    }
}
