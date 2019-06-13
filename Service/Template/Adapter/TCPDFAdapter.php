<?php

namespace Tecnoready\Common\Service\Template\Adapter;

use Tecnoready\Common\Service\Template\AdapterInterface;
use Tecnoready\Common\Model\Template\TemplateInterface;
use Twig_Environment;
use TCPDF;
use Tecnoready\Common\Util\ConfigurationUtil;

/**
 * Adaptador de PDF con TCPDF
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TCPDFAdapter implements AdapterInterface
{

    /**
     * @var Twig_Environment 
     */
    private $twig;

    public function __construct(Twig_Environment $twig)
    {
        ConfigurationUtil::checkLib("tecnickcom/tcpdf");
        $this->twig = $twig;
    }

    public function compile($filename, $string, array $parameters)
    {
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Tecnoready Common');
//        $pdf->SetTitle('TCPDF Example 001');
//        $pdf->SetSubject('TCPDF Tutorial');
//        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set default font subsetting mode
        $pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 12, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

// Set some content to print
        $html = $string;

// Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);


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
        return TemplateInterface::TYPE_PDF;
    }

    public function render(TemplateInterface $template, array $variables)
    {
        $templateTwig = $this->twig->createTemplate($template->getContent());
        $result = $templateTwig->render($variables);

        return $result;
    }

}
