<?php

namespace Tecnoready\Common\Service\Template\Adapter;

use Tecnoready\Common\Model\Template\TemplateInterface;
use Doctrine\ORM\EntityManager;

/**
 * Adaptador de doctrine2
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMAdapter implements AdapterInterface
{
     /**
     * @var EntityManager
     */
    private $em;
    
    /**
     * Nombre de la clase
     * @var string
     */
    private $className;

    public function __construct($className,EntityManager $em)
    {
        $this->em = $em;
        $this->className = $className;
    }
    
    public function find($id): TemplateInterface
    {
        return $this->em->find($this->className, $id);
    }
}
