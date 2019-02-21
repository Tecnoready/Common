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
