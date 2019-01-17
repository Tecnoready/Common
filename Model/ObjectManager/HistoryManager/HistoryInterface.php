<?php

namespace Tecnoready\Common\Model\ObjectManager\HistoryManager;

use Tecnoready\Common\Model\ObjectManager\BaseInterface;

/**
 * Intefaz de historial
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface HistoryInterface extends BaseInterface
{
    /**
     * Historial tipo por defecto
     */
    const TYPE_DEFAULT = "default";
    
    /**
     * Historial tipo de error
     */
    const TYPE_DANGER = "error";
    /**
     * Historial tipo de exito
     */
    const TYPE_SUCCESS = "success";
    /**
     * Historial tipo de alerta
     */
    const TYPE_WARNING = "warning";
    /**
     * Historial tipo de informacion
     */
    const TYPE_INFO = "info";
    /**
     * Historial tipo de depuracion
     */
    const TYPE_DEBUG = "debug";
    
    public function setEventName($eventName);
    
    public function getEventName();
    
    public function getType();
    
    public function setType($type);
}
