<?php

namespace Tecnoready\Common\Model\Snippet\ORM;

use Tecnoready\Common\Model\Snippet\ParameterInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo de parametro
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class ModelParameter implements ParameterInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * Indice
     * @var string
     * @ORM\Column(type="string",length=50)
     */
    protected $itemKey;
    
    /**
     * Descripcion de la variable
     * @var string
     * @ORM\Column(type="string",length=200)
     */
    protected $description;
    
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    public function getItemKey()
    {
        return $this->itemKey;
    }

    public function setItemKey($itemKey)
    {
        $this->itemKey = $itemKey;
        return $this;
    }
}
