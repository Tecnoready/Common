<?php

namespace Tecnoready\Common\Service\Snippet;

use Tecnoready\Common\Service\Snippet\Engine\EngineInterface;
use Tecnoready\Common\Model\Snippet\SnippetInterface;
use Tecnoready\Common\Service\Snippet\Adapter\SnippetAdapterInterface;
use RuntimeException;

/**
 * Manejador de snippet
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class SnippetManager
{
    /**
     * Motores
     * @var array 
     */
    private $engines;
    
    public function __construct()
    {
        $this->engines = [];
        $this->addEngine(new Engine\PHPEngine());
    }
    
    /**
     * Agrega un motor
     * @param EngineInterface $engine
     * @return $this
     * @throws RuntimeException
     */
    public function addEngine(EngineInterface $engine)
    {
        if(isset($this->engines[$engine->getName()])){
            throw new RuntimeException(sprintf("El motor de snippet '%s' ya se encuentra agregado.",$engine->getName()));
        }
        $this->engines[$engine->getName()] = $engine;
        
        return $this;
    }
    
    /**
     * Retorna todos los moteres
     * @return array
     */
    public function getDefinedEngines()
    {
        $engines = [];
        foreach ($this->engines as $engine) {
            $engines[$engine->getName()] = $engine->getName();
        }
        return $engines;
    }
    
    /**
     * Retorna el motor o da error
     * @param type $name
     * @return EngineInterface
     * @throws RuntimeException
     */
    private function getEngine($name)
    {
        if(!isset($this->engines[$name])){
            throw new RuntimeException(sprintf("El motor de snippet '%s' no se encuentra agregado.",$name));
        }
        
        return $this->engines[$name];
    }

    /**
     * Renderiza un snippet
     * @param Snippet $snippet
     * @param array $parameters
     * @param array $options
     * @return type
     * @throws RuntimeException
     */
    public function render(SnippetInterface $snippet,array $parameters = [],array $options = [])
    {
        $requiredParameters = $snippet->getRequiredParameters();
        if(count($requiredParameters) > 0){
            $diff = [];
            foreach ($requiredParameters as $requiredParameter) {
                $key = $requiredParameter->getItemKey();
                if(!isset($parameters[$key])){
                    $diff[] = $key;
                }
            }
            if(count($diff) > 0){
                throw new RuntimeException(sprintf("Los parametros '%s' son requeridos.", implode(", ", $diff)));
            }
        }
        
        $engine = $this->getEngine($snippet->getEngine());
        $result = $engine->render($snippet->getCode(),$parameters,$options);
        if($result !== 0 && empty($result)){
            throw new RuntimeException(sprintf("El resultado del snippet '%s' no puede estar vacio o ser NULL. Retorno '%s'",$snippet->getCode(),$result));
        }
        return $result;
    }
}
