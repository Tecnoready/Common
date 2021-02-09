<?php

namespace Tecnoready\Common\Service\Template\Engine;

/**
 * Base de motor
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class BaseEngine implements EngineInterface
{

    /**
     * Nombre de archivo generado durante la compilacion (si es null se deja el generado temporal o el pasado en el parametro)
     * @var string
     */
    protected $fileName = null;

    /**
     * Soluciones de instalacion
     * @var array
     */
    protected $installSolutions = [];

    protected function addSolution($solution)
    {
        $this->installSolutions[] = $solution;
        return $this;
    }

    public function getInstallSolutions(): string
    {
        return implode(",", $this->installSolutions);
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    protected function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

}
