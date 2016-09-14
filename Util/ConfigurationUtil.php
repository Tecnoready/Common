<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Util;

/**
 * Util de la libreria de configuracion
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ConfigurationUtil {
    /**
     * Genera el id para guardar y recuperar la configuracion de la cache
     * @param type $nameWrapper
     * @param type $key
     * @return type
     */
    public static function generateId($nameWrapper,$key)
    {
        return sprintf("%s__%s",$nameWrapper,$key);
    }
    
}
