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
abstract class ModelHistory extends HistoryInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="string", length=36)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;
    
    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at",type="datetime")
     */
    protected $createdAt;

    /**
     * @var string $createdFromIp
     *
     * @Gedmo\IpTraceable(on="create")
     * @ORM\Column(type="string", name="created_from_ip",length=45, nullable=true)
     */
    protected $createdFromIp;
    
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
    
    /**
     * Descripcion del evento
     * @var string 
     * @ORM\Column(name="description",type="text",nullable=true)
     */
    protected $description;
    
    /**
     * Navegador usado
     * @var string
     * @ORM\Column(name="user_agent",type="text") 
     */
    protected $userAgent;
    
    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getCreatedFromIp()
    {
        return $this->createdFromIp;
    }

    public function getEventName()
    {
        return $this->eventName;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setCreatedFromIp($createdFromIp)
    {
        $this->createdFromIp = $createdFromIp;
        return $this;
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

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

}
