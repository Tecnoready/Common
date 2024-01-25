<?php

namespace Tecnoready\Common\Service\ObjectManager\StatisticManager\Adapter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Tecnoready\Common\Service\ObjectManager\StatisticManager\Adapter\StatisticsAdapterInterface;
use Tecnoready\Common\Service\ObjectManager\StatisticManager\StatisticsManager;

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
    
    /**
     * Se usa para no abrir 2 transacciones
     * @var bool
     */
    private $transactionActive = false;

    public function __construct(array $options = []) {
        $this->setOptions($options);
    }
    
    private function setOptions(array $options = []) {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "find_statistics_year_callback" => null,
        ]);
        $resolver->setRequired([
            "connection",
            "model_statistics_month",
            "model_statistics_year",
        ]);
        $this->options = $resolver->resolve($options);
    }
    
    public function newStatisticsMonth(StatisticsManager $statisticsManager) {
        $className = $this->options["model_statistics_month"];
        $entity = new $className();
        $entity->total = 0;
        for($i=1;$i<=31;$i++){
            $entity->{"day".$i} = 0;
        }
        return $entity;
    }

    public function newYearStatistics(StatisticsManager $statisticsManager) {
        $className = $this->options["model_statistics_year"];
        $entity = new $className();
        $entity->total = 0;
        for($i=1; $i<=12;$i++){
            $entity->{"total_month_".$i} = 0;
        }
        return $entity;
    }

    public function persist($entity) {
        if(!$this->transactionActive){
            $this->transactionActive = true;
            $this->options["connection"]->beginTransaction();
        }
        if(!$entity->save()){
            throw new \Exception("No se pudo guardar la estadistica.");
        }
    }
    
    public function flush() {
        try {
            $this->options["connection"]->commit();
        } catch (\Exception $ex) {
            $this->options["connection"]->rollback();
            throw $ex;
        } finally {
            $this->transactionActive = false;
        }
    }

    
    /**
     * Busca una estadistica año
     * @param array $params
     * @return \Tecnoready\Common\Model\Statistics\StatisticsYearInterface
     */
    public function findStatisticsYear(array $options = []) {
        $className = $this->options["model_statistics_year"];
        $qb = $className::where("year",$options["year"])
                ->where("object_type",$options["objectType"])
                ->where("object_id",$options["objectId"])
                ->where("object",$options["object"])
                ;
        if(is_callable($this->options["find_statistics_year_callback"])){
            call_user_func_array($this->options["find_statistics_year_callback"],[&$qb,$options["extras"]]);
        }
        $result = $qb ->first();
        return $result;
    }
    
    public function configure($param) {
        
    }
}
