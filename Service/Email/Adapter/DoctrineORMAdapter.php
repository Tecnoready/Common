<?php

namespace Tecnoready\Common\Service\Email\Adapter;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Adaptador de doctrine para el servicio de correo
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMAdapter implements EmailAdapterInterface
{
    /**
     * @var Registry
     */
    protected $doctrine;
    
    /**
     * @var \Doctrine\ORM\EntityManagerInterface 
     */
    protected $em;
    
    protected $options;
    
    public function __construct(Registry $doctrine,\Doctrine\ORM\EntityManagerInterface $em, array $options = []) {
        $this->doctrine = $doctrine;
        $this->em = $em;
        $resolver = new OptionsResolver();
        
        $resolver->setRequired([
            "email_queue_class", 
            "email_template_class", 
            "email_component_class",
            ]);
        $resolver->setDefaults([
            "connection" => null,
        ]);
        $this->options = $resolver->resolve($options);
    }
    
    public function createEmailQueue() {
        return new $this->options["email_queue_class"]();
    }

    public function find($id) {
        return $this->em->find($this->options["email_template_class"], $id);
    }

    public function flush() {
        $this->em->flush();
    }

    public function persist($entity) {
        $em = $this->em;
        $em->persist($entity);
    }

    public function createComponent() {
        return new $this->options["email_component_class"]();
    }
    
    public function createEmailTemplate() {
        return new $this->options["email_template_class"]();
    }

    public function remove($entity) {
        $em = $this->em;
        $em->remove($entity);
    }

}
