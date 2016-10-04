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

use Tecnoready\Common\Model\Configuration\Statistics\Yii2ActiveRecord\StatisticsMonth;
use Tecnoready\Common\Model\Configuration\Statistics\Yii2ActiveRecord\StatisticsYear;

/**
 * Adaptador de yii2
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class Yii2ActiveRecordAdapter implements StatisticsAdapterInterface 
{
    public function flush() {
        
    }

    public function newStatisticsMonth() {
        return new StatisticsMonth();
    }

    public function newYearStatistics() {
        return new StatisticsYear();
    }

    public function persist($entity) {
        $entity->save();
    }
}
