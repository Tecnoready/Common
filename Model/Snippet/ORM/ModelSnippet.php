<?php

namespace Tecnoready\Common\Model\Snippet\ORM;

use Doctrine\ORM\Mapping as ORM;
use Tecnoready\Common\Model\Snippet\SnippetInterface;

/**
 * Modelo de snippet
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class ModelSnippet implements SnippetInterface
{
    use \Tecnoready\Common\Model\Traits\ORM\ItemReferenceTrait;
    
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * Nombre o descripcion del snippet
     * @var string
     * @ORM\Column(type="string",length=100)
     */
    protected $name;
    
    /**
     * Tipo de codigo (self::TYPE_CODE_*)
     * @var string
     * @ORM\Column(type="string",length=10)
     */
    protected $engine;
    
    /**
     * Codigo a complilar
     * @var string
     * @ORM\Column(type="text")
     */
    protected $code;
    
    public function getEngine()
    {
        return $this->engine;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setEngine($engine)
    {
        $this->engine = $engine;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }

}
