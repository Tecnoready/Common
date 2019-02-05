<?php

namespace Tecnoready\Common\Service\ObjectManager\NoteManager\Adapter;

use Doctrine\ORM\EntityManager;
use Pagerfanta\Pagerfanta as Paginator;
use Pagerfanta\Adapter\DoctrineORMAdapter as Adapter;
use Tecnoready\Common\Model\ObjectManager\NoteManager\NoteInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Adaptador de doctrine2 para las notas
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMAdapter implements NoteAdapterInterface
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
    
    private function create(NoteInterface $entity)
    {
        $this->em->persist($entity);
        return $this->em->flush();
    }

    public function delete(NoteInterface $entity)
    {
        $this->em->remove($entity);
        return $this->em->flush();
    }

    public function find($id)
    {
        return $this->em->find($id);
    }

    public function getPaginator($type, array $criteria = array(), array $sortBy = array())
    {
        $repository = $this->em->getRepository($this->className);
        $qb = $repository->createQueryBuilder("e");
        $qb
            ->andWhere("e.type = :type")
            ->andWhere("e.objectId = :objectId")
            ->andWhere("e.objectType = :objectType")
            ->setParameter("objectId",$this->objectId)
            ->setParameter("objectType",$this->objectType)
            ->setParameter("type",$type)
            ->orderBy("e.createdAt","DESC")
            ;
        $pagerfanta = new Paginator(new Adapter($qb));
        return $pagerfanta;
    }

    public function addPrivate($note,array $options = [])
    {
        $entity = $this->createNew($note,NoteInterface::TYPE_PRIVATE,$options);
        $this->create($entity);
        return $entity;
    }

    public function addPublic($note,array $options = [])
    {
        $entity = $this->createNew($note,NoteInterface::TYPE_PUBLIC,$options);
        $this->create($entity);
        return $entity;
    }
    
    /**
     * @return \Tecnoready\Common\Model\ObjectManager\NoteManager\NoteInterface
     */
    private function createNew($note,$type,array $options = [])
    {
        $resolve = new OptionsResolver();
        $resolve->setRequired("user");
        $options = $resolve->resolve($options);
        $entity = new $this->className();
        $entity
            ->setUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setCreatedAt(new \DateTime())
            ->setObjectType($this->objectType)
            ->setObjectId($this->objectId)
            ->setDescription($note)
            ->setType($type)
            ->setUser($options["user"])
            ;
        return $entity;
    }

}
