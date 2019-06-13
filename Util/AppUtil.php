<?php

namespace Tecnoready\Common\Util;

/**
 * Utils
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class AppUtil 
{   
    /**
     * isCommandLineInterface
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return boolean
     */
    public static function isCommandLineInterface()
    {
        return (php_sapi_name() === 'cli');
    }
}
