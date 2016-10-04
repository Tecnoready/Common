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

use Tecnoready\Common\Model\Statistics\Yii2ActiveRecord\StatisticsMonth;
use Tecnoready\Common\Model\Statistics\Yii2ActiveRecord\StatisticsYear;

/**
 * Adaptador de yii2
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class Yii2ActiveRecordAdapter implements StatisticsAdapterInterface 
{
    /**
     * @var \yii\db\Transaction
     */
    private $transaction;
    
    public function flush() {
        try {
            $this->transaction->commit();
        } catch (\Exception $ex) {
            $this->transaction->rollBack();
            throw $ex;
        } finally {
            $this->transaction = null;
        }
    }

    public function newStatisticsMonth(\Tecnoready\Common\Service\Statistics\StatisticsManager $statisticsManager) {
        $options = $statisticsManager->getOptions();
        $entity = new StatisticsMonth();
        $entity->total = 0;
        for($i=1;$i<=31;$i++){
            $entity->{"day".$i} = 0;
        }
        $now = new \DateTime();
        $nowString = $now->format($options["date_format"]);
        $entity->created_at = $nowString;
        $entity->updated_at = $nowString;
        $entity->created_from_ip = $options["current_ip"];
        $entity->updated_from_ip = $options["current_ip"];
        return $entity;
    }

    public function newYearStatistics(\Tecnoready\Common\Service\Statistics\StatisticsManager $statisticsManager) {
        $options = $statisticsManager->getOptions();
        $entity = new StatisticsYear();
        $entity->total = 0;
        for($i=1; $i<=12;$i++){
            $entity->{"total_month_".$i} = 0;
        }
        $now = new \DateTime();
        $nowString = $now->format($options["date_format"]);
        $entity->created_at = $nowString;
        $entity->updated_at = $nowString;
        $entity->created_from_ip = $options["current_ip"];
        $entity->updated_from_ip = $options["current_ip"];
        
        return $entity;
    }

    public function persist($entity) {
        if(!$this->transaction){
            $this->transaction = $this->getConnection()->beginTransaction();
        }
        if(!$entity->save()){
            var_dump($entity->getErrors());
            die;
            throw new \Exception();
        }
    }
    
    /**
     * 
     * @return \yii\db\Connection
     */
    protected function getConnection()
    {
        return \Yii::$app->db;
    }
}
