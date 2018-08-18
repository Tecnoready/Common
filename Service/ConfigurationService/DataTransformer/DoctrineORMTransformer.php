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

/**
 * Transforma los id a entidades
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMTransformer implements DataTransformerInterface
{
    /**
     * Manejador de entidades
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }
    
    public function reverseTransform($value, ConfigurationInterface $configuration) {
        if($configuration->getType() === "object"){
            $value = $this->em->find($configuration->getDataType(), $value);
        }
        return $value;
    }

    public function transform($value, ConfigurationInterface $configuration) {
        if($configuration->getType() === "object"){
//            $this->em->persist($value);
//            $this->em->flush();
            try {
                 $className = get_class($value);
                $configuration->setDataType($className);
                $class = $this->em->getClassMetadata($className);
//                var_dump($class);
                $propertyPath = $class->identifier[0];
                $accessor = \Symfony\Component\PropertyAccess\PropertyAccess::createPropertyAccessor();
                $value = $accessor->getValue($value, $propertyPath);
//                var_dump($class->identifier[0]);
//                var_dump($value);
//                die;
            } catch (MappingException $ex) {
                //No esta manejada la entidad por doctrine
//                var_dump("No se maneja.");
            }
        }
        return $value;
    }
}
