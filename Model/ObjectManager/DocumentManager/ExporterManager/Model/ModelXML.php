<?php

namespace Tecnoready\Common\Model\ObjectManager\DocumentManager\ExporterManager\Model;

use DOMDocument;
use SimpleXMLElement;

/**
 * Modelo de xml
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ModelXML extends ModelTXT
{
   public function getFormat() {
        return "xml";
   }
    
    /**
     * Formatea el xml a formato pretty manteniendo los saltos de linea
     * @param SimpleXMLElement $simpleXMLElement
     * @return type
     */
    function formatXml(SimpleXMLElement $simpleXMLElement)
    {
        $xmlDocument = new DOMDocument('1.0');
        $xmlDocument->preserveWhiteSpace = false;
        $xmlDocument->formatOutput = true;
        $xmlDocument->loadXML($simpleXMLElement->asXML());

        return $xmlDocument->saveXML();
    }
}
