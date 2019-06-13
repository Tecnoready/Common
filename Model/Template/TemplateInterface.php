<?php

namespace Tecnoready\Common\Model\Template;

/**
 * Interfaz para plantillas
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface TemplateInterface
{
    /**
     * Se usa wkhtmltopdf para generar el PDF a partir de HTML
     */
    const TYPE_PDF = "PDF";
//    const TYPE_TXT = "TXT";
//    const TYPE_WORD = "WORD";
    /**
     * Tipo: Excel
     */
    const TYPE_XLSX = "XLSX";
    
    public function getTypeTemplate();
    
    public function getContent();

    public function setTypeTemplate($typeTemplate);

    public function setContent($content);

    public function getVariables();

    public function getParameters();

    public function setId($id);
}
