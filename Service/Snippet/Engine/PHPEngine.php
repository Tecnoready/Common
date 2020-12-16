<?php

namespace Tecnoready\Common\Service\Snippet\Engine;

use Tecnoready\Common\Model\Snippet\SnippetInterface;

/**
 * Motor para parsear codigo php
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class PHPEngine implements EngineInterface
{
    public function getName()
    {
        return "php";
    }

    public function render(SnippetInterface $snippet,array $parameters = [],array $options = [])
    {
        $function = function($code,array $parameters = array()){
            extract($parameters);
            return eval($code);
        };
        $result = $function($snippet->getCode(),$parameters);
        
        return $result;
    }

}
