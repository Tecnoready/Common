<?php

namespace Tecnoready\Common\Model\Template;

/**
 * Modelo de plantilla
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ModelTemplate implements TemplateInterface
{
    private $content;
    private $parameters;
    private $variables;
    private $typeTemplate;
    private $id;
    
    public function getContent()
    {
        return $this->content;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getVariables()
    {
        return $this->variables;
    }

    public function getTypeTemplate()
    {
        return $this->typeTemplate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function setVariables($variables)
    {
        $this->variables = $variables;
        return $this;
    }

    public function setTypeTemplate($typeTemplate)
    {
        $this->typeTemplate = $typeTemplate;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}
