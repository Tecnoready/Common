<?php

namespace Tecnoready\Common\Service\Template;

use Tecnoready\Common\Model\Template\TemplateInterface;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface AdapterInterface
{
    public function render(TemplateInterface $template,array $variables);
    
    public function compile($filename,$element,array $parameters);
    
    public function getExtension();
    
    public function getDefaultParameters();

}
