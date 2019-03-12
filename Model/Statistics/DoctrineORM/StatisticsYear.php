<?php

namespace Tecnoready\Common\Model\Statistics\DoctrineORM;

use Doctrine\ORM\Mapping as ORM;
use Tecnoready\Common\Model\Statistics\StatisticsMonthInterface;
use Tecnoready\Common\Model\Statistics\StatisticsYearInterface;

/**
 * Estadistica de un año
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class StatisticsYear implements StatisticsYearInterface
{
    /**
     * @var integer
     * @ORM\Column(name="year",type="integer", nullable=false)
     */
    protected $year;
    
    /**
     * Total de todos los meses
     * @var integer
     * @ORM\Column(name="total",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $total = 0;
    
    /**
     * Total del mes
     * @var integer
     * @ORM\Column(name="total_month_1",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $totalMonth1 = 0;
    
    /**
     * Total del mes
     * @var integer
     * @ORM\Column(name="total_month_2",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $totalMonth2 = 0;
        
    /**
     * Total del mes
     * @var integer
     * @ORM\Column(name="total_month_3",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $totalMonth3 = 0;
        
    /**
     * Total del mes
     * @var integer
     * @ORM\Column(name="total_month_4",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $totalMonth4 = 0;
        
    /**
     * Total del mes
     * @var integer
     * @ORM\Column(name="total_month_5",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $totalMonth5 = 0;
        
    /**
     * Total del mes
     * @var integer
     * @ORM\Column(name="total_month_6",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $totalMonth6 = 0;
        
    /**
     * Total del mes
     * @var integer
     * @ORM\Column(name="total_month_7",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $totalMonth7 = 0;
        
    /**
     * Total del mes
     * @var integer
     * @ORM\Column(name="total_month_8",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $totalMonth8 = 0;
        
    /**
     * Total del mes
     * @var integer
     * @ORM\Column(name="total_month_9",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $totalMonth9 = 0;
        
    /**
     * Total del mes
     * @var integer
     * @ORM\Column(name="total_month_10",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $totalMonth10 = 0;
        
    /**
     * Total del mes
     * @var integer
     * @ORM\Column(name="total_month_11",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $totalMonth11 = 0;
        
    /**
     * Total del mes
     * @var integer
     * @ORM\Column(name="total_month_12",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $totalMonth12 = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string",length=100)
     */
    protected $object;

    use \Tecnoready\Common\Model\ObjectManager\Base\TraitBaseORM;

    public function __construct() 
    {
        $this->months = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return StatisticsYear
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set total
     *
     * @param string $total
     *
     * @return StatisticsYear
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set totalMonth1
     *
     * @param string $totalMonth1
     *
     * @return StatisticsYear
     */
    public function setTotalMonth1($totalMonth1)
    {
        $this->totalMonth1 = $totalMonth1;

        return $this;
    }

    /**
     * Get totalMonth1
     *
     * @return string
     */
    public function getTotalMonth1()
    {
        return $this->totalMonth1;
    }

    /**
     * Set totalMonth2
     *
     * @param string $totalMonth2
     *
     * @return StatisticsYear
     */
    public function setTotalMonth2($totalMonth2)
    {
        $this->totalMonth2 = $totalMonth2;

        return $this;
    }

    /**
     * Get totalMonth2
     *
     * @return string
     */
    public function getTotalMonth2()
    {
        return $this->totalMonth2;
    }

    /**
     * Set totalMonth3
     *
     * @param string $totalMonth3
     *
     * @return StatisticsYear
     */
    public function setTotalMonth3($totalMonth3)
    {
        $this->totalMonth3 = $totalMonth3;

        return $this;
    }

    /**
     * Get totalMonth3
     *
     * @return string
     */
    public function getTotalMonth3()
    {
        return $this->totalMonth3;
    }

    /**
     * Set totalMonth4
     *
     * @param string $totalMonth4
     *
     * @return StatisticsYear
     */
    public function setTotalMonth4($totalMonth4)
    {
        $this->totalMonth4 = $totalMonth4;

        return $this;
    }

    /**
     * Get totalMonth4
     *
     * @return string
     */
    public function getTotalMonth4()
    {
        return $this->totalMonth4;
    }

    /**
     * Set totalMonth5
     *
     * @param string $totalMonth5
     *
     * @return StatisticsYear
     */
    public function setTotalMonth5($totalMonth5)
    {
        $this->totalMonth5 = $totalMonth5;

        return $this;
    }

    /**
     * Get totalMonth5
     *
     * @return string
     */
    public function getTotalMonth5()
    {
        return $this->totalMonth5;
    }

    /**
     * Set totalMonth6
     *
     * @param string $totalMonth6
     *
     * @return StatisticsYear
     */
    public function setTotalMonth6($totalMonth6)
    {
        $this->totalMonth6 = $totalMonth6;

        return $this;
    }

    /**
     * Get totalMonth6
     *
     * @return string
     */
    public function getTotalMonth6()
    {
        return $this->totalMonth6;
    }

    /**
     * Set totalMonth7
     *
     * @param string $totalMonth7
     *
     * @return StatisticsYear
     */
    public function setTotalMonth7($totalMonth7)
    {
        $this->totalMonth7 = $totalMonth7;

        return $this;
    }

    /**
     * Get totalMonth7
     *
     * @return string
     */
    public function getTotalMonth7()
    {
        return $this->totalMonth7;
    }

    /**
     * Set totalMonth8
     *
     * @param string $totalMonth8
     *
     * @return StatisticsYear
     */
    public function setTotalMonth8($totalMonth8)
    {
        $this->totalMonth8 = $totalMonth8;

        return $this;
    }

    /**
     * Get totalMonth8
     *
     * @return string
     */
    public function getTotalMonth8()
    {
        return $this->totalMonth8;
    }

    /**
     * Set totalMonth9
     *
     * @param string $totalMonth9
     *
     * @return StatisticsYear
     */
    public function setTotalMonth9($totalMonth9)
    {
        $this->totalMonth9 = $totalMonth9;

        return $this;
    }

    /**
     * Get totalMonth9
     *
     * @return string
     */
    public function getTotalMonth9()
    {
        return $this->totalMonth9;
    }

    /**
     * Set totalMonth10
     *
     * @param string $totalMonth10
     *
     * @return StatisticsYear
     */
    public function setTotalMonth10($totalMonth10)
    {
        $this->totalMonth10 = $totalMonth10;

        return $this;
    }

    /**
     * Get totalMonth10
     *
     * @return string
     */
    public function getTotalMonth10()
    {
        return $this->totalMonth10;
    }

    /**
     * Set totalMonth11
     *
     * @param string $totalMonth11
     *
     * @return StatisticsYear
     */
    public function setTotalMonth11($totalMonth11)
    {
        $this->totalMonth11 = $totalMonth11;

        return $this;
    }

    /**
     * Get totalMonth11
     *
     * @return string
     */
    public function getTotalMonth11()
    {
        return $this->totalMonth11;
    }

    /**
     * Set totalMonth12
     *
     * @param string $totalMonth12
     *
     * @return StatisticsYear
     */
    public function setTotalMonth12($totalMonth12)
    {
        $this->totalMonth12 = $totalMonth12;

        return $this;
    }

    /**
     * Get totalMonth12
     *
     * @return string
     */
    public function getTotalMonth12()
    {
        return $this->totalMonth12;
    }

    public function getObject()
    {
        return $this->object;
    }
    
    public function setObject($object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * Add month
     *
     * @param StatisticsMonthInterface $month
     *
     * @return StatisticsYearInterface
     */
    public function addMonth(StatisticsMonthInterface $month){}

    /**
     * Remove month
     *
     * @param StatisticsMonthInterface $month
     */
    public function removeMonth(StatisticsMonthInterface $month){}

    /**
     * Get months
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMonths(){}
    
    public function getMonth($month){}
    
    public function totalize(){}

    public function setUser($user){}
    
    public function getUser(){}
}
