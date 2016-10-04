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
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface StatisticsAdapterInterface {
    public function persist($entity);
    public function flush();
    
    /**
     * @return \Tecnoready\Common\Model\Configuration\Statistics\StatisticsYearInterface Description
     */
    public function newYearStatistics();
    /**
     * @return \Tecnoready\Common\Model\Configuration\Statistics\StatisticsMonthInterface Description
     */
    public function newStatisticsMonth();
}
