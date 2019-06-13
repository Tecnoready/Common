<?php

namespace Tecnoready\Common\Service\Template\Adapter;

use Tecnoready\Common\Service\Template\AdapterInterface;
use Tecnoready\Common\Model\Template\TemplateInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use RuntimeException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Adaptador para exportar a excel con la libreria "phpoffice/phpspreadsheet": "^1.6"
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class XLSXAdapter implements AdapterInterface
{
    public function __construct()
    {
        \Tecnoready\Common\Util\ConfigurationUtil::checkLib("phpoffice/phpspreadsheet");
    }
    
    public function render(TemplateInterface $template, array $variables)
    {
        $spreadsheet = null;
        extract($variables);//Define como variables locales
        $content = "use PhpOffice\PhpSpreadsheet\Spreadsheet;";
        $content .= $template->getContent();
        $content .= "return \$spreadsheet;";
        eval($content);
        if($spreadsheet === null){
            throw new RuntimeException("La variable \$spreadsheet no puede ser null. Debe setearla en la plantilla.");
        }
        return $spreadsheet;
    }
    
    public function compile($filename, $spreadsheet, array $parameters)
    {
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        
        return true;
    }

    public function getDefaultParameters()
    {
        return [];
    }

    public function getExtension()
    {
        return TemplateInterface::TYPE_XLSX;
    }
}
