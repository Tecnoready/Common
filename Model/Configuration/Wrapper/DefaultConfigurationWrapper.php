<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Configuration\Wrapper;

/**
 * Configuracion wraper por defecto para guardar valores generales
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DefaultConfigurationWrapper extends ConfigurationWrapper 
{
    public static function getName() {
        return "default";
    }
}
