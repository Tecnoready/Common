<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Email\ORM;

use Doctrine\ORM\EntityRepository;
use Tecnoready\Common\Model\Email\EmailInterface;

/**
 * Description of EmailRepository
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class EmailRepository extends EntityRepository {
    
    public function addEmail(EmailInterface $email)
    {
        $em = $this->getEntityManager();
        $email->setStatus(EmailInterface::STATUS_READY);
        $email->setRetries(0);
        $em->persist($email);
        $em->flush();
    }
    public function getAllEmails()
    {
        $qb = $this->createQueryBuilder('e');
        $qb->addOrderBy('e.createdAt', 'DESC');
        return $qb->getQuery();
    }
    public function getEmailQueue($environment,$limit = 100)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->where($qb->expr()->eq('e.status', ':status'))->setParameter(':status', EmailInterface::STATUS_READY);
        $qb->orWhere($qb->expr()->eq('e.status', ':status_1'))->setParameter(':status_1', EmailInterface::STATUS_FAILED);
        $qb->andWhere($qb->expr()->lt('e.retries', ':retries'))->setParameter(':retries', 10);
//        $qb->andWhere($qb->expr()->eq('e.environment', ':environment'))->setParameter(':environment', $environment);
        $qb->addOrderBy('e.retries', 'ASC');
        $qb->addOrderBy('e.createdAt', 'ASC');
        if (empty($limit) === false) {
            $qb->setMaxResults($limit);
        }
        
        $emails = $qb->getQuery()->getResult();
        if (count($emails) > 0) {
            $ids = [];
            foreach ($emails as $email) {
                $ids[] = $email->getId();
            }
            $query = $this->_em->createQuery("UPDATE ".$this->getClassName()." e SET e.status = '" . EmailInterface::STATUS_PROCESSING . "' WHERE e.id IN (:ids)");
            $query->setParameter(':ids', $ids);
            $query->execute();
        }
        return $emails;
    }
    public function markFailedSending(EmailInterface $email, \Exception $ex)
    {
        $email->setErrorMessage($ex->getMessage());
        $email->setStatus(EmailInterface::STATUS_FAILED);
        $email->setRetries($email->getRetries() + 1);
        $em = $this->getEntityManager();
        $em->persist($email);
        $em->flush();
    }
    public function markCompleteSending(EmailInterface $email)
    {
        $email->setStatus(EmailInterface::STATUS_COMPLETE);
        $email->setSentAt(new \DateTime());
        $email->setErrorMessage('');
        $em = $this->getEntityManager();
        $em->persist($email);
        $em->flush();
    }
}
