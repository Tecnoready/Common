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
use DateTime;

/**
 * Transforma una fecha para guardarla en cache
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DateTimeTransformer implements DataTransformerInterface 
{
    const TYPE_DATETIME = "date_time";
    public function reverseTransform($value, ConfigurationInterface $configuration) {
        if($configuration->getType() === self::TYPE_DATETIME){
            $value = unserialize(base64_decode($value));
        }
        return $value;
    }

    public function transform($value, ConfigurationInterface $configuration) {
        if($configuration->getType() === "object" && $value instanceof DateTime){
            $value = base64_encode(serialize($value));
            $configuration->setType(self::TYPE_DATETIME);
        }
        return $value;
    }
}
