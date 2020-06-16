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
        return sprintf("%s___%s",strtoupper($nameWrapper),md5(strtoupper($key)));
    }
    
    static $libs = [
        "optionsResolver" => "checkOptionsResolver",
        "guzzleHttp" => "checkGuzzleHttp",
        "propertyAccess" => "checkPropertyAccess",
        "phpoffice/phpspreadsheet" => "checkPhpspreadsheet",
        "tecnickcom/tcpdf" => "checkTCPDF",
        "symfony/event-dispatcher" => "checkEventDispatcher",
        "symfony/templating" => "checkTemplating",
    ];
    
    public static function checkLib($name) {
        if(!isset(self::$libs[$name])){
            throw new \InvalidArgumentException(sprintf("The libname '%s' is not valid."));
        }
        $method = self::$libs[$name];
        self::{$method}();
    }
    
    private static function checkOptionsResolver() {
        if(!class_exists("Symfony\Component\OptionsResolver\OptionsResolver")){
            throw new \Exception(sprintf("The package '%s' is required, please install https://packagist.org/packages/symfony/options-resolver",'"symfony/options-resolver": "~3.4"'));
        }
    }
    private static function checkGuzzleHttp() {
        if (!class_exists('\GuzzleHttp\Client')) {
            throw new \Exception(sprintf("The package '%s' is required, please install https://packagist.org/packages/guzzlehttp/guzzle",'"guzzlehttp/guzzle": "~6.3"'));
        }
    }
    private static function checkPropertyAccess() {
        if (!class_exists('\Symfony\Component\PropertyAccess\PropertyAccess')) {
            throw new \Exception(sprintf("The package '%s' is required, please install.",'"symfony/property-access": "~3.4"'));
        }
    }
    private static function checkPhpspreadsheet() {
        if (!class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
            throw new \Exception(sprintf("The package '%s' is required, please install.",'"phpoffice/phpspreadsheet": "^1.6"'));
        }
    }
    private static function checkTCPDF() {
        if (!class_exists('\TCPDF')) {
            throw new \Exception(sprintf("The package '%s' is required, please install.",'"tecnickcom/tcpdf": "^6.2"'));
        }
    }
    private static function checkEventDispatcher() {
        if (!class_exists('\Symfony\Component\EventDispatcher\Event')) {
            throw new \Exception(sprintf("The package '%s' is required, please install.",'"symfony/event-dispatcher": "^4.0"'));
        }
    }
    private static function checkTemplating() {
        if (!interface_exists('\Symfony\Component\Templating\EngineInterface')) {
            throw new \Exception(sprintf("The package '%s' is required, please install.",'"symfony/templating": "^4.0"'));
        }
    }
    
}
