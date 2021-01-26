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

    /**
     * @return \Tecnocreaciones\Bundle\ToolsBundle\Model\ORM\Template\ModelVariable Description
     */
    public function getVariables();

    /**
     * @return \Tecnocreaciones\Bundle\ToolsBundle\Model\ORM\Template\ModelParameter Description
     */
    public function getParameters();

    public function setId($id);

    public function getName();
    
    public function setName($name);
    
}
