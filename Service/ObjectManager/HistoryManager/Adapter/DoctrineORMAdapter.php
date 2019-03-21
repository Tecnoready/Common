<?php

namespace Tecnoready\Common\Service\ObjectManager\HistoryManager\Adapter;

use Tecnoready\Common\Model\ObjectManager\HistoryManager\HistoryInterface;
use Doctrine\ORM\EntityManager;
use Pagerfanta\Pagerfanta as Paginator;
use Pagerfanta\Adapter\DoctrineORMAdapter as Adapter;

/**
 * Adaptador de doctrine2
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMAdapter implements HistoryAdapterInterface
{
    use \Tecnoready\Common\Service\ObjectManager\TraitConfigure;
    
    /**
     * @var string
     */
    private $className;
    /**
     * @var EntityManager
     */
    private $em;
    
    public function __construct($className,EntityManager $em)
    {
        $this->className = $className;
        $this->em = $em;
    }
    
    public function create(HistoryInterface $entity)
    {
        $this->em->persist($entity);
        return $this->em->flush();
    }
    
    public function delete(HistoryInterface $entity)
    {
        $this->em->remove($entity);
        return $this->em->flush();
    }
    
    public function find($id)
    {
        return $this->em->find($id);
    }
    
    /**
     * @param array $criteria
     * @param array $sortBy
     * @return Paginator
     */
    public function getPaginator(array $criteria = [],array $sortBy = [])
    {
        $repository = $this->em->getRepository($this->className);
        $qb = $repository->createQueryBuilder("e");
        $qb
            ->andWhere("e.objectId = :objectId")
            ->andWhere("e.objectType = :objectType")
            ->setParameter("objectId",$this->objectId)
            ->setParameter("objectType",$this->objectType)
            ->orderBy("e.createdAt","DESC")
            ;
        $pagerfanta = new Paginator(new Adapter($qb));
        return $pagerfanta;
    }
}
