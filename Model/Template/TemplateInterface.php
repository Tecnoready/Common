<?php

namespace Tecnoready\Common\Model\Template;

/**
 * Interfaz para plantillas
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface TemplateInterface
{
    public function getTypeTemplate();
    
    public function getContent();

    public function setTypeTemplate($typeTemplate);

    public function setContent($content);

    public function getVariables();

    public function getParameters();

    public function setId($id);

    public function getName();
    
    public function setName($name);
    
}
