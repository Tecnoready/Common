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
    
    public function compile($filename,$string,array $parameters): void;
    
    public function getExtension();
    
    public function getDefaultParameters();

    public function checkAvailability() : bool;
    
    public function getInstallSolutions(): string;
    
    /**
     * Descripcion del motor
     * @return string
     */
    public function getDescription(): string;
    
    /**
     * id del motor (debe ser unico)
     * @return string
     */
    public function getId(): string;
    
    /**
     * Ejemplo
     * @return string
     */
    public function getExample(): string;
    
    /**
     * Lenguaje de progamacion que usa la plantilla
     * @return string
     */
    public function getLanguage(): string;
}
