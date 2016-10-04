<?php

namespace Tecnoready\Common\Model\Statistics\DoctrineORM;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contador mensual enteros
 * @ORM\Table(name="statistics_monthly")
 * @ORM\Entity()
 */
class StatisticsMonth implements \Tecnoready\Common\Model\Statistics\StatisticsMonthInterface
{
    /**
     * @var StatisticsYear
     * @ORM\ManyToOne(targetEntity="Pandco\Bundle\AppBundle\Entity\Core\Statistics\StatisticsYear",inversedBy="months")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $yearEntity;
    
    /**
     * @var integer
     * @ORM\Column(name="year",type="integer", nullable=false)
     */
    protected $year;
    /**
     * Mes
     * @var integer
     * @ORM\Column(name="month",type="integer", nullable=false)
     */
    protected $month;
    /**
     * Total del mes
     * @var integer
     * @ORM\Column(name="total",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $total = 0;
    /**
     * Contador dia 1
     * @var integer
     * @ORM\Column(name="day1",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day1 = 0;
    /**
     * Contador dia 2
     * @var integer
     * @ORM\Column(name="day2",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day2 = 0;
    /**
     * Contador dia 3
     * @var integer
     * @ORM\Column(name="day3",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day3 = 0;
    /**
     * Contador dia 4
     * @var integer
     * @ORM\Column(name="day4",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day4 = 0;
    /**
     * Contador dia 5
     * @var integer
     * @ORM\Column(name="day5",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day5 = 0;
    /**
     * Contador dia 6
     * @var integer
     * @ORM\Column(name="day6",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day6 = 0;
    /**
     * Contador dia 7
     * @var integer
     * @ORM\Column(name="day7",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day7 = 0;
    /**
     * Contador dia 8
     * @var integer
     * @ORM\Column(name="day8",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day8 = 0;
    /**
     * Contador dia 9
     * @var integer
     * @ORM\Column(name="day9",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day9 = 0;
    /**
     * Contador dia 10
     * @var integer
     * @ORM\Column(name="day10",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day10 = 0;
    /**
     * Contador dia 11
     * @var integer
     * @ORM\Column(name="day11",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day11 = 0;
    /**
     * Contador dia 12
     * @var integer
     * @ORM\Column(name="day12",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day12 = 0;
    /**
     * Contador dia 13
     * @var integer
     * @ORM\Column(name="day13",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day13 = 0;
    /**
     * Contador dia 14
     * @var integer
     * @ORM\Column(name="day14",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day14 = 0;
    /**
     * Contador dia 15
     * @var integer
     * @ORM\Column(name="day15",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day15 = 0;
    /**
     * Contador dia 16
     * @var integer
     * @ORM\Column(name="day16",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day16 = 0;
    /**
     * Contador dia 17
     * @var integer
     * @ORM\Column(name="day17",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day17 = 0;
    /**
     * Contador dia 18
     * @var integer
     * @ORM\Column(name="day18",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day18 = 0;
    /**
     * Contador dia 19
     * @var integer
     * @ORM\Column(name="day19",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day19 = 0;
    /**
     * Contador dia 20
     * @var integer
     * @ORM\Column(name="day20",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day20 = 0;
    /**
     * Contador dia 21
     * @var integer
     * @ORM\Column(name="day21",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day21 = 0;
    /**
     * Contador dia 22
     * @var integer
     * @ORM\Column(name="day22",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day22 = 0;
    /**
     * Contador dia 23
     * @var integer
     * @ORM\Column(name="day23",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day23 = 0;
    /**
     * Contador dia 24
     * @var integer
     * @ORM\Column(name="day24",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day24 = 0;
    /**
     * Contador dia 25
     * @var integer
     * @ORM\Column(name="day25",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day25 = 0;
    /**
     * Contador dia 26
     * @var integer
     * @ORM\Column(name="day26",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day26 = 0;
    /**
     * Contador dia 27
     * @var integer
     * @ORM\Column(name="day27",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day27 = 0;
    /**
     * Contador dia 28
     * @var integer
     * @ORM\Column(name="day28",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day28 = 0;
    /**
     * Contador dia 29
     * @var integer
     * @ORM\Column(name="day29",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day29 = 0;
    /**
     * Contador dia 30
     * @var integer
     * @ORM\Column(name="day30",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day30 = 0;
    /**
     * Contador dia 31
     * @var integer
     * @ORM\Column(name="day31",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $day31 = 0;
    
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
    
    use Tecnoready\Common\Model\TraceableTrait;
    
    public function getYear() {
        return $this->year;
    }

    public function getMonth() {
        return $this->month;
    }

    public function getDay1() {
        return $this->day1;
    }

    public function getDay2() {
        return $this->day2;
    }

    public function getDay3() {
        return $this->day3;
    }

    public function getDay4() {
        return $this->day4;
    }

    public function getDay5() {
        return $this->day5;
    }

    public function getDay6() {
        return $this->day6;
    }

    public function getDay7() {
        return $this->day7;
    }

    public function getDay8() {
        return $this->day8;
    }

    public function getDay9() {
        return $this->day9;
    }

    public function getDay10() {
        return $this->day10;
    }

    public function getDay11() {
        return $this->day11;
    }

    public function getDay12() {
        return $this->day12;
    }

    public function getDay13() {
        return $this->day13;
    }

    public function getDay14() {
        return $this->day14;
    }

    public function getDay15() {
        return $this->day15;
    }

    public function getDay16() {
        return $this->day16;
    }

    public function getDay17() {
        return $this->day17;
    }

    public function getDay18() {
        return $this->day18;
    }

    public function getDay19() {
        return $this->day19;
    }

    public function getDay20() {
        return $this->day20;
    }

    public function getDay21() {
        return $this->day21;
    }

    public function getDay22() {
        return $this->day22;
    }

    public function getDay23() {
        return $this->day23;
    }

    public function getDay24() {
        return $this->day24;
    }

    public function getDay25() {
        return $this->day25;
    }

    public function getDay26() {
        return $this->day26;
    }

    public function getDay27() {
        return $this->day27;
    }

    public function getDay28() {
        return $this->day28;
    }

    public function getDay29() {
        return $this->day29;
    }

    public function getDay30() {
        return $this->day30;
    }

    public function getDay31() {
        return $this->day31;
    }

    public function setYear($year) {
        $this->year = $year;
        return $this;
    }

    public function setMonth($month) {
        $this->month = $month;
        return $this;
    }

    public function setDay1($day1) {
        $this->day1 = $day1;
        return $this;
    }

    public function setDay2($day2) {
        $this->day2 = $day2;
        return $this;
    }

    public function setDay3($day3) {
        $this->day3 = $day3;
        return $this;
    }

    public function setDay4($day4) {
        $this->day4 = $day4;
        return $this;
    }

    public function setDay5($day5) {
        $this->day5 = $day5;
        return $this;
    }

    public function setDay6($day6) {
        $this->day6 = $day6;
        return $this;
    }

    public function setDay7($day7) {
        $this->day7 = $day7;
        return $this;
    }

    public function setDay8($day8) {
        $this->day8 = $day8;
        return $this;
    }

    public function setDay9($day9) {
        $this->day9 = $day9;
        return $this;
    }

    public function setDay10($day10) {
        $this->day10 = $day10;
        return $this;
    }

    public function setDay11($day11) {
        $this->day11 = $day11;
        return $this;
    }

    public function setDay12($day12) {
        $this->day12 = $day12;
        return $this;
    }

    public function setDay13($day13) {
        $this->day13 = $day13;
        return $this;
    }

    public function setDay14($day14) {
        $this->day14 = $day14;
        return $this;
    }

    public function setDay15($day15) {
        $this->day15 = $day15;
        return $this;
    }

    public function setDay16($day16) {
        $this->day16 = $day16;
        return $this;
    }

    public function setDay17($day17) {
        $this->day17 = $day17;
        return $this;
    }

    public function setDay18($day18) {
        $this->day18 = $day18;
        return $this;
    }

    public function setDay19($day19) {
        $this->day19 = $day19;
        return $this;
    }

    public function setDay20($day20) {
        $this->day20 = $day20;
        return $this;
    }

    public function setDay21($day21) {
        $this->day21 = $day21;
        return $this;
    }

    public function setDay22($day22) {
        $this->day22 = $day22;
        return $this;
    }

    public function setDay23($day23) {
        $this->day23 = $day23;
        return $this;
    }

    public function setDay24($day24) {
        $this->day24 = $day24;
        return $this;
    }

    public function setDay25($day25) {
        $this->day25 = $day25;
        return $this;
    }

    public function setDay26($day26) {
        $this->day26 = $day26;
        return $this;
    }

    public function setDay27($day27) {
        $this->day27 = $day27;
        return $this;
    }

    public function setDay28($day28) {
        $this->day28 = $day28;
        return $this;
    }

    public function setDay29($day29) {
        $this->day29 = $day29;
        return $this;
    }

    public function setDay30($day30) {
        $this->day30 = $day30;
        return $this;
    }

    public function setDay31($day31) {
        $this->day31 = $day31;
        return $this;
    }
    
    public function getTotal() {
        return $this->total;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function totalize()
    {
        $reflection = new \ReflectionClass($this);
        $methods = $reflection->getMethods();
        
        $nameMatchReal = '^getDay\w+$';
        
        $total = 0.0;
        foreach ($methods as $method) {
            $methodName = $method->getName();
//            $class = $method->getDeclaringClass();
           
            if(preg_match('/'.$nameMatchReal.'/i', $methodName)){
//                var_dump($this->$methodName());
                $total += $this->$methodName();
            }
        }
        $this->total = $total;
//        var_dump($total);
//        var_dump(__FILE__);
//        die;
    }
    
    public function getYearEntity() {
        return $this->yearEntity;
    }

    public function setYearEntity(StatisticsYear $yearEntity) {
        $this->yearEntity = $yearEntity;
        return $this;
    }
}
