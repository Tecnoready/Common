<?php

namespace Tecnoready\Common\Model\ObjectManager\HistoryManager;

use DateTime;

/**
 * Intefaz de historial
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface HistoryInterface
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
    
    public function setDescription($description);
    
    public function getDescription();
    
    public function setUser($user);
    
    public function getUser();
    
    public function getType();
    
    public function setType($type);
    
    public function setCreatedFromIp($createdFromIp);
    
    public function getCreatedFromIp();
    
    public function setCreatedAt(DateTime $createdAt);
    
    public function getCreatedAt();
    
    public function getId();
    
    public function setId();
    
    public function getUserAgent();

    public function setUserAgent($userAgent);
}
