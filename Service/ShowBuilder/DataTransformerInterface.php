<?php

namespace Tecnoready\Common\Service\ShowBuilder;

/**
 * Transformador de data
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface DataTransformerInterface
{
    /**
     * Transforma el valor
     * @param type $value
     */
    public function transform($value,array $options = []);
    
    /**
     * Nombre del transformador
     */
    public function getName();
    
    /**
     * Soporta el dato pasado
     * @param type $value
     */
    public function supports($value,array $options = []);
}
