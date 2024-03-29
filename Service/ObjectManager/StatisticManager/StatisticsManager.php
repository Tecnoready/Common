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

use DateTime;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Tecnoready\Common\Service\ObjectManager\ConfigureInterface;
use Tecnoready\Common\Service\ObjectManager\StatisticManager\Adapter\StatisticsAdapterInterface;
use Tecnoready\Common\Model\Statistics\StatisticsMonthInterface;

/**
 * Manejador de estadisticas para el manejador de objetos (ObjectManager)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class StatisticsManager implements ConfigureInterface
{

    use \Tecnoready\Common\Service\ObjectManager\TraitConfigure;

    /**
     * @var PropertyAccessor 
     */
    private $propertyAccess;

    /**
     * @var StatisticsAdapterInterface
     */
    private $adapter;

    /**
     * Opciones del estadist manager
     * @var array
     */
    protected $options;
    
    /**
     * Opciones por defecto (se hace merge con las otras opciones)
     * @var array
     */
    private $defaultOptions;

    /**
     * Adaptadores disponibles
     * @var Adapters
     */
    private $adapters;

    /**
     * Adaptador por defecto
     * @var StatisticsAdapterInterface
     */
    private $defaultAdapter;

    /**
     * Objetos validos
     * @var objectValids
     */
    private $objectValids;

    public function __construct(StatisticsAdapterInterface $adapter = null,array $defaultOptions = [])
    {
        if (!class_exists("Symfony\Component\PropertyAccess\PropertyAccess")) {
            throw new \Exception(sprintf("The package '%s' is required, please install https://packagist.org/packages/symfony/property-access", '"symfony/property-access": "^3.1"'));
        }
        if (!class_exists("Symfony\Component\OptionsResolver\OptionsResolver")) {
            throw new \Exception(sprintf("The package '%s' is required, please install https://packagist.org/packages/symfony/options-resolver", '"symfony/options-resolver": "^3.1"'));
        }
        $builder = PropertyAccess::createPropertyAccessorBuilder();
        $builder->enableMagicCall();

        $this->propertyAccess = $builder->getPropertyAccessor();
        $this->defaultAdapter = $adapter;
        $this->objectValids = [];
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * Registro de configuraciones
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @author Carlos Mendoza <inhack20@gmail.com>
     * @param  $objectId
     * @param  $objectType
     * @throws RuntimeException
     * @return StatisticsManager
     */
    public function configure($objectId, $objectType, array $options = [])
    {
        $this->adapter = $this->defaultAdapter;
        if (isset($this->adapters[$objectType])) {
            $this->adapter = $this->adapters[$objectType]["adapter"];
        }
        if ($this->adapter === null) {
            throw new RuntimeException(sprintf("No hay ningun adaptador configurado para '%s' en '%s' debe agregar por lo menos uno.", $objectType, StatisticsManager::class));
        }

        $this->objectId = $objectId;
        $this->objectType = $objectType;

        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "object" => null,
            //Mejora: para evitar pasar la opcion "current_ip" en cada llamada de "configure"
            "current_ip" => isset($this->defaultOptions["current_ip"]) ? $this->defaultOptions["current_ip"] : null,
            "extras" => [],
        ]);
        $resolver->setAllowedTypes("extras", "array");
        $this->options = $resolver->resolve($options);
        
        $this->options = array_merge($this->defaultOptions,$this->options);
        
        $this->setObject($this->options["object"]);
        $this->adapter->configure($objectId, $objectType);
        $instance = $this;
        return clone $instance;
    }

    /**
     * @deprecated Usar getTotalDay
     */
    public function getStatisticsMonthValue($year = null, $month = null, $day = null)
    {
        return $this->getTotalDay([
                    "year" => $year,
                    "month" => $month,
                    "day" => $day,
        ]);
    }

    /**
     * @deprecated usar getStatisticsMonthTotal
     */
    public function getStatisticsMonthTotal($year = null, $month = null)
    {
        return $this->getTotalMonth([
                    "year" => $year,
                    "month" => $month,
        ]);
    }

    /**
     * @deprecated Usar getStatisticsYearValue
     */
    public function getStatisticsYearValue($year = null)
    {
        return $this->getTotalYear([
                    "year" => $year,
        ]);
    }

    /**
     * Retorna las estadisticas de un día especifico por año,mes y dia.
     * Antiguo: getStatisticsMonthValue
     * @param array $options [year,month,day]
     * @return StatisticsMonthValue
     */
    public function getTotalDay(array $options = [])
    {
        $options = $this->parseOldValues($options);
        $resolver = new OptionsResolver();
        $now = new DateTime();
        $defaults = [
            "year" => (int) $now->format("Y"),
            "month" => (int) $now->format("m"),
            "day" => (int) $now->format("d"),
        ];
        $resolver->setDefaults($defaults);
        foreach ($defaults as $option => $value) {
            $resolver->setAllowedTypes($option, "int");
        }
        $options = $resolver->resolve($options);

        $foundStatistics = $this->findStatisticsMonth([
            "year" => $options["year"],
            "month" => $options["month"],
        ]);
        return (double) $this->getValueDay($options["day"], $foundStatistics);
    }

    /**
     * Busca el total de un mes.
     * Antiguo: getStatisticsMonthTotal
     * @param array $options [year,month]
     * @return int
     */
    public function getTotalMonth(array $options = [])
    {
        $options = $this->parseOldValues($options);
        $resolver = new OptionsResolver();
        $now = new DateTime();
        $defaults = [
            "year" => (int) $now->format("Y"),
            "month" => (int) $now->format("m"),
        ];
        $resolver->setDefaults($defaults);
        foreach ($defaults as $option => $value) {
            $resolver->setAllowedTypes($option, "int");
        }
        $options = $resolver->resolve($options);

        $foundStatistics = $this->findStatisticsMonth([
            "year" => $options["year"],
            "month" => $options["month"],
        ]);
        $total = 0;
        if ($foundStatistics !== null) {
            $total = $foundStatistics->getTotal();
        }

        return $total;
    }

    /**
     * Busca el total de un año
     * Antiguo: getStatisticsYearValue
     * @param array $options [year]
     * @return int
     */
    public function getTotalYear(array $options = [])
    {
        $options = $this->parseOldValues($options);
        $resolver = new OptionsResolver();
        $now = new DateTime();
        $defaults = [
            "year" => (int) $now->format("Y"),
        ];
        $resolver->setDefaults($defaults);
        foreach ($defaults as $option => $value) {
            $resolver->setAllowedTypes($option, "int");
        }
        $options = $resolver->resolve($options);

        $foundStatistics = $this->findStatisticsYear($options["year"]);
        $total = 0;
        if ($foundStatistics) {
            $total = $foundStatistics->getTotal();
        }

        return $total;
    }

    /**
     * Retorna el resumen de las estadisticas del año en un array
     * @param type $year
     * @return YearStatistics
     */
    public function getSummaryYear($year = null)
    {
        $summary = [];
        for ($month = 1; $month <= 12; $month++) {
            $summary[$month] = $this->getStatisticsMonthTotal($year, $month);
        }

        return $summary;
    }

    /**
     * Obtiene un resumen del mes
     * @param array $options
     * @return array
     */
    public function getSummaryMonth(array $options = [])
    {
        $now = new DateTime();
        $resolver = new OptionsResolver();
        $defaults = [
            "year" => (int) $now->format("Y"),
            "month" => (int) $now->format("m"),
        ];
        $resolver->setDefaults($defaults);
        foreach ($defaults as $option => $value) {
            $resolver->setAllowedTypes($option, "int");
        }
        $options = $resolver->resolve($options);

        $summary = [];

        $days = cal_days_in_month(CAL_GREGORIAN, $options["month"], $options["year"]);

        for ($day = 1; $day <= $days; $day++) {
            $summary[$day] = $this->getTotalDay([
                "year" => $options["year"],
                "month" => $options["month"],
                "day" => $day,
            ]);
        }
        return $summary;
    }

    /**
     * Retorna las estadisticas de un mes por el año
     * @param array $options [year,month]
     * @return StatisticsMonthInterface
     */
    private function findStatisticsMonth(array $options = [])
    {
        $resolver = new OptionsResolver();
        $now = new DateTime();
        $defaults = [
            "year" => (int) $now->format("Y"),
            "month" => (int) $now->format("m"),
        ];
        $resolver->setDefaults($defaults);
        foreach ($defaults as $option => $value) {
            $resolver->setAllowedTypes($option, "int");
        }
        $options = $resolver->resolve($options);

        $foundStatisticsYear = $this->findStatisticsYear($options["year"]);
        $foundStatistics = null;
        if ($foundStatisticsYear !== null) {
            $foundStatistics = $foundStatisticsYear->getMonth($options["month"]);
        }

        return $foundStatistics;
    }

    /**
     * Retorna las estadisticas de un año
     * @param type $year
     * @param type $month
     * @return \Tecnoready\Common\Model\Statistics\StatisticsYearInterface
     */
    private function findStatisticsYear($year)
    {
        $year = (int) $year;
        $foundStatistics = $this->adapter->findStatisticsYear([
            "object" => $this->options["object"],
            "objectId" => $this->objectId,
            "objectType" => $this->objectType,
            "year" => $year,
            "extras" => $this->options["extras"],
        ]);
        if (!$foundStatistics) {
            $foundStatistics = null;
        }

        return $foundStatistics;
    }

    /**
     * Cuenta uno a las estadisticas de un objeto por el año, mes y dia<br/>
     * <b>$value: Puede incrementar +20, restar -15 o colocar un valor fijo 5</b>
     * @param array $options [year,month,day,value] 
     * @return \Tecnoready\Common\Model\Statistics\StatisticsMonthInterface
     */
    public function countStatisticsMonth(array $options = [])
    {
        $resolver = new OptionsResolver();
        $now = new DateTime();
        $defaults = [
            "year" => (int) $now->format("Y"),
            "month" => (int) $now->format("m"),
            "day" => (int) $now->format("d"),
            "value" => null,
            "mode" => null,
        ];
        $resolver->setDefaults($defaults);
        foreach ($defaults as $option => $v) {
            if (in_array($option, ["$option"])) {
                $resolver->setAllowedTypes($option, ["int", "string", "null", "double"]);
                continue;
            }
            $resolver->setAllowedTypes($option, "int");
        }
        $resolver->setAllowedValues("mode", ["cumulative", "set", "count", null]);
        $options = $resolver->resolve($options);

        // Consulta de estadistica año
        $foundStatisticsYear = $this->findStatisticsYear($options["year"]);
        if ($foundStatisticsYear === null) {
            $foundStatisticsYear = $this->newYearStatistics($options["year"]);
            $this->adapter->persist($foundStatisticsYear);
        }
        $foundStatisticsMonth = $foundStatisticsYear->getMonth($options["month"]);

        $value = $options["value"];
        if ($options["mode"] === "cumulative") {
            // || ($value && is_string($value)) Corrigue error cuando se pasaba un monto string y mode "set"
            $value = doubleval($value);
            $value = $this->getValueDay($options["day"], $foundStatisticsMonth) + doubleval($value);
        } elseif ($options["mode"] === "count" || $value === null) {
            //Se quiere contar
            $value = $this->getValueDay($options["day"], $foundStatisticsMonth);
            $value++;
        } elseif ($options["mode"] === "set") {
            //Se establece el valor directo
            $value = $options["value"];
        }

        $this->setValueDay($foundStatisticsMonth, $options["day"], $value);
        $foundStatisticsMonth->totalize();

        //Guardo cambios en el mes (totales)
        $this->adapter->persist($foundStatisticsMonth);

        //Totalizo el valor del anio con los valores actualizados del mes.
        $foundStatisticsYear->totalize();
        $this->adapter->persist($foundStatisticsYear);
        $this->adapter->flush();
        
        $foundStatisticsYear = null;//clean memory
        return $foundStatisticsMonth;
    }

    /**
     * Retorna el valor de un dia
     * @param type $day
     * @param type $foundStatistics
     * @return int
     */
    private function getValueDay($day, $foundStatistics = null)
    {
        if ($foundStatistics === null) {
            return 0;
        }
        $statisticsPropertyPath = "day" . $day;
        $value = $this->propertyAccess->getValue($foundStatistics, $statisticsPropertyPath);
//        var_dump($foundStatistics->getId());
//        var_dump($value);
//        var_dump($statisticsPropertyPath);
//        die;
        return $value;
    }

    /**
     * Establece el valor de un dia
     * @param type $foundStatistics
     * @param type $day
     * @param type $value
     */
    private function setValueDay($foundStatistics, $day, $value)
    {
        $statisticsPropertyPath = "day" . $day;
        if($foundStatistics === null){
            throw new InvalidArgumentException(sprintf("El objeto de la propiedad '%s' no puede ser null.",$statisticsPropertyPath));
        }
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
        $now = new DateTime();
        if ($year === null) {
            $year = $now->format("Y");
        }
        $adapterConfig = $this->adapters[$this->objectType];
        
        $yearStatistics = $this->adapter->newYearStatistics($this,$this->options);
        $yearStatistics->setYear($year);
        $yearStatistics->setCreatedAt($now);
        $yearStatistics->setObject($this->options["object"]);
        $yearStatistics->setObjectId($this->objectId);
        $yearStatistics->setObjectType($this->objectType);
        $yearStatistics->setCreatedFromIp($this->options["current_ip"]);
        if(is_callable($adapterConfig["post_new_year_statistics_callback"])){
            call_user_func_array($adapterConfig["post_new_year_statistics_callback"],[&$yearStatistics,$this->options]);
        }
        $this->adapter->persist($yearStatistics);
        for ($month = 1; $month <= 12; $month++) {
            $statisticsMonth = $this->adapter->newStatisticsMonth($this,$this->options);
            $statisticsMonth->setMonth($month);
            $statisticsMonth->setYear($year);
            $statisticsMonth->setYearEntity($yearStatistics);
            $statisticsMonth->setCreatedAt($now);
            $statisticsMonth->setObject($this->options["object"]);
            $statisticsMonth->setObjectId($this->objectId);
            $statisticsMonth->setObjectType($this->objectType);
            $statisticsMonth->setCreatedFromIp($this->options["current_ip"]);
            $yearStatistics->addMonth($statisticsMonth);
            if(is_callable($adapterConfig["post_new_statistics_month_callback"])){
                call_user_func_array($adapterConfig["post_new_statistics_month_callback"],[&$statisticsMonth,$this->options]);
            }
            $this->adapter->persist($statisticsMonth);
        }
        $this->adapter->flush();

        return $yearStatistics;
    }

    /**
     * Agrega un adaptador
     * @param StatisticsAdapterInterface $adapter
     */
    public function addAdapter(StatisticsAdapterInterface $adapter, $objectType,array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "adapter" => $adapter,
            "post_new_year_statistics_callback" => null,//Llamada de callback a realizar despues de crear la instancia
            "post_new_statistics_month_callback" => null,//Llamada de callback a realizar despues de crear la instancia
        ]);
        //ObjectValids
        $this->adapters[$objectType] = $resolver->resolve($options);

        return $this;
    }

    /**
     * Agrega objetos validos por tipo de objeto
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  $objectType
     * @param  array  $objectValids
     */
    public function addObjectValids($objectType, array $objectValids = array())
    {
        $this->objectValids[$objectType] = $objectValids;

        return $this;
    }

    /**
     * Registro de objeto a usar en las llamadas futuras
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @author Carlos Mendoza <inhack20@gmail.com>
     * @param  String $object
     */
    public function setObject($object = null)
    {
        if (count($this->objectValids) == 0 || ($this->options["object"] !== null && (!isset($this->objectValids[$this->objectType]) || !in_array($this->options["object"], $this->objectValids[$this->objectType])) )) {
            throw new InvalidArgumentException(sprintf("The object '%s' not add in object type '%s', please add. Available are %s", $this->options["object"], $this->objectType, implode(",", $this->objectValids[$this->objectType] ?? [] )));
        }
        $this->options["object"] = $object;
        return $this;
    }

    /**
     * Compatibilidad con lo viejo de getStatisticsMonthTotal
     * @param array $options
     * @return array
     */
    private function parseOldValues(array $options = [])
    {
        //Compatibilidad con lo viejo de getStatisticsMonthTotal
        foreach ($options as $key => $value) {
            if ($value === null) {
                unset($options[$key]);
            }
        }
        return $options;
    }
    
    /**
     * Opciones por si se necesita acceder desde los adaptadores (caso laravel)
     * @return array
     */
    public function getOptions() {
        return $this->options;
    }
    
    /**
     * Agregar una opcion por defecto
     * @param type $option
     * @param type $value
     * @return $this
     */
    public function setDefaultOption($option,$value) {
        $this->defaultOptions[$option] = $value;
        return $this;
    }



}
