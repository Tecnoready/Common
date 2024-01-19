<?php

namespace Tecnoready\Common\Service\ShowBuilder\DataTransformer;

use Tecnoready\Common\Service\ShowBuilder\DataTransformerInterface;
use DateTime;

/**
 * Transforma una fecha en un string
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DateTimeTransformer implements DataTransformerInterface
{
    const DATE_FORMAT = "d/m/Y h:i a";
    
    public function transform($value,array $options = [])
    {
        $value = $value->format(self::DATE_FORMAT);
        return $value;
    }

    public function getName()
    {
        return "datetime";
    }

    public function supports($value,array $options = [])
    {
        return $value instanceof DateTime;
    }
}
