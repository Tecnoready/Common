<?php

namespace Tecnoready\Common\Service\Statistics\Adapter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Tecnoready\Common\Model\Statistics\LaravelEloquent\StatisticsYear;
use Tecnoready\Common\Model\Statistics\LaravelEloquent\StatisticsMonth;
use Tecnoready\Common\Service\ObjectManager\StatisticManager\Adapter\StatisticsAdapterInterface;

/**
 * Adaptador para laravel con eloquent
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class LaravelEloquentAdapter implements StatisticsAdapterInterface {
    
    /**
     * Opciones de configuraciones
     * @var array
     */
    private $options;

    public function __construct(array $options = []) {
        $this->setOptions($options);
    }
    
    private function setOptions(array $options = []) {
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            "connection",
            "model_statistics_month",
            "model_statistics_year",
        ]);
        $this->options = $resolver->resolve($options);
    }
    
    public function flush() {
        try {
            $this->options["connection"]->commit();
        } catch (\Exception $ex) {
            $this->options["connection"]->rollback();
            throw $ex;
        }
    }

    public function newStatisticsMonth(\Tecnoready\Common\Service\Statistics\StatisticsManager $statisticsManager) {
        $options = $statisticsManager->getOptions();
        $className = $this->options["model_statistics_month"];
        $entity = new $className();
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
        $className = $this->options["model_statistics_year"];
        $entity = new $className();
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
        $this->options["connection"]->beginTransaction();
        if(!$entity->save()){
            throw new \Exception("No se pudo guardar la estadistica.");
        }
    }

    
    /**
     * Busca una estadistica año
     * @param array $params
     * @return \Tecnoready\Common\Model\Statistics\StatisticsYearInterface
     */
    public function findStatisticsYear(array $options = []) {
        $result = null;
        return $result;
    }
}
