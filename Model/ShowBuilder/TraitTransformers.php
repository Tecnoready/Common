<?php

namespace Tecnoready\Common\Model\ShowBuilder;

use JMS\Serializer\Annotation as JMS;
USE Tecnoready\Common\Service\ShowBuilder\DataTransformerInterface;

/**
 * Trait de transformadores
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
trait TraitTransformers
{
    /**
     * @JMS\Exclude
     * @var DataTransformerInterface 
     */
    private $transformers;
    
    public function setTransformers(array $transformers)
    {
        $this->transformers = $transformers;
        return $this;
    }
    
    private function transformValue($value,array $options = [])
    {
        foreach ($this->transformers as $transformer) {
            if($transformer->supports($value,$options)){
                $value = $transformer->transform($value,$options);
            }
        }
        return $value;
    }
}
