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
            [['total', 'total_month_1', 'total_month_2', 'total_month_3', 'total_month_4', 'total_month_5', 'total_month_6', 'total_month_7', 'total_month_8', 'total_month_9', 'total_month_10', 'total_month_11', 'total_month_12'], 'string', 'max' => 255],
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
    
    public function init() {
        parent::init();
        $this->total = 0;
        for($i=1; $i<=12;$i++){
            $this->{"totalMonth".$i} = 0;
        }
    }
    
    use Tecnoready\Common\Model\TraceableTrait;    
    
    /**
     * Total de todos los meses
     * @var integer
     * @ORM\Column(name="total",type="decimal", precision=50, scale=18, nullable=false)
     */
    protected $total = 0;
    
    /**
     * Meses
     * @var StatisticsMonth
     * @ORM\OneToMany(targetEntity="Pandco\Bundle\AppBundle\Entity\Core\Statistics\StatisticsMonth",cascade={"persist","remove"},mappedBy="yearEntity")
     */
    protected $months;

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
    }
}
