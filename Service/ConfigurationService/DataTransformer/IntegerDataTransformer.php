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
 * Transforma un string a un entero
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class IntegerDataTransformer implements DataTransformerInterface
{
    public function reverseTransform($value, ConfigurationInterface $configuration) {
        if($configuration->getType() === "integer"){
            $value = (int)$value ;
        }
        return $value;
    }

    public function transform($value, ConfigurationInterface $configuration) {
        return $value;
    }
}
