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
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\Common\Util\ClassUtils;

/**
 * Transforma los id a entidades
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMTransformer implements DataTransformerInterface
{
    const TYPE_DOCTRINE = "doctrine2orm";
    /**
     * Manejador de entidades
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }
    
    public function reverseTransform($value, ConfigurationInterface $configuration) {
        if($configuration->getType() === self::TYPE_DOCTRINE){
            $value = $this->em->find($configuration->getDataType(), $value);
        }
        return $value;
    }

    public function transform($value, ConfigurationInterface $configuration) {
        if(in_array($configuration->getType(),["object",self::TYPE_DOCTRINE])){
            try {
                $className = ClassUtils::getRealClass(get_class($value));
                $class = $this->em->getClassMetadata($className);
                $propertyPath = $class->identifier[0];
                $configuration->setDataType($className);
                $configuration->setType(self::TYPE_DOCTRINE);
                $accessor = \Symfony\Component\PropertyAccess\PropertyAccess::createPropertyAccessor();
                $value = $accessor->getValue($value, $propertyPath);
            } catch (MappingException $ex) {
                //No esta manejada la entidad por doctrine
            }
        }
        return $value;
    }
}
