<?php

namespace Tecnoready\Common\Service\Template;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Tecnoready\Common\Model\Template\TemplateInterface;
use RuntimeException;
use Tecnoready\Common\Service\Template\Adapter\AdapterInterface;
use Tecnoready\Common\Service\Template\Engine\EngineInterface;

/**
 * Servicio que renderiza y compila plantillas para generad documentos (pdf)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TemplateService
{
    /**
     * Motores disponibles
     * @var array
     */
    private $engines;
    
    /**
     * Adaptador para buscar las plantillas
     * @var AdapterInterface
     */
    private $adapter;

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
        $this->engines = [];
    }
    
    /**
     * Establece el adaptador
     * @param AdapterInterface $adapter
     * @return $this
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }
    
    /**
     * Añade un motor
     * @param \Tecnoready\Common\Service\Template\EngineInterface $adapter
     * @throws RuntimeException
     */
    public function addEngine(EngineInterface $adapter)
    {
        if (isset($this->engines[$adapter->getName()])) {
            throw new RuntimeException(sprintf("The adapter with name '%s' is already added.", $adapter->getExtension()));
        }
        $this->engines[$adapter->getName()] = $adapter;
        
        return $this;
    }

    /**
     * Busca un motor
     * @param string $name Nombre del motor
     * @return EngineInterface
     * @throws RuntimeException
     */
    public function getEngine($name)
    {
        if (!isset($this->engines[$name])) {
            throw new RuntimeException(sprintf("The adapter with extension '%s' is not added.", $name));
        }
        return $this->engines[$name];
    }

    /**
     * Metodo que renderiza el template y retona el string
     * @param type $template
     * @param array $variables
     */
    public function render(TemplateInterface $template, array $variables)
    {
        $adapter = $this->getEngine($template->getTypeTemplate());
        return $adapter->render($template, $variables);
    }

    /**
     * Busca la plantilla en la base de datos u otra fuente y la compila
     * @param type $id
     * @param type $filename
     * @param array $variables
     * @param array $parameters
     * @return type
     */
    public function compile($id, $filename, array $variables, array $parameters = [])
    {
        $template = $this->adapter->find($id);
        return $this->compileTemplate($template, $filename, $variables,$parameters);
    }

    /**
     * Toma el string generado y crea el archivo PDF,TXT
     * @param type $string
     * @param array $parameters
     */
    public function compileTemplate(TemplateInterface $template, $filename, array $variables, array $parameters = [])
    {
        $adapter = $this->getEngine($template->getTypeTemplate());
        $string = $this->render($template, $variables);
        return $adapter->compile($filename, $string, $parameters);
    }

}
