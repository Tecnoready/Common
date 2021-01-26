<?php

namespace Tecnoready\Common\Service\Snippet\Adapter;

/**
 * Adaptador de doctrine
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMAdapter implements SnippetAdapterInterface
{
    /**
     * Manejador de entidades
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) 
    {
        $this->em = $em;
    }

    public function flush()
    {
        $this->em->flush();
    }

    public function persist($entity)
    {
        $this->em->persist($entity);
    }

}
