<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\ConfigurationService\DataTransformer;

use Tecnoready\Common\Service\ConfigurationService\DataTransformerInterface;
use Tecnoready\Common\Model\Configuration\BaseEntity\ConfigurationInterface;

/**
 * Transforma el array a base de datos e inversa
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ArrayDataTransformer implements DataTransformerInterface
{
    public function reverseTransform($value, ConfigurationInterface $configuration) {
        if($configuration->getType() === "array"){
            $value = unserialize(base64_decode($value));
        }
        return $value;
    }

    public function transform($value, ConfigurationInterface $configuration) {
        if($configuration->getType() === "array"){
            $value = base64_encode(serialize($value));
        }
        return $value;
    }
}
