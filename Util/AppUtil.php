<?php

namespace Tecnoready\Common\Util;

/**
 * Utils
 */
class AppUtil 
{   
    /**
     * isCommandLineInterface
     *  
     * @return boolean
     */
    public static function isCommandLineInterface()
    {
        return (php_sapi_name() === 'cli');
    }
    
    /**
     * Retorna todos los roles disponibles para el usuario
     * @staticvar type $roles
     * @param array $rolesHierarchy
     * @return type
     */
    public static  function getRoles(array $rolesHierarchy,array $unset = ["ROLE_APP"])
    {
        static $roles = null;
        if(is_array($roles)){
            return $roles;
        }

        $roles = array();
        foreach ($rolesHierarchy as $key => $value) {
            $roles[$key] = $key;
        }
        array_walk_recursive($rolesHierarchy, function($val,$key) use (&$roles) {
            $roles[$val] = $val;
        });
        foreach ($unset as $val) {
            unset($roles[$val]);
        }
        return $roles = array_unique($roles);
    }
}
