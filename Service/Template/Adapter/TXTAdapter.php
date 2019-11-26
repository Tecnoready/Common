<?php

namespace Tecnoready\Common\Service\Template\Adapter;

use Tecnoready\Common\Service\Template\AdapterInterface;
use Tecnoready\Common\Model\Template\TemplateInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use RuntimeException;
use Twig_Environment;

/**
 * Adaptador para exportar a txt
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class TXTAdapter implements AdapterInterface
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

        return $this->fname;
    }
    
    public function compile($filename, $string, array $parameters)
    {
        extract($this->variables);
        $fh = fopen($filename, "w");
            include $this->fname;
        fclose($fh);
        
        return $filename;
    }

    public function getDefaultParameters()
    {
        return [];
    }

    public function getExtension()
    {
        return TemplateInterface::TYPE_TXT;
    }
}
