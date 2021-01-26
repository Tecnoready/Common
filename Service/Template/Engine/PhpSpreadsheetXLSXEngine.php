<?php

namespace Tecnoready\Common\Service\Template\Engine;

use Tecnoready\Common\Model\Template\TemplateInterface;
use RuntimeException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Adaptador para exportar a excel con la libreria "phpoffice/phpspreadsheet": "^1.6"
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class PhpSpreadsheetXLSXEngine extends BaseEngine
{

    const NAME = "PHP_SPREADSHEET";

    public function render(TemplateInterface $template, array $variables)
    {
        $spreadsheet = null;
        extract($variables); //Define como variables locales
        $content = "use PhpOffice\PhpSpreadsheet\Spreadsheet;";
        $content .= $template->getContent();
        $content .= "return \$spreadsheet;";
        eval($content);
        if ($spreadsheet === null) {
            throw new RuntimeException("La variable \$spreadsheet no puede ser null. Debe setearla en la plantilla.");
        }
        return $spreadsheet;
    }

    public function compile($filename, $spreadsheet, array $parameters):void
    {
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
    }

    public function getDefaultParameters()
    {
        return [];
    }

    public function getExtension()
    {
        return "XLSX";
    }

    public function checkAvailability(): bool
    {
        $result = true;
        if (!class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
            $this->addSolution(sprintf("The package '%s' is required, please install.", '"phpoffice/phpspreadsheet": "^1.6"'));
            $result = false;
        }
        return $result;
    }

    public function getDescription(): string
    {
        return "Excel (phpoffice/phpspreadsheet)";
    }

    public function getId(): string
    {
        return self::NAME;
    }

    public function getExample(): string
    {
        $content = <<<EOF
        \$spreadsheet = new Spreadsheet();
        \$sheet = \$spreadsheet->getActiveSheet();
        \$sheet->setCellValue('A1', 'Hello World '.\$name.'!');            
EOF;
        return $content;
    }

    public function getLanguage(): string
    {
        return "PHP";
    }

}
