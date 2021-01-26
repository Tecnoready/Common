<?php

namespace Tecnoready\Common\Tests\Service\Template;

use PHPUnit\Framework\TestCase;
use Tecnoready\Common\Tests\BaseWebTestCase;
use Tecnoready\Common\Model\Template\ModelTemplate;
use Tecnoready\Common\Service\Template\Engine\PhpSpreadsheetXLSXEngine;
use Tecnoready\Common\Service\Template\Engine\TXTNativeEngine;
use Tecnoready\Common\Service\Template\Engine\WkhtmlToPDFEngine;
use Tecnoready\Common\Service\Template\Engine\TXTEchoEngine;
use Tecnoready\Common\Service\Template\Engine\TCPDFEngine;

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
        
        $adapterPDF = new TCPDFEngine($twig);
        $templateService->addEngine($adapterPDF);
        
        $adapterPDF2 = new WkhtmlToPDFEngine($twig);
        $templateService->addEngine($adapterPDF2);
        
        $adapterXLSX = new PhpSpreadsheetXLSXEngine();
        $templateService->addEngine($adapterXLSX);
        
        $adapterTXT = new TXTNativeEngine();
        $templateService->addEngine($adapterTXT);
        
        $adapterTXT2 = new TXTEchoEngine();
        $templateService->addEngine($adapterTXT2);
        
        return $templateService;
    }
    
    public function testTCPDFEngine()
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
            ->setTypeTemplate(TCPDFEngine::NAME)
            ->setId("demo")
            ->setContent($content)
            ->setVariables([])
            ->setParameters([])
            ;
        $templateService = $this->getService();
        $r = $templateService->render($template,$variables);
        $this->assertEquals("Hola Carlos.", $r);
        
        $filename = $this->getTempPath("TemplateService")."/"."archivo.pdf";
        $templateService->compileTemplate($template, $filename, $variables, $parameters);
        $this->assertFileExists($filename);
        @unlink($filename);
    }
    
    public function testPhpSpreadsheetXLSXEngine()
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
            ->setTypeTemplate(PhpSpreadsheetXLSXEngine::NAME)
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
        $templateService->compileTemplate($template, $filename, $variables, $parameters);
        $this->assertFileExists($filename);
        @unlink($filename);
    }
    
    public function testTXTEngine()
    {
        $variables = [
            "name" => "Carlos",
            "lastname" => "Mendoza",
        ];
        $parameters = [];
        
        $content = <<<EOF
<?php
fwrite(\$fh,"Hola \$name.\n");
fwrite(\$fh,"Apellido \$lastname.");
EOF;
        $template = new ModelTemplate();
        $template
            ->setTypeTemplate(TXTNativeEngine::NAME)
            ->setId("demo")
            ->setContent($content)
            ->setVariables([])
            ->setParameters([])
            ;
        $templateService = $this->getService();
        $r = $templateService->render($template,$variables);
        $r2 = <<<EOF
Hola Carlos.
Apellido Mendoza.
EOF;
        $this->assertEquals($r2, $r);
        
        $filename = $this->getTempPath("TemplateService")."/"."archivo.txt";
        $templateService->compileTemplate($template, $filename, $variables, $parameters);
        $this->assertFileExists($filename);
        @unlink($filename);
    }
    
    public function testTXTEchoEngine()
    {
        $variables = [
            "name" => "Carlos",
            "lastname" => "Mendoza",
        ];
        $parameters = [];
        
        $content = <<<EOF
echo "Hola \$name.\n";
echo "Apellido \$lastname.";
EOF;
        $template = new ModelTemplate();
        $template
            ->setTypeTemplate(TXTEchoEngine::NAME)
            ->setId("demo")
            ->setContent($content)
            ->setVariables([])
            ->setParameters([])
            ;
        $templateService = $this->getService();
        $r = $templateService->render($template,$variables);
        $r2 = <<<EOF
Hola Carlos.
Apellido Mendoza.
EOF;
        $this->assertEquals($r2, $r);
        
        $filename = $this->getTempPath("TemplateService")."/"."archivo.txt";
        $templateService->compileTemplate($template, $filename, $variables, $parameters);
        $this->assertFileExists($filename);
        @unlink($filename);
    }
    

}
