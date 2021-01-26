<?php

namespace Tecnoready\Common\Service\Template\Engine;

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
class WkhtmlToPDFEngine extends BaseEngine
{
    const NAME = "WKHT_MLTO_PDF";
    
    /**
     * @var Twig_Environment 
     */
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
//            "page-width","page-height",
        ]);
        $resolver->setDefaults([
            "page-width" => 0.0,
            "page-height" => 0.0,
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
        foreach ($parameters as $key => $value) {
            if($value === 0.0){
                unset($parameters[$key]);
            }
        }
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
            "page-height" => 0.0,
            "page-width" => 0.0,
            'encoding' => 'UTF-8',  // option with argument
            'no-outline',           // option without argument
            'disable-smart-shrinking',
            "page-size" => "Letter",
            'lowquality',
            'commandOptions' => [
                    'locale' => 'es_ES.utf-8',
                    'procEnv' => ['LANG' => 'es_ES.utf-8'],
            ]
        ];
    }

    public function checkAvailability(): bool
    {
        $result = true;
        if(!class_exists("mikehaertl\wkhtmlto\Pdf")){
            $this->addSolution(sprintf("The package '%s' is required, please install.",'"mikehaertl/phpwkhtmltopdf": "^2.4.2"'));
        }
        return $result;
    }

    public function getDescription(): string
    {
        return "[TWIG] wkhtmltopdf";
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getExample(): string
    {
        $content = <<<EOF
Html compiler
Hola <b>{{ name }}</b>.
EOF;
        return $content;
    }

}
