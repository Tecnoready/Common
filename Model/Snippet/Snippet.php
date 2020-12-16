<?php

namespace Tecnoready\Common\Model\Snippet;

use Tecnoready\Common\Model\Snippet\SnippetInterface;

/**
 * Modelo de engine
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class Snippet implements SnippetInterface
{
    protected $ref;
    
    protected $engine;
    
    protected $code;
    
    public function getRef()
    {
        return $this->ref;
    }

    public function getEngine()
    {
        return $this->engine;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setRef($ref)
    {
        $this->ref = $ref;
        return $this;
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

}
