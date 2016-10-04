<?php

namespace Tecnoready\Common\Model\Statistics\Yii2ActiveRecord;

use Yii;

/**
 * Estadistica de un año
 *
 * This is the model class for table "statistics_year".
 * @author Carlos Mendoza <inhack20@gmail.com>
 *
 * @property integer $id
 * @property integer $year
 * @property string $total
 * @property string $total_month_1
 * @property string $total_month_2
 * @property string $total_month_3
 * @property string $total_month_4
 * @property string $total_month_5
 * @property string $total_month_6
 * @property string $total_month_7
 * @property string $total_month_8
 * @property string $total_month_9
 * @property string $total_month_10
 * @property string $total_month_11
 * @property string $total_month_12
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property string $created_from_ip
 * @property string $updated_from_ip
 *
 * @property StatisticsMonthly[] $statisticsMonthlies
 */
class StatisticsYear extends \yii\db\ActiveRecord implements \Tecnoready\Common\Model\Statistics\StatisticsYearInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statistics_year';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year', 'total', 'total_month_1', 'total_month_2', 'total_month_3', 'total_month_4', 'total_month_5', 'total_month_6', 'total_month_7', 'total_month_8', 'total_month_9', 'total_month_10', 'total_month_11', 'total_month_12', 'created_at', 'updated_at'], 'required'],
            [['year'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            //[['total', 'total_month_1', 'total_month_2', 'total_month_3', 'total_month_4', 'total_month_5', 'total_month_6', 'total_month_7', 'total_month_8', 'total_month_9', 'total_month_10', 'total_month_11', 'total_month_12'], 'string', 'max' => 255],
            [['created_from_ip', 'updated_from_ip'], 'string', 'max' => 45],
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
            'total' => 'Total',
            'total_month_1' => 'Total Month 1',
            'total_month_2' => 'Total Month 2',
            'total_month_3' => 'Total Month 3',
            'total_month_4' => 'Total Month 4',
            'total_month_5' => 'Total Month 5',
            'total_month_6' => 'Total Month 6',
            'total_month_7' => 'Total Month 7',
            'total_month_8' => 'Total Month 8',
            'total_month_9' => 'Total Month 9',
            'total_month_10' => 'Total Month 10',
            'total_month_11' => 'Total Month 11',
            'total_month_12' => 'Total Month 12',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'created_from_ip' => 'Created From Ip',
            'updated_from_ip' => 'Updated From Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatisticsMonthlies()
    {
        return $this->hasMany(StatisticsMonthly::className(), ['yearEntity_id' => 'id']);
    }
    
    use \Tecnoready\Common\Model\TraceableTrait;

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
        $this->total_month_1 = $totalMonth1;

        return $this;
    }

    /**
     * Get totalMonth1
     *
     * @return string
     */
    public function getTotalMonth1()
    {
        return $this->total_month_1;
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
        $this->total_month_2 = $totalMonth2;

        return $this;
    }

    /**
     * Get totalMonth2
     *
     * @return string
     */
    public function getTotalMonth2()
    {
        return $this->total_month_2;
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
        $this->total_month_3 = $totalMonth3;

        return $this;
    }

    /**
     * Get totalMonth3
     *
     * @return string
     */
    public function getTotalMonth3()
    {
        return $this->total_month_3;
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
        $this->total_month_4 = $totalMonth4;

        return $this;
    }

    /**
     * Get totalMonth4
     *
     * @return string
     */
    public function getTotalMonth4()
    {
        return $this->total_month_4;
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
        $this->total_month_5 = $totalMonth5;

        return $this;
    }

    /**
     * Get totalMonth5
     *
     * @return string
     */
    public function getTotalMonth5()
    {
        return $this->total_month_5;
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
        $this->total_month_6 = $totalMonth6;

        return $this;
    }

    /**
     * Get totalMonth6
     *
     * @return string
     */
    public function getTotalMonth6()
    {
        return $this->total_month_6;
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
        $this->total_month_7 = $totalMonth7;

        return $this;
    }

    /**
     * Get totalMonth7
     *
     * @return string
     */
    public function getTotalMonth7()
    {
        return $this->total_month_7;
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
        $this->total_month_8 = $totalMonth8;

        return $this;
    }

    /**
     * Get totalMonth8
     *
     * @return string
     */
    public function getTotalMonth8()
    {
        return $this->total_month_8;
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
        $this->total_month_9 = $totalMonth9;

        return $this;
    }

    /**
     * Get totalMonth9
     *
     * @return string
     */
    public function getTotalMonth9()
    {
        return $this->total_month_9;
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
        $this->total_month_10 = $totalMonth10;

        return $this;
    }

    /**
     * Get totalMonth10
     *
     * @return string
     */
    public function getTotalMonth10()
    {
        return $this->total_month_10;
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
        $this->total_month_11 = $totalMonth11;

        return $this;
    }

    /**
     * Get totalMonth11
     *
     * @return string
     */
    public function getTotalMonth11()
    {
        return $this->total_month_11;
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
        $this->total_month_12 = $totalMonth12;

        return $this;
    }

    /**
     * Get totalMonth12
     *
     * @return string
     */
    public function getTotalMonth12()
    {
        return $this->total_month_12;
    }

    /**
     * Add month
     *
     * @return StatisticsYear
     */
    public function addMonth(\Tecnoready\Common\Model\Statistics\StatisticsMonthInterface $month)
    {
        $month->setYearEntity($this);

        return $this;
    }

    /**
     * Remove month
     */
    public function removeMonth(\Tecnoready\Common\Model\Statistics\StatisticsMonthInterface $month)
    {
        $this->months->removeElement($month);
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
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonths()
    {
        return $this->hasMany(StatisticsMonth::className(), ['yearEntity_id' => 'id'])->all();
    }
}
