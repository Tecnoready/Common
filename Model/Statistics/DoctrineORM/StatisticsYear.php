<?php

namespace Tecnoready\Common\Model\Statistics\DoctrineORM;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estadistica de un año
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class StatisticsYear implements \Tecnoready\Common\Model\Statistics\StatisticsYearInterface
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
     * Meses
     * @var StatisticsMonth
     * @ORM\OneToMany(targetEntity="Pandco\Bundle\AppBundle\Entity\Core\Statistics\StatisticsMonth",cascade={"persist","remove"},mappedBy="yearEntity")
     */
    protected $months;
    
    /**
     * @var \DateTime $created
     *
     * @ORM\Column(name="created_at",type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime $updated
     *
     * @ORM\Column(name="updated_at",type="datetime")
     */
    protected $updatedAt;
    
    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;
    
    /**
     * @var string $createdFromIp
     *
     * @ORM\Column(type="string", name="created_from_ip",length=45, nullable=true)
     */
    protected $createdFromIp;
    
    /**
     * @var string $updatedFromIp
     *
     * @ORM\Column(type="string", name="updated_from_ip",length=45, nullable=true)
     */
    protected $updatedFromIp;
    
    use \Tecnoready\Common\Model\TraceableTrait;
    
    public function __construct() {
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

    /**
     * Add month
     *
     * @param \Pandco\Bundle\AppBundle\Entity\Core\Statistics\StatisticsMonth $month
     *
     * @return StatisticsYear
     */
    public function addMonth(\Pandco\Bundle\AppBundle\Entity\Core\Statistics\StatisticsMonth $month)
    {
        $this->months->set($month->getMonth(),$month);

        return $this;
    }

    /**
     * Remove month
     *
     * @param \Pandco\Bundle\AppBundle\Entity\Core\Statistics\StatisticsMonth $month
     */
    public function removeMonth(\Pandco\Bundle\AppBundle\Entity\Core\Statistics\StatisticsMonth $month)
    {
        $this->months->removeElement($month);
    }

    /**
     * Get months
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMonths()
    {
        return $this->months;
    }
    
    public function getMonth($month)
    {
        $month = (int)$month;
        $found = null;
        foreach ($this->getMonths() as $value) {
            if($value->getMonth() === $month){
                $found = $value;
                break;
            }
        }
        return $found;
    }
    
    public function totalize() {
        $total = 0.0;
        foreach ($this->getMonths() as $month) {
                $month->totalize();
                $totalMonth = $month->getTotal();
                $setTotalMonth = sprintf("setTotalMonth%s",$month->getMonth());
                $this->$setTotalMonth($totalMonth);
//                var_dump("setTotalMonth ".$totalMonth);
                $total = $total + $totalMonth;
        }
        $this->total = $total;
//        var_dump($total);
//        die;
    }
}
