<?php

namespace Tecnoready\Common\Service\Snippet\Engine;

use Tecnoready\Common\Model\Snippet\SnippetInterface;

/**
 * Motor de renderizar snippet
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface EngineInterface
{
    public function render(SnippetInterface $snippet,array $parameters = [],array $options = []);
    public function getName();
}
