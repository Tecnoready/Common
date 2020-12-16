<?php

namespace Tecnoready\Common\Model\Snippet;

use Tecnoready\Common\Service\SequenceGenerator\ItemReferenceInterface;

/**
 * Snippet
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface SnippetInterface extends ItemReferenceInterface
{
    public function getName();
    
    public function setName($name);
    
    public function getEngine();

    public function getCode();

    public function setEngine($engine);

    public function setCode($code);
    
    public function getRequiredParameters();
}
