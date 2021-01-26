<?php

namespace Tecnoready\Common\Tests\Service\Snippet;

use Tecnoready\Common\Service\Snippet\SnippetManager;
use Tecnoready\Common\Service\Snippet\Engine\PHPEngine;
use Tecnoready\Common\Model\Snippet\Snippet;

/**
 * Prueba de snippet
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class SnippetManagerTest
{
    public function testEnginePhp()
    {
        $snippetManager = new SnippetManager();
        $phpEngine = new PHPEngine();
        $snippetManager->addEngine($phpEngine);
        
        $snippet = new Snippet();
        $snippet
            ->setEngine($phpEngine->getName())
            ->setCode($code)
            ;
        
        $snippetManager->render($snippet, $options);
    }
}
