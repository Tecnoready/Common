<?php

namespace Tecnoready\Common\Service\Template;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Tecnoready\Common\Model\Template\TemplateInterface;
use RuntimeException;

/**
 * Servicio que renderiza y compila plantillas para generad documentos (pdf)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TemplateService
{
    private $adapters;
    
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
        ]);
        $resolver->setRequired([
            "env", 
        ]
        );
        $this->adapters = [];
    }
    /**
     * Añade un adaptador
     * @param \Tecnoready\Common\Service\Template\AdapterInterface $adapter
     * @throws RuntimeException
     */
    public function addAdapter(AdapterInterface $adapter)
    {
        if(isset($this->adapters[$adapter->getExtension()])){
            throw new RuntimeException(sprintf("The adapter with extension '%s' is already added.",$adapter->getExtension()));
        }
        $this->adapters[$adapter->getExtension()] = $adapter;
    }
    
    /**
     * Busca un adaptador
     * @param type $extension
     * @return AdapterInterface
     * @throws RuntimeException
     */
    public function getAdepter($extension)
    {
        if(!isset($this->adapters[$extension])){
            throw new RuntimeException(sprintf("The adapter with extension '%s' is not added.",$extension));
        }
        return $this->adapters[$extension];
    }
    
    /**
     * Metodo que renderiza el template y retona el string
     * @param type $template
     * @param array $variables
     */
    public function render(TemplateInterface $template,array $variables)
    {
        $adapter = $this->getAdepter($template->getTypeTemplate());
        return $adapter->render($template, $variables);
    }
    
    /**
     * Toma el string generado y crea el archivo PDF,TXT
     * @param type $string
     * @param array $parameters
     */
    public function compile(TemplateInterface $template,$filename,array $variables,array $parameters = [])
    {
        $adapter = $this->getAdepter($template->getTypeTemplate());
        $string = $this->render($template, $variables);
        return $adapter->compile($filename, $string, $parameters);
    }
}
