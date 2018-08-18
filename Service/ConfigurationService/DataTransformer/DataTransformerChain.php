<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\ConfigurationService\DataTransformer;

use Tecnoready\Common\Service\ConfigurationService\DataTransformerInterface;
use Tecnoready\Common\Model\Configuration\BaseEntity\ConfigurationInterface;

/**
 * Junta varios data transformers
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DataTransformerChain implements DataTransformerInterface
{
    protected $transformers;
    
    protected $transformers;

    /**
     * Uses the given value transformers to transform values.
     *
     * @param DataTransformerInterface[] $transformers
     */
    public function __construct(array $transformers)
    {
        $this->transformers = $transformers;
    }

    /**
     * Passes the value through the transform() method of all nested transformers.
     *
     * The transformers receive the value in the same order as they were passed
     * to the constructor. Each transformer receives the result of the previous
     * transformer as input. The output of the last transformer is returned
     * by this method.
     *
     * @param mixed $value The original value
     *
     * @return mixed The transformed value
     *
     * @throws TransformationFailedException
     */
    public function transform($value,ConfigurationInterface $configuration)
    {
        foreach ($this->transformers as $transformer) {
            $value = $transformer->transform($value,$configuration);
        }

        return $value;
    }

    /**
     * Passes the value through the reverseTransform() method of all nested
     * transformers.
     *
     * The transformers receive the value in the reverse order as they were passed
     * to the constructor. Each transformer receives the result of the previous
     * transformer as input. The output of the last transformer is returned
     * by this method.
     *
     * @param mixed $value The transformed value
     *
     * @return mixed The reverse-transformed value
     *
     * @throws TransformationFailedException
     */
    public function reverseTransform($value,ConfigurationInterface $configuration)
    {
        for ($i = \count($this->transformers) - 1; $i >= 0; --$i) {
            $value = $this->transformers[$i]->reverseTransform($value,$configuration);
        }

        return $value;
    }

    /**
     * @return DataTransformerInterface[]
     */
    public function getTransformers()
    {
        return $this->transformers;
    }
}
