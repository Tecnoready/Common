<?php

namespace Tecnoready\Common\Model\ObjectManager\HistoryManager\ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Tecnoready\Common\Model\ObjectManager\HistoryManager\HistoryInterface;

/**
 * Modelo de historial
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @ORM\MappedSuperclass()
 */
abstract class ModelHistory implements HistoryInterface
{
    use \Tecnoready\Common\Model\ObjectManager\Base\TraitBaseORM;
    
    /**
     * Nombre del evento
     * @var string 
     * @ORM\Column(name="event_name",type="string")
     */
    protected $eventName;
    
    /**
     * Tipo de historial
     * @var string self::TYPE_*
     * @ORM\Column(name="type",type="string",length=20)
     */
    protected $type = self::TYPE_DEFAULT;
    
    public function getEventName()
    {
        return $this->eventName;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}
