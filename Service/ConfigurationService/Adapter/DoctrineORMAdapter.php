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

    public function persist(ConfigurationInterface $configuration) {
        $this->em->persist($configuration);
        $success = true;
        return $success;
    }

    public function flush() {
        $this->em->flush();
        return true;
    }
}
