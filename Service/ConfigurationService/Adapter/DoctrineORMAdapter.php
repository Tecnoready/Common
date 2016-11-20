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

/**
 * Adaptador de doctrine2
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class DoctrineORMAdapter implements ConfigurationAdapterInterface
{
    /**
     * Manejador de entidades
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }

    public function update($key, $value, $description,$wrapperName) {
        $entity = $this->find($key);
        if($entity === null){
            $entity = $this->createNew();
            $entity->setEnabled(true);
        }else{
            $entity->setUpdatedAt();
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

}
