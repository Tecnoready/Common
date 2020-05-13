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
    
    
    static function debugQuery(\Doctrine\ORM\QueryBuilder $qb)
    {
        $query = $qb->getQuery();
        $sql = $query->getSql();
        $paramsList = self::getListParamsByDql($query->getDql());
        $paramsArr = self::getParamsArray($query->getParameters());
        $fullSql = '';
        for ($i = 0; $i < strlen($sql); $i++) {
            if ($sql[$i] == '?') {
                $nameParam = array_shift($paramsList);

                if (is_string($paramsArr[$nameParam])) {
                    $fullSql .= '"' . addslashes($paramsArr[$nameParam]) . '"';
                } elseif (is_array($paramsArr[$nameParam])) {
                    $sqlArr = '';
                    foreach ($paramsArr[$nameParam] as $var) {
                        if (!empty($sqlArr))
                            $sqlArr .= ',';

                        if (is_string($var)) {
                            $sqlArr .= '"' . addslashes($var) . '"';
                        } else
                            $sqlArr .=  '"' . $var . '"';;
                    }
                    $fullSql .= $sqlArr;
                }elseif (is_object($paramsArr[$nameParam])) {
                    switch (get_class($paramsArr[$nameParam])) {
                        case 'DateTime':
                            $fullSql .= "'" . $paramsArr[$nameParam]->format('Y-m-d H:i:s') . "'";
                            break;
                        default:
                            $fullSql .= "'" . $paramsArr[$nameParam]->getId() . "'";
                    }
                } else
                    $fullSql .= $paramsArr[$nameParam];
            } else {
                $fullSql .= $sql[$i];
            }
        }
        echo($fullSql);
        return $fullSql;
    }
    
    /**
     * Get query params list
     * 
     * @author Yosef Kaminskyi <yosefk@spotoption.com>
     * @param  Doctrine\ORM\Query\Parameter $paramObj
     * @return int
     */
    public static function getParamsArray($paramObj)
    {
        $parameters = array();
        foreach ($paramObj as $val) {
            /* @var $val Doctrine\ORM\Query\Parameter */
            $parameters[$val->getName()] = $val->getValue();
        }

        return $parameters;
    }

    public static function getListParamsByDql($dql)
    {
        $parsedDql = preg_split("/:/", $dql);
        $length = count($parsedDql);
        $parmeters = array();
        for ($i = 1; $i < $length; $i++) {
            if (ctype_alpha($parsedDql[$i][0])) {
                $param = (preg_split("/[' ' )]/", $parsedDql[$i]));
                $parmeters[] = $param[0];
            }
        }

        return $parmeters;
    }
    
    static function debugRawSql($sql,array $params){
        foreach ($params as $key => $val) {
            $sql = str_replace(":".$key, $val,$sql);
        }
        return $sql;
    }
}
