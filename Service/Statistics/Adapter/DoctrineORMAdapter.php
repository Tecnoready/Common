<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\Statistics\Adapter;

/**
 * Adaptador de doctrine 2
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class DoctrineORMAdapter implements StatisticsAdapterInterface
{
    /**
     * Manejador de entidades
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }
}
