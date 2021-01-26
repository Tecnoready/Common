<?php

namespace Tecnoready\Common\Service\Template\Adapter;

use Tecnoready\Common\Service\Template\AdapterInterface;
use Tecnoready\Common\Model\Template\TemplateInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Environment;

/**
 * Adaptador para exportar a txt
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @deprecated Usar Tecnoready\Common\Service\Template\Engine\TXTEngine
 */
class OldTXTAdapter
{
    /**
     * @var Twig_Environment 
     */
    private $twig;

    /**
     * Opciones de configuracion
     * @var array
     */
    private $options;

    private $fname;
    private $variables;
    private $fileName;

    public function __construct(Twig_Environment $twig,array $options = [])
    {
        $this->twig = $twig;
        $resolver = new OptionsResolver();
        $tmpDir = sys_get_temp_dir();
        $resolver->setDefaults([
            "tmpDir" => $tmpDir,//Carpeta temporal
        ]);
        $this->options = $resolver->resolve($options);
        
    }

    public function render(TemplateInterface $template, array $variables)
    {
        $this->variables = $variables;
        
        $this->fname = tempnam(null, "content".".php");
        $fh = fopen($this->fname, "a");
            fwrite($fh,$template->getContent());
        fclose($fh);

        $this->fileName = $template->getName().".txt";

        return $this->fname;
    }
    
    public function compile($filename, $string, array $parameters)
    {
        $execute = function($variables,$fname,$filename){
            ob_start();
            $fh = fopen($filename, "w");
            extract($variables);
            include $fname;
            fclose($fh);
            ob_end_clean();
        };
        $execute($this->variables,$this->fname,$filename);
        unset($execute);
        
        return $this->fileName;
    }

    public function getDefaultParameters()
    {
        return [];
    }

    public function getExtension()
    {
        return TemplateInterface::TYPE_TXT;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }
}
