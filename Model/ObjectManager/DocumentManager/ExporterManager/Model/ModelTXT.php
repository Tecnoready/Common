<?php

namespace Tecnoready\Common\Model\ObjectManager\DocumentManager\ExporterManager\Model;

use Tecnoready\Common\Model\ObjectManager\DocumentManager\ExporterManager\ModelDocumentExporter;

/**
 * Modelo de txt
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ModelTXT extends ModelDocumentExporter
{
    public function getFormat() {
        return "txt";
    }

    public function write(array $parameters = []) {
        $fname = tempnam(null, $this->getName().".".$this->getFormat());
        extract($parameters);
        $fh = fopen($fname, "a");
            include $this->getFilePathContent();
        fclose($fh);
        $pathFileOut = $this->getDocumentPath($parameters);
        rename($fname, $pathFileOut);
        
        return $pathFileOut;
    }
}
