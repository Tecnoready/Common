<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\ObjectManager\StatisticManager\Adapter;

/**
 * Adaptador de doctrine 2
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMAdapter implements StatisticsAdapterInterface
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

	/**
     * @return \Tecnoready\Common\Model\Configuration\Statistics\StatisticsYearInterface Description
     */
    public function newYearStatistics(\Tecnoready\Common\Service\ObjectManager\StatisticManager\StatisticsManager $statisticsManager)
    {
        $entity = new \Pandco\Bundle\OMBundle\Entity\Statistics\StatisticsYear();
        $entity
            ->setUserAgent("dd")
            ->setCreatedAt(new \DateTime());

        return $entity;
    }
    
    /**
     * @return \Tecnoready\Common\Model\Configuration\Statistics\StatisticsMonthInterface Description
     */
    public function newStatisticsMonth(\Tecnoready\Common\Service\ObjectManager\StatisticManager\StatisticsManager $statisticsManager)
    {
        $entity = new \Pandco\Bundle\OMBundle\Entity\Statistics\StatisticsMonth();
        $entity
            ->setUserAgent("s")
            ->setCreatedAt(new \DateTime());

        return $entity;
    }

    public function persist($entity)
    {
        return $this->em->persist($entity);
    }

    public function flush()
    {
        return $this->em->flush();
    }

    /**
     * Retorna manejador de entidades
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }
}