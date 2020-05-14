<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\ObjectManager\StatisticManager;

use Tecnoready\Common\Service\ObjectManager\ConfigureInterface;
use InvalidArgumentException;

/**
 * Manejador de estadisticas
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class StatisticsManager implements ConfigureInterface
{
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor 
     */
    private $propertyAccess;
    
    /**
     * @var Adapter\StatisticsAdapterInterface
     */
    private $adapter;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * Adaptadores disponibles
     * @var Adapters
     */
    private $adapters;

    /**
     * Adaptador por defecto
     * @var Adapter\StatisticsAdapterInterface
     */
    private $defaultAdapter;

    /**
     * Objetos validos
     * @var objectValids
     */
    private $objectValids;

    /**
     * $object
     * @var Object
     */
    private $object;
    
    public function __construct(Adapter\StatisticsAdapterInterface $adapter) 
    {
        if(!class_exists("Symfony\Component\PropertyAccess\PropertyAccess")){
            throw new \Exception(sprintf("The package '%s' is required, please install https://packagist.org/packages/symfony/property-access",'"symfony/property-access": "^3.1"'));
        }
        if(!class_exists("Symfony\Component\OptionsResolver\OptionsResolver")){
            throw new \Exception(sprintf("The package '%s' is required, please install https://packagist.org/packages/symfony/options-resolver",'"symfony/options-resolver": "^3.1"'));
        }
        $builder = \Symfony\Component\PropertyAccess\PropertyAccess::createPropertyAccessorBuilder();
        $builder->enableMagicCall();
        
        $this->propertyAccess = $builder->getPropertyAccessor();
        $this->defaultAdapter = $adapter;
    }
    
    /**
     * Registro de configuraciones
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  $objectId
     * @param  $objectType
     */
    public function configure($objectId, $objectType)
    {
        $this->adapter = $this->defaultAdapter;
        if (isset($this->adapters[$objectType])) {
            $this->adapter = $this->adapters[$objectType];
        }
        
        $this->objectId = $objectId;
        $this->objectType = $objectType;
        $this->adapter->configure($objectId, $objectType);
    }
    
    /**
     * Retorna las estadisticas de un mes especifico por año y dia
     * @param type $year
     * @param type $month
     * @param type $day
     * @return StatisticsMonthValue
     */
    public function getStatisticsMonthValue($year = null,$month = null,$day= null)
    {
        $now = new \DateTime();
        if($year === null){
            $year = (int)$now->format("Y");
        }
        if($month === null){
            $month = (int)$now->format("m");
        }
        if($day === null){
            $day = (int)$now->format("d");
        }
        $foundStatistics = $this->findStatisticsMonth($year, $month);

        return (int)$this->getValueDay($day,$foundStatistics);
    }
    
    public function getStatisticsMonthTotal($year = null,$month = null) 
    {
        $now = new \DateTime();
        if($year === null){
            $year = (int)$now->format("Y");
        }
        if($month === null){
            $month = (int)$now->format("m");
        }
        $foundStatistics = $this->findStatisticsMonth($year, $month);
        $total = 0;
        if($foundStatistics !== null){
            $total = $foundStatistics->getTotal();
        }

        return $total;
    }
    
    public function getStatisticsYearValue($year = null)
    {
        $now = new \DateTime();
        if($year === null){
            $year = (int)$now->format("Y");
        }
        $foundStatistics = $this->findStatisticsYear($year);
        $total = 0;
        if($foundStatistics){
            $total = $foundStatistics->getTotal();
        }

        return $total;
    }
    
    /**
     * Retorna las estadisticas de un mes por el año
     * @param type $year
     * @param type $month
     * @return type
     */
    public function findStatisticsMonth($year,$month) 
    {
        $foundStatisticsYear = $this->findStatisticsYear($year);
        $foundStatistics = null;
        if($foundStatisticsYear !== null){
            $foundStatistics = $foundStatisticsYear->getMonth($month);
        }

        return $foundStatistics;
    }
    
    /**
     * Retorna las estadisticas de un año
     * @param type $year
     * @param type $month
     * @return type
     */
    public function findStatisticsYear($year) 
    {
        $year = (int)$year;
        $foundStatistics = $this->adapter->findStatisticsYear([
            "object" => $this->object,
            "objectId" => $this->objectId,
            "year" => $year
        ]);
        if (!$foundStatistics) {
            $foundStatistics = null;
        }
        
        return $foundStatistics;
    }

    /**
     * Cuenta uno a las estadisticas de un objeto por el año, mes y dia
     * @param type $year
     * @param type $month
     * @param type $day
     * @return type
     */
    public function countStatisticsMonth($object,$year = null,$month = null,$day= null, $value = null)
    {
        if (!in_array($object,$this->objectValids[$this->objectType])) {
            throw new InvalidArgumentException(sprintf("The object '%s' not add in object type '%s', please add",$object,$this->objectType));
        }
        $this->object = $object;

        $now = new \DateTime();
        if($year === null){
            $year = (int)$now->format("Y");
        }
        if($month === null){
            $month = (int)$now->format("m");
        }
        if($day === null){
            $day = (int)$now->format("d");
        }
        
        // Consulta de estadistica año
        $foundStatisticsYear = $this->findStatisticsYear($year);
        if($foundStatisticsYear === null){
            $foundStatisticsYear = $this->newYearStatistics($year);
            $this->adapter->persist($foundStatisticsYear);
        }
        $foundStatisticsMonth = $foundStatisticsYear->getMonth($month);

        if ($value && is_string($value)) {
            $value = $this->getValueDay($day,$foundStatisticsMonth) + intval($value);
        } elseif (!$value) {
            $value = $this->getValueDay($day,$foundStatisticsMonth);
            $value++;
        }

        $this->setValueDay($foundStatisticsMonth, $day, $value);            
        $foundStatisticsMonth->totalize();

        //Guardo cambios en el mes (totales)
        $this->adapter->persist($foundStatisticsMonth);        

        //Totalizo el valor del anio con los valores actualizados del mes.
        $foundStatisticsYear->totalize();
        $this->adapter->persist($foundStatisticsYear);
        $this->adapter->flush();
        
        return $foundStatisticsMonth;
    }

    /**
     * Retorna el valor de un dia
     * @param type $day
     * @param type $foundStatistics
     * @return int
     */
    private function getValueYear($day,$foundStatistics = null)
    {
        if($foundStatistics === null){
            return 0;
        }
        $statisticsPropertyPath = "day".$day;
        $value = $this->propertyAccess->getValue($foundStatistics, $statisticsPropertyPath);

        return $value;
    }
    
    /**
     * Retorna el valor de un dia
     * @param type $day
     * @param type $foundStatistics
     * @return int
     */
    private function getValueDay($day,$foundStatistics = null)
    {
        if($foundStatistics === null){
            return 0;
        }
        $statisticsPropertyPath = "day".$day;
        $value = $this->propertyAccess->getValue($foundStatistics, $statisticsPropertyPath);

        return $value;
    }
    /**
     * Establece el valor de un dia
     * @param type $foundStatistics
     * @param type $day
     * @param type $value
     */
    private function setValueDay($foundStatistics,$day,$value)
    {
        $statisticsPropertyPath = "day".$day;
        $this->propertyAccess->setValue($foundStatistics, $statisticsPropertyPath, $value);
    }

    /**
     * Registra una nueva estadistica
     * 
     * @param  String $year
     * @return YearStatistics
     */
    private function newYearStatistics($year = null)
    {
        $now = new \DateTime();
        if($year === null){
            $year = $now->format("Y");
        }

        $yearStatistics = $this->adapter->newYearStatistics($this);
        $yearStatistics->setYear($year);
        $yearStatistics->setCreatedAt($now);
        $yearStatistics->setObject($this->object);
        $yearStatistics->setObjectId($this->objectId);
        $yearStatistics->setObjectType($this->objectType);
        // $yearStatistics->setCreatedFromIp($this->options["current_ip"]);        
        $this->adapter->persist($yearStatistics);        
        for($month = 1; $month <= 12; $month++){
            $statisticsMonth = $this->adapter->newStatisticsMonth($this);
            $statisticsMonth->setMonth($month);
            $statisticsMonth->setYear($year);
            $statisticsMonth->setYearEntity($yearStatistics);
            $statisticsMonth->setCreatedAt($now);
            $statisticsMonth->setObject($this->object);
            $statisticsMonth->setObjectId($this->objectId);
            $statisticsMonth->setObjectType($this->objectType);
            // $statisticsMonth->setCreatedFromIp($this->options["current_ip"]);            
            $yearStatistics->addMonth($statisticsMonth);
            $this->adapter->persist($statisticsMonth);
        }
        $this->adapter->flush();
        
        return $yearStatistics;
    }
    
    /**
     * Retorna el resumen de las estadisticas del anio en un array
     * @param type $year
     * @return YearStatistics
     */
    public function getSummaryYear($year = null)
    {
        $summary = [];
        for($month=1;$month<=12;$month++){
            $summary[$month] = $this->getStatisticsMonthTotal($year, $month);
        }
        
        return $summary;
    }

    /**
     * Agrega un adaptador
     * @param StatisticsAdapterInterface $adapter
     */
    public function addAdapter(Adapter\StatisticsAdapterInterface $adapter,$objectType)
    {
        $this->adapters[$objectType] = $adapter;
    }

    /**
     * Agrega objetos validos por tipo de objeto
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  $objectType
     * @param  array  $objectValids
     */
    public function addObjectValids($objectType,array $objectValids = array())
    {
        $this->objectValids[$objectType] = $objectValids;
    }
}