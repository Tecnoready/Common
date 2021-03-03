<?php

namespace Tecnoready\Common\Service\Template\Engine;

use Tecnoready\Common\Model\Template\TemplateInterface;
use TCPDF;

/**
 * Adaptador de PDF con TCPDF
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TCPDFNativeEngine extends BaseEngine
{

    const NAME = "TCPDF_NATIVE";

    public function compile($filename, $pdf, array $parameters): void
    {
        if(($pdf instanceof TCPDF) === false){
            throw new RuntimeException(sprintf("La variable \$pdf debe ser una instancia de TCPDF. Pero es '%s'",gettype($pdf)));
        }
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output($filename, 'F');
    }

    public function getDefaultParameters()
    {
        return [];
    }

    public function getExtension()
    {
        return "PDF";
    }

    public function render(TemplateInterface $template, array $variables)
    {
        $pdf = null;
        extract($variables); //Define como variables locales
        $content = "";
        $content .= $template->getContent();
        $content .= "return \$pdf;";
        eval($content);
        if ($pdf === null) {
            throw new RuntimeException("La variable \$pdf no puede ser null. Debe setearla en la plantilla.");
        }
        if(($pdf instanceof TCPDF) === false){
            throw new RuntimeException(sprintf("La variable \$pdf debe ser una instancia de TCPDF. Pero es '%s'",gettype($pdf)));
        }
        return $pdf;
    }

    public function checkAvailability(): bool
    {
        $result = true;
        if (!class_exists('\TCPDF')) {
            $this->addSolution(sprintf("The package '%s' is required, please install.", '"tecnickcom/tcpdf": "^6.2"'));
            $result = false;
        }
        return $result;
    }

    public function getDescription(): string
    {
        return "TCPDF (tecnickcom/tcpdf)";
    }

    public function getId(): string
    {
        return self::NAME;
    }

    public function getExample(): string
    {
        $content = <<<EOF
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		\$image_file = K_PATH_IMAGES.'logo_example.jpg';
		\$this->Image(\$image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		\$this->SetFont('helvetica', 'B', 20);
		// Title
		\$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		\$this->SetY(-15);
		// Set font
		\$this->SetFont('helvetica', 'I', 8);
		// Page number
		\$this->Cell(0, 10, 'Page '.\$this->getAliasNumPage().'/'.\$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
\$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
\$pdf->SetCreator(PDF_CREATOR);
\$pdf->SetAuthor('Nicola Asuni');
\$pdf->SetTitle('TCPDF Example 003');
\$pdf->SetSubject('TCPDF Tutorial');
\$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
\$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
\$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
\$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                
// set font
\$pdf->SetFont('times', 'BI', 12);

// add a page
\$pdf->AddPage();

// set some text to print
\$txt = <<<EOD
TCPDF Example 003

Custom page header and footer are defined by extending the TCPDF class and overriding the Header() and Footer() methods.
EOD;

// print a block of text using Write()
\$pdf->Write(0, \$txt, '', 0, 'C', true, 0, false, false, 0);
EOF;
        return $content;
    }

    public function getLanguage(): string
    {
        return "PHP";
    }

}
