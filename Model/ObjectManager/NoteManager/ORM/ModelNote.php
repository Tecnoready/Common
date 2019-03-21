<?php

namespace Tecnoready\Common\Model\ObjectManager\NoteManager\ORM;

use Tecnoready\Common\Model\ObjectManager\NoteManager\NoteInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo de una nota
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @ORM\MappedSuperclass()
 */
abstract class ModelNote implements NoteInterface
{
    use \Tecnoready\Common\Model\ObjectManager\Base\TraitBaseORM;
    
    /**
     * Tipo de historial
     * @var string self::TYPE_*
     * @ORM\Column(name="type",type="string",length=20)
     */
    protected $type;
    
    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}
