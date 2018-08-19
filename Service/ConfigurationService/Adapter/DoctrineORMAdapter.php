<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\ConfigurationService\Adapter;

use Tecnoready\Common\Model\Configuration\BaseEntity\ConfigurationInterface;

/**
 * Adaptador de doctrine2
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMAdapter implements ConfigurationAdapterInterface
{
    /**
     * Manejador de entidades
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    protected $className;
    
    public function __construct(\Doctrine\ORM\EntityManager $em,$className) {
        $this->em = $em;
        $this->className = $className;
    }
    
    public function createNew() {
        return new $this->className();
    }

    public function find($key) {
        return $this->em->getRepository($this->className)->findOneBy([
            "key" => $key,
        ]);
    }

    public function findAll() {
        return $this->em->getRepository($this->className)->findAll();
    }

    public function update($key, $value, $description,$wrapperName) {
        //desuso
        $entity = $this->find($key);
        if($entity === null){
            $entity = $this->createNew();
            $entity->setEnabled(true);
            
            $entity->setCreatedAt(new \DateTime());
            $type = gettype($value);
            $entity->setType($type);
        }else{
            $entity->setUpdatedAt();
        }
        if($type == "object"){
            $className = get_class($value);
            $entity->setDataType($className);
            $class = $this->em->getClassMetadata($className);
            $propertyPath = $class->identifier[0];
            $accessor = \Symfony\Component\PropertyAccess\PropertyAccess::createPropertyAccessor();
            $value = $accessor->getValue($value, $propertyPath);
            var_dump($class->identifier[0]);
            var_dump($value);
            die;
        }else if($type == "array"){
            $value = serialize($value);
        }
        $entity->setKey($key);
        $entity->setValue($value);
        if($description != null){
            $entity->setDescription($description);
        }
        $entity->setNameWrapper($wrapperName);
        $this->em->persist($entity);
        $this->em->flush();
        $success = true;
        return $success;
    }
    
    public function persist(ConfigurationInterface $configuration) {
        $this->em->persist($configuration);
//        $this->em->flush();
        $success = true;
        return $success;
    }

    public function flush() {
        $this->em->flush();
        return true;
    }
}
