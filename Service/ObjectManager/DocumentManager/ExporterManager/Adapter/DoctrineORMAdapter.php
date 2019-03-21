<?php

namespace Tecnoready\Common\Service\ObjectManager\DocumentManager\ExporterManager\Adapter;

use Doctrine\ORM\EntityManager;

/**
 * Adaptador de doctrine
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMAdapter implements ExporterAdapterInterface
{
    /**
     * @var EntityManager
     */
    private $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function find($className,$id) {
        return $this->em->find($className, $id);
    }
}
