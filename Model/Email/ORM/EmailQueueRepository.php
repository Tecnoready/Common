<?php

namespace Tecnoready\Common\Model\Email\ORM;

use Tecnocreaciones\Bundle\ToolsBundle\ORM\EntityRepository;
use Tecnoready\Common\Model\Email\ORM\ModelEmailQueue;

/**
 * Repositorio de cola de email
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class EmailQueueRepository extends EntityRepository
{
    /**
     * Retorna un paginador con los correos pendientes por construir y enviar
     * @param array $criteria
     * @return \Tecnocreaciones\Bundle\ToolsBundle\Model\Paginator\Paginator
     */
    public function findByCriteria(array $criteria = [], array $orderBy = null)
    {
        $criteria = $this->parseCriteria($criteria);

        $a = $this->getAlias();
        $qb = $this->createQueryBuilder($a);

        if (($status = $criteria->remove("status")) != null) {
            if (!is_array($status)) {
                $status = [$status];
            }
            $qb
                ->andWhere($a . '.status IN(:status)')
                ->setParameter("status", $status)
            ;
        }
        
        if(($environment = $criteria->remove("environment")) != null){
            $qb
                ->andWhere($a.".environment = :environment")
                ->setParameter("environment", $environment)
                ;            
        }
        
        $this->applySorting($qb, $orderBy);        

        return $this->getPaginator($qb);
     }

    /**
     * Retorna un paginador con los correos pendientes por construir y enviar
     * @param type $environment
     * @return \Tecnocreaciones\Bundle\ToolsBundle\Model\Paginator\Paginator
     */
    public function getPendings($environment) {
       $qb = $this->getQueryBuilder();
       
       $qb
           ->andWhere("eq.status = :status")
           ->andWhere("eq.environment = :environment")
           ->setParameter("status",ModelEmailQueue::STATUS_NOT_SENT)
           ->setParameter("environment",$environment)
           ;
       return $this->getPaginator($qb);
    }
    
    public function getAlias() {
        return "eq";
    }
}
