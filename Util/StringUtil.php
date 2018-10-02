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
}
