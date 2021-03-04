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
use Tecnoready\Common\Service\Template\Engine\TCPDFNativeEngine;
use TCPDF;

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
        
        $adapterPDF3 = new TCPDFNativeEngine($twig);
        $templateService->addEngine($adapterPDF3);
        
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
    
    public function testTCPDFNativeEngine()
    {
        $variables = [
            "name" => "Carlos"
        ];
        $parameters = [];
        
        $content = <<<EOF
// create new PDF document
\$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
   
\$pdf->AddPage();
                
// Set some content to print
\$html = <<<EOD
<h1>Welcome {\$name} to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
<i>This is the first example of TCPDF library.</i>
<p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
<p>Please check the source code documentation and other examples for further information.</p>
<p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
EOD;

// Print text using writeHTMLCell()
\$pdf->writeHTMLCell(0, 0, '', '', \$html, 0, 1, 0, true, '', true);
EOF;
        $template = new ModelTemplate();
        $template
            ->setTypeTemplate(TCPDFNativeEngine::NAME)
            ->setId("demo")
            ->setContent($content)
            ->setVariables([])
            ->setParameters([])
            ;
        $templateService = $this->getService();
        $r = $templateService->render($template,$variables);
        $this->assertInstanceOf(TCPDF::class, $r);
        
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
