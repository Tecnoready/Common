<?php


namespace Tecnoready\Common\Service\Template\Engine;

/**
 * Base de motor
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class BaseEngine implements EngineInterface
{
    protected $installSolutions = [];
    
    protected function addSolution($solution)
    {
        $this->installSolutions[] = $solution;
        return $this;
    }
    
    public function getInstallSolutions(): string{
        return implode(",",$this->installSolutions);
    }
}
