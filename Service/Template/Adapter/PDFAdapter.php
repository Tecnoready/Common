<?php

namespace Tecnoready\Common\Service\Template\Adapter;

use Tecnoready\Common\Service\Template\AdapterInterface;
use Tecnoready\Common\Model\Template\TemplateInterface;
use Twig_Environment;
use Symfony\Component\OptionsResolver\OptionsResolver;
use mikehaertl\wkhtmlto\Pdf;
use RuntimeException;


/**
 * Adaptador para generar pdf de una plantilla
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class PDFAdapter implements AdapterInterface
{
    private $twig;
    
    /**
     * Opciones de configuracion
     * @var array
     */
    private $options;
    
    public function __construct(Twig_Environment $twig,array $options = [])
    {
        $this->twig = $twig;
        $resolver = new OptionsResolver();
        $tmpDir = sys_get_temp_dir();
        $resolver->setDefaults([
            "tmpDir" => $tmpDir,//Carpeta temporal
            "binary" => "wkhtmltopdf",//Binario
        ]);
        $this->options = $resolver->resolve($options);
        
    }
    
    public function render(TemplateInterface $template, array $variables)
    {
        $templateTwig =$this->twig->createTemplate($template->getContent());
        $result = $templateTwig->render($variables);
        
        return $result;
    }

    public function compile($filename, $string, array $parameters)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            "page-width","page-height",
        ]);
        
        //Parametros para generar el pdf
        $resolver->setDefaults($this->getDefaultParameters());
        $resolver->setDefault("force-download", false);
        $resolver->setAllowedTypes("page-width","float");
        $resolver->setAllowedTypes("page-width","float");
        $resolver->setAllowedTypes("margin-left","float");
        $resolver->setAllowedTypes("margin-top","float");
        $resolver->setAllowedTypes("margin-right","float");
        $resolver->setAllowedTypes("margin-bottom","float");
        
        $parameters = $resolver->resolve($parameters);
        $forceDownload = (bool)$parameters["force-download"];
        unset($parameters["force-download"]);
        $pdf = new Pdf();
        $pdf->tmpDir = $this->options["tmpDir"];
        $pdf->binary = $this->options["binary"];
        $pdf->setOptions($parameters);
        
        $pdf->addPage('<html>'.$string.'</html>');
        $success = !$pdf->saveAs($filename);
        if ($forceDownload == false && !$pdf->saveAs($filename)) {
            $error = $pdf->getError();
            //sh: wkhtmltopdf: command not found (Error que da cuando no encuentra el binario de wkhtmltopdf)
            throw new RuntimeException(sprintf("Ocurrio un error generando el pdf: '%s'",$error));
        }
        if($forceDownload === true){
//            $pdf->send();
            $pdf->send(basename($filename));
        }
        return true;
    }

    public function getExtension()
    {
        return TemplateInterface::TYPE_PDF;
    }

    public function getDefaultParameters()
    {
        return [
            "margin-left" => 0.0,//Margen izquierdo
            "margin-top" => 0.0,//Margen superior
            "margin-right" => 0.0,//Margen derecho
            "margin-bottom" => 0.0,//Margen inferior
            'encoding' => 'UTF-8',  // option with argument
            'no-outline',           // option without argument
            "page-width" => 100.0,
            "page-height" => 100.0,
            'disable-smart-shrinking',
            'lowquality',
            'commandOptions' => [
                    'locale' => 'es_ES.utf-8',
                    'procEnv' => ['LANG' => 'es_ES.utf-8'],
            ]
        ];
    }

}
