<?php

namespace Tecnoready\Common\Tests\Service\ShowBuilder;

use Tecnoready\Common\Tests\BaseWebTestCase;
use Tecnoready\Common\Service\ShowBuilder\ShowBuilderManager;

/**
 * Pruebas del constructor de show
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ShowBuilderManagerTest extends BaseWebTestCase
{
    /**
     * @return ShowBuilderManager
     */
    private function getShowBuilderManager()
    {
        return $this->getContainer()->get(ShowBuilderManager::class);
    }
    
    /**
     * Forma un string asi:
{
    "title": {
        "widget": "title",
        "text": "Titulo"
    },
    "content": {
        "items": [
            {
                "widget": "item",
                "text": "Contenido"
            },
            {
                "widget": "title",
                "text": "Sub titulo"
            },
            {
                "widget": "item",
                "text": "Contenido 2"
            }
        ]
    }
}
     */
    public function testShow()
    {
        $showBuilderManager = $this->getShowBuilderManager();
        
        $showBuilderManager->start();
        
        $showBuilderManager->addTitle("Titulo");
        $showBuilderManager->content()->addItem("Contenido");
        $showBuilderManager->content()->addSubTitle("Sub titulo");
        $showBuilderManager->content()->addItem("Contenido 2");
        
        $data = $showBuilderManager->end();
        $this->assertNotNull($data);
        $this->assertEquals('{"title":{"widget":"title","text":"Titulo"},"content":{"items":[{"widget":"item","text":"Contenido"}]}}', $data);
    }
    
    
}
