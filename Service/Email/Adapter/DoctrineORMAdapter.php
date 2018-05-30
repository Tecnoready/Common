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
    
    protected $options;
    
    public function __construct(Registry $doctrine,array $options = []) {
        $this->doctrine = $doctrine;
        $resolver = new OptionsResolver();
        
        $resolver->setRequired([
            "email_queue_class", 
            "email_template_class", 
            ]);
        $this->options = $resolver->resolve($options);
    }
    
    public function createNew() {
        return new $this->options["email_queue_class"]();
    }

    public function find($id) {
        return $this->doctrine->getManager()->find($this->options["email_template_class"], $id);
    }

    public function flush() {
        $this->doctrine->flush();
    }

    public function persist($entity) {
        $em = $this->doctrine->getManager();
        $em->persist($entity);
    }
}
