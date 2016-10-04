<?php

namespace Tecnoready\Common\Model\Configuration\Statistics\Yii2ActiveRecord;

use Yii;

/**
 * Contador mensual enteros
 * This is the model class for table "statistics_monthly".
 * @author Carlos Mendoza <inhack20@gmail.com>
 * 
 * @property integer $id
 * @property integer $year
 * @property integer $month
 * @property string $total
 * @property string $day1
 * @property string $day2
 * @property string $day3
 * @property string $day4
 * @property string $day5
 * @property string $day6
 * @property string $day7
 * @property string $day8
 * @property string $day9
 * @property string $day10
 * @property string $day11
 * @property string $day12
 * @property string $day13
 * @property string $day14
 * @property string $day15
 * @property string $day16
 * @property string $day17
 * @property string $day18
 * @property string $day19
 * @property string $day20
 * @property string $day21
 * @property string $day22
 * @property string $day23
 * @property string $day24
 * @property string $day25
 * @property string $day26
 * @property string $day27
 * @property string $day28
 * @property string $day29
 * @property string $day30
 * @property string $day31
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property string $created_from_ip
 * @property string $updated_from_ip
 * @property integer $yearEntity_id
 *
 * @property StatisticsYear $yearEntity
 */
class StatisticsMonth extends \yii\db\ActiveRecord implements \Tecnoready\Common\Model\Configuration\Statistics\StatisticsMonthInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statistics_monthly';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year', 'month', 'total', 'day1', 'day2', 'day3', 'day4', 'day5', 'day6', 'day7', 'day8', 'day9', 'day10', 'day11', 'day12', 'day13', 'day14', 'day15', 'day16', 'day17', 'day18', 'day19', 'day20', 'day21', 'day22', 'day23', 'day24', 'day25', 'day26', 'day27', 'day28', 'day29', 'day30', 'day31', 'created_at', 'updated_at', 'yearEntity_id'], 'required'],
            [['year', 'month', 'yearEntity_id'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['total', 'day1', 'day2', 'day3', 'day4', 'day5', 'day6', 'day7', 'day8', 'day9', 'day10', 'day11', 'day12', 'day13', 'day14', 'day15', 'day16', 'day17', 'day18', 'day19', 'day20', 'day21', 'day22', 'day23', 'day24', 'day25', 'day26', 'day27', 'day28', 'day29', 'day30', 'day31'], 'string', 'max' => 255],
            [['created_from_ip', 'updated_from_ip'], 'string', 'max' => 45],
            [['yearEntity_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatisticsYear::className(), 'targetAttribute' => ['yearEntity_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'Year',
            'month' => 'Month',
            'total' => 'Total',
            'day1' => 'Day1',
            'day2' => 'Day2',
            'day3' => 'Day3',
            'day4' => 'Day4',
            'day5' => 'Day5',
            'day6' => 'Day6',
            'day7' => 'Day7',
            'day8' => 'Day8',
            'day9' => 'Day9',
            'day10' => 'Day10',
            'day11' => 'Day11',
            'day12' => 'Day12',
            'day13' => 'Day13',
            'day14' => 'Day14',
            'day15' => 'Day15',
            'day16' => 'Day16',
            'day17' => 'Day17',
            'day18' => 'Day18',
            'day19' => 'Day19',
            'day20' => 'Day20',
            'day21' => 'Day21',
            'day22' => 'Day22',
            'day23' => 'Day23',
            'day24' => 'Day24',
            'day25' => 'Day25',
            'day26' => 'Day26',
            'day27' => 'Day27',
            'day28' => 'Day28',
            'day29' => 'Day29',
            'day30' => 'Day30',
            'day31' => 'Day31',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'created_from_ip' => 'Created From Ip',
            'updated_from_ip' => 'Updated From Ip',
            'yearEntity_id' => 'Year Entity ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYearEntity()
    {
        return $this->hasOne(StatisticsYear::className(), ['id' => 'yearEntity_id']);
    }
    
    public function init() {
        parent::init();
        $this->total = 0;
        for($i=1;$i<=31;$i++){
            $this->{"day".$i} = 0;
        }
    }
    
    use Tecnoready\Common\Model\Configuration\TraceableTrait;
    
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

    public function totalize()
    {
        $reflection = new \ReflectionClass($this);
        $methods = $reflection->getMethods();
        $nameMatchReal = '^getDay\w+$';
        $total = 0.0;
        foreach ($methods as $method) {
            $methodName = $method->getName();
            if(preg_match('/'.$nameMatchReal.'/i', $methodName)){
                $total += $this->$methodName();
            }
        }
        $this->total = $total;
    }
    
    public function setYearEntity(StatisticsYear $yearEntity) {
        $this->yearEntity = $yearEntity;
        return $this;
    }
}
