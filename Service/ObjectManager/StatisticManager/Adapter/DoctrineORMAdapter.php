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

use Tecnoready\Common\Service\ObjectManager\ConfigureInterface;

/**
 * Adaptador de doctrine 2
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMAdapter implements StatisticsAdapterInterface
{
    /**
     * @var string
     */
    public $classYearName;

    /**
     * @var string
     */
    private $classMonthName;

    /**
     * Manejador de entidades
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    
    public function __construct($classYearName,$classMonthName,\Doctrine\ORM\EntityManager $em) 
    {
        $this->classYearName = $classYearName;
        $this->classMonthName = $classMonthName;
        $this->em = $em;
    }

    /**
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  $objectId
     * @param  $objectType
     */
    public function configure($objectId, $objectType)
    {   
        $this->objectId = $objectId;
        $this->objectType = $objectType;
    }

	/**
     * @return \Tecnoready\Common\Model\Configuration\Statistics\StatisticsYearInterface Description
     */
    public function newYearStatistics(\Tecnoready\Common\Service\ObjectManager\StatisticManager\StatisticsManager $statisticsManager)
    {
        $entity = new $this->classYearName;
        $entity
            ->setUserAgent($this->getUserAgent())
            ->setCreatedAt(new \DateTime());

        return $entity;
    }
    
    /**
     * @return \Tecnoready\Common\Model\Configuration\Statistics\StatisticsMonthInterface Description
     */
    public function newStatisticsMonth(\Tecnoready\Common\Service\ObjectManager\StatisticManager\StatisticsManager $statisticsManager)
    {
        $entity = new $this->classMonthName;
        $entity
            ->setUserAgent($this->getUserAgent())
            ->setCreatedAt(new \DateTime());

        return $entity;
    }

    /**
     * Registra cambios en entidad
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  $entity
     * @return EntityManager
     */
    public function persist($entity)
    {
        return $this->em->persist($entity);
    }

    public function flush()
    {
        return $this->em->flush();
    }

    /**
     * Consulta de año
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  array  $params
     * @return StatisticYear
     */
    public function findStatisticsYear(array $params = array())
    {
        return $this->getEntityManager()->getRepository($this->classYearName)->findOneBy(array_merge(["objectType" => $this->objectType],$params));
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

    /**
     * Obtener el UserAgent
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return UserAgent
     */
    public function getUserAgent()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        } elseif (\Tecnoready\Common\Util\AppUtil::isCommandLineInterface()) {
            $userAgent = "cli-user-agent";
        }

        if (empty($userAgent)) {
            $userAgent = "unknown";
        }

        return $userAgent;
    }
}
