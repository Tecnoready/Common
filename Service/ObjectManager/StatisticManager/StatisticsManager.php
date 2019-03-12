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

/**
 * Manejador de estadisticas
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class StatisticsManager
{
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor 
     */
    private $propertyAccess;
    /**
     *
     * @var Adapter\StatisticsAdapterInterface
     */
    private $adapter;

    /**
     * @var array
     */
    protected $options = array();
    
    public function __construct(Adapter\StatisticsAdapterInterface $adapter,array $options = []) 
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
        $this->adapter = $adapter;
        
        $this->setOptions($options);
    }
    
    public function setOptions(array $options)
    {
        $resolver = new \Symfony\Component\OptionsResolver\OptionsResolver();
        $resolver->setDefaults([
            'date_format' => 'Y-m-d H:i:s',
        ]);
        
        // $resolver->setRequired(["current_ip","date_format"]);
        // $resolver->addAllowedTypes("current_ip","string");
        // $resolver->addAllowedTypes("date_format","string");
        
        $this->options = $resolver->resolve($options);
    }
    
    /**
     * Retorna las estadisticas de un mes especifico por año y dia
     * @param type $object
     * @param type $propertyPath
     * @param type $year
     * @param type $month
     * @param type $day
     * @return type
     */
    public function getStatisticsMonthValue($object,$propertyPath,$year = null,$month = null,$day= null)
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
        $foundStatistics = $this->findStatisticsMonth($object, $propertyPath, $year, $month);
        return (int)$this->getValueDay($day,$foundStatistics);
    }
    
    public function getStatisticsMonthTotal($object,$propertyPath,$year = null,$month = null) {
        $now = new \DateTime();
        if($year === null){
            $year = (int)$now->format("Y");
        }
        if($month === null){
            $month = (int)$now->format("m");
        }
        $foundStatistics = $this->findStatisticsMonth($object, $propertyPath, $year, $month);
        $total = 0;
        if($foundStatistics !== null){
            $total = $foundStatistics->getTotal();
        }
        return $total;
    }
    
    public function getStatisticsYearValue($object,$propertyPath,$year = null)
    {
        $now = new \DateTime();
        if($year === null){
            $year = (int)$now->format("Y");
        }
        $foundStatistics = $this->findStatisticsYear($object, $propertyPath, $year);
        $total = 0;
        if($foundStatistics){
            $total = $foundStatistics->getTotal();
        }
        return $total;
    }
    
    /**
     * Retorna las estadisticas de un mes por el año
     * @param type $object
     * @param type $propertyPath
     * @param type $year
     * @param type $month
     * @return type
     */
    public function findStatisticsMonth($object,$propertyPath,$year,$month) 
    {
        $foundStatisticsYear = $this->findStatisticsYear($object, $propertyPath, $year);
        $foundStatistics = null;
        if($foundStatisticsYear !== null){
            $foundStatistics = $foundStatisticsYear->getMonth($month);
        }
        return $foundStatistics;
    }
    
    /**
     * Retorna las estadisticas de un año
     * @param type $object
     * @param type $propertyPath
     * @param type $year
     * @param type $month
     * @return type
     */
    public function findStatisticsYear($object,$year) 
    {
        $year = (int)$year;
        $foundStatistics = $this->adapter->getEntityManager()->getRepository(\Pandco\Bundle\OMBundle\Entity\Statistics\StatisticsYear::class)->findOneBy(["object" => $object, "year" => $year]);
        // $statistics = $this->propertyAccess->getValue($object, $propertyPath);
        if (!$foundStatistics) {
            $foundStatistics = null;
        }
        // foreach ($statistics as $statistic) {
        //     if($statistic->getYear() === $year){
        //         $foundStatistics = $statistic;
        //         break;
        //     }
        // }
        return $foundStatistics;
    }

    /**
     * Cuenta uno a las estadisticas de un objeto por el año, mes y dia
     * @param type $object
     * @param type $propertyPath
     * @param type $year
     * @param type $month
     * @param type $day
     * @return type
     */
    public function countStatisticsMonth($object,$propertyPath,$year = null,$month = null,$day= null)
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
        
        $foundStatisticsYear = $this->findStatisticsYear($object, $year);
        // var_dump($foundStatisticsYear);
        // die();
        if($foundStatisticsYear === null){
            $foundStatisticsYear = $this->newYearStatistics($object,$year);
            $this->adapter->persist($foundStatisticsYear);
        }
        $foundStatisticsMonth = $foundStatisticsYear->getMonth($month);
        
        $value = (int)$this->getValueDay($day,$foundStatisticsMonth);
        $value++;
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
     * Crea una nueva estadistica
     */
    private function newYearStatistics($object, $year = null)
    {
        $now = new \DateTime();
        if($year === null){
            $year = $now->format("Y");
        }

        // $nowString = $now->format($this->options["date_format"]);
        $nowString = $now;
        $yearStatistics = $this->adapter->newYearStatistics($this);
        $yearStatistics->setYear($year);
        $yearStatistics->setCreatedAt($nowString);
        $yearStatistics->setObject($object);
        $yearStatistics->setObjectId("test");
        $yearStatistics->setObjectType("test");
        // $yearStatistics->setCreatedFromIp($this->options["current_ip"]);
        
        $this->adapter->persist($yearStatistics);
        
        for($month = 1; $month <= 12; $month++){
            $statisticsMonth = $this->adapter->newStatisticsMonth($this);
            $statisticsMonth->setMonth($month);
            $statisticsMonth->setYear($year);
            $statisticsMonth->setYearEntity($yearStatistics);
            $statisticsMonth->setCreatedAt($nowString);
            $statisticsMonth->setObject($object);
            $statisticsMonth->setObjectId("test");
            $statisticsMonth->setObjectType("test");
            // $statisticsMonth->setCreatedFromIp($this->options["current_ip"]);
            
            $yearStatistics->addMonth($statisticsMonth);
            $this->adapter->persist($statisticsMonth);
        }
        $this->adapter->flush();
        
        return $yearStatistics;
    }
    
    /**
     * Retorna el resumen de las estadisticas del anio en un array
     * @param type $object
     * @param type $propertyPath
     * @param type $year
     * @return type
     */
    public function getSummaryYear($object,$propertyPath,$year = null)
    {
        $summary = [];
        for($month=1;$month<=12;$month++){
            $summary[$month] = $this->getStatisticsMonthTotal($object, $propertyPath, $year, $month);
        }
        return $summary;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
}