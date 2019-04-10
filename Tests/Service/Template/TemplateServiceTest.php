<?php

namespace Tecnoready\Common\Tests\Service\Template;

use PHPUnit\Framework\TestCase;
use Tecnoready\Common\Tests\BaseWebTestCase;
use Tecnoready\Common\Model\Template\ModelTemplate;

/**
 * Test del serivcio de compilador de plantillas
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TemplateServiceTest extends BaseWebTestCase
{
    /**
     * @return \Tecnoready\Common\Service\Template\TemplateService
     */
    private function getService()
    {
        $templateService = new \Tecnoready\Common\Service\Template\TemplateService([
            "debug" => true,
            "env" => "test",
        ]);
        $twig = $this->client->getContainer()->get("twig");
        $adapterPDF = new \Tecnoready\Common\Service\Template\Adapter\PDFAdapter($twig);
        $templateService->addAdapter($adapterPDF);
        $adapterXLSX = new \Tecnoready\Common\Service\Template\Adapter\XLSXAdapter();
        $templateService->addAdapter($adapterXLSX);
        return $templateService;
    }
    public function testPDFAdapter()
    {
        $variables = [
            "name" => "Carlos"
        ];
        $parameters = [];
        
        $content = <<<EOF
Hola {{ name }}.
EOF;
        $template = new ModelTemplate();
        $template
            ->setTypeTemplate(ModelTemplate::TYPE_PDF)
            ->setId("demo")
            ->setContent($content)
            ->setVariables([])
            ->setParameters([])
            ;
        $templateService = $this->getService();
        $r = $templateService->render($template,$variables);
        $this->assertEquals("Hola Carlos.", $r);
        
        $filename = $this->getTempPath("TemplateService")."/"."archivo.pdf";
        $templateService->compile($template, $filename, $variables, $parameters);
        $this->assertFileExists($filename);
        @unlink($filename);
    }
    
    public function testXLSXAdapter()
    {
        $variables = [
            "name" => "Carlos"
        ];
        $parameters = [];
        
        $content = <<<EOF
        \$spreadsheet = new Spreadsheet();
        \$sheet = \$spreadsheet->getActiveSheet();
        \$sheet->setCellValue('A1', 'Hello World '.\$name.'!');            
EOF;
        $template = new ModelTemplate();
        $template
            ->setTypeTemplate(ModelTemplate::TYPE_XLSX)
            ->setId("demo")
            ->setContent($content)
            ->setVariables([])
            ->setParameters([])
            ;
        $templateService = $this->getService();
        $r = $templateService->render($template,$variables);
//        var_dump($r);
        $this->assertInstanceOf("PhpOffice\PhpSpreadsheet\Spreadsheet", $r);
        
        $filename = $this->getTempPath("TemplateService")."/"."archivo.xlsx";
        $templateService->compile($template, $filename, $variables, $parameters);
        $this->assertFileExists($filename);
        @unlink($filename);
    }
    

}
