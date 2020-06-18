<?php

namespace Tecnoready\Common\Util;

/**
 * Normalizador de cadenas de texto
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class StringUtil {
    public static function clearAccents($str,array $allowed = []) {
        $convert = Array(
            'ä' => 'a',
            'Ä' => 'A',
            'á' => 'a',
            'Á' => 'A',
            'à' => 'a',
            'À' => 'A',
            'ã' => 'a',
            'Ã' => 'A',
            'â' => 'a',
            'Â' => 'A',
            'č' => 'c',
            'Č' => 'C',
            'ć' => 'c',
            'Ć' => 'C',
            'ď' => 'd',
            'Ď' => 'D',
            'ě' => 'e',
            'Ě' => 'E',
            'é' => 'e',
            'É' => 'E',
            'ë' => 'e',
            'í' => 'i',
            'Í' => 'I',
            'ó' => 'o',
            'Ó' => 'O',
            'ú' => 'u',
            'ü' => 'u',
            'Ú' => 'U',
            'ñ' => 'n',
            'Ñ' => 'N',
            'è' => 'e',
            'ö' => 'o',
            'ù' => 'u',
            'Ç' => 'C',
            'ô' => 'o',
            'Ü' => 'U',
            'ì' => 'i',
        );
        foreach ($allowed as $v) {
            if(isset($convert[$v])){
                unset($convert[$v]);
            }
        }

        $str = strtr($str, $convert);
        return $str;
    }
    
    /**
     * Valida que solo tenga caracteres permitidos
     * @param type $str
     * @param type $allowed Por defecto permite letras de la a-z A-Z 0-9 @
     * @return boolean
     */
    public static function validCharacters($str, $allowed = '/[^A-Za-z0-9@]/') {
        $valid = false;
        if (!preg_match($allowed, $str)) { // '/[^a-z\d]/i' should also work.
            $valid = true;
        }
        return $valid;
    }
    
    /**
     * Limpia un texto de caracteres especiales y elimina los espacios
     * @param type $string
     * @return type
     */
    public static function clean($string, $allowed = '/[^A-Za-z0-9\-]/', $spacer = "") {
        $string = str_replace(' ', $spacer, $string); // Replaces all spaces with hyphens.
        return preg_replace($allowed, $spacer, $string); // Removes special chars.
    }
    
    /**
     * Trunca un string
     * @param string $label
     * @param type $truncate
     * @return string
     */
    public static function truncate($label, $truncate = 30, $onlylast = false)
    {
        if (empty($label)) {
            return "...";
        }
        if ((strlen($label) > $truncate) && $onlylast == false) {
            $label = mb_substr($label, 0, $truncate, 'UTF-8') . '...';
        } else if ((strlen($label) > $truncate) && $onlylast == true) {
            $label = substr($label, $truncate * -1);
        }
        return $label;
    }
    
    public static function unParseUrl(array $parsed)
    {
        $pass = $parsed['pass'] ?? null;
            $user = $parsed['user'] ?? null;
            $userinfo = $pass !== null ? "$user:$pass" : $user;
            $port = $parsed['port'] ?? 0;
            $scheme = $parsed['scheme'] ?? "";
            $query = $parsed['query'] ?? "";
            $fragment = $parsed['fragment'] ?? "";
            $authority = (
                    ($userinfo !== null ? "$userinfo@" : "") .
                    ($parsed['host'] ?? "") .
                    ($port ? ":$port" : "")
                    );
            return (
                    (\strlen($scheme) > 0 ? "$scheme:" : "") .
                    (\strlen($authority) > 0 ? "//$authority" : "") .
                    ($parsed['path'] ?? "") .
                    (\strlen($query) > 0 ? "?$query" : "") .
                    (\strlen($fragment) > 0 ? "#$fragment" : "")
                    );
    }
    
    /**
     * 
     * @param type $url
     * @param array $toRemove
     * @return type
     */
    public function removeQueryStringURL($url,array $toRemove)
    {
        $urlParsedReferer = parse_url($url);
            $queryOut = [];
            $result = $url;
            if (isset($urlParsedReferer["query"])) {
                parse_str($urlParsedReferer["query"], $queryOut);
                foreach ($toRemove as $key) {
                    if (isset($queryOut[$key])) {
                        unset($queryOut[$key]);
                    }
                }
                $urlParsedReferer["query"] = http_build_query($queryOut);
                $result = self::unParseUrl($urlParsedReferer);
            }
            return $result;
    }
}
