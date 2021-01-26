<?php

namespace Tecnoready\Common\Service\Template\Engine;

use Tecnoready\Common\Model\Template\TemplateInterface;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface EngineInterface
{
    public function render(TemplateInterface $template,array $variables);
    
    public function compile($filename,$string,array $parameters);
    
    public function getExtension();
    
    public function getDefaultParameters();

    public function checkAvailability() : bool;
    
    public function getInstallSolutions(): string;
    
    public function getDescription(): string;
    
    public function getName(): string;
    
    public function getExample(): string;
}
