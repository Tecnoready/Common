<?php

namespace Tecnoready\Common\Model\Statistics;

use Tecnoready\Common\Model\Statistics\StatisticsMonthInterface;
use Tecnoready\Common\Model\Statistics\StatisticsYearInterface;
use Tecnoready\Common\Model\ObjectManager\BaseInterface;

/**
 * StatisticsYearInterface
 * 
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface StatisticsYearInterface extends BaseInterface
{
    /**
     * Set year
     *
     * @param integer $year
     */
    public function setYear($year);
    /**
     * Get year
     *
     * @return integer
     */
    public function getYear();

    /**
     * Get total
     *
     * @return string
     */
    public function getTotal();

    /**
     * Set totalMonth1
     *
     * @param string $totalMonth1
     */
    public function setTotalMonth1($totalMonth1);

    /**
     * Get totalMonth1
     *
     * @return string
     */
    public function getTotalMonth1();

    /**
     * Set totalMonth2
     *
     * @param string $totalMonth2
     *
     * @return StatisticsYearInterface
     */
    public function setTotalMonth2($totalMonth2);

    /**
     * Get totalMonth2
     *
     * @return string
     */
    public function getTotalMonth2();

    /**
     * Set totalMonth3
     *
     * @param string $totalMonth3
     *
     * @return StatisticsYearInterface
     */
    public function setTotalMonth3($totalMonth3);

    /**
     * Get totalMonth3
     *
     * @return string
     */
    public function getTotalMonth3();

    /**
     * Set totalMonth4
     *
     * @param string $totalMonth4
     *
     * @return StatisticsYearInterface
     */
    public function setTotalMonth4($totalMonth4);

    /**
     * Get totalMonth4
     *
     * @return string
     */
    public function getTotalMonth4();

    /**
     * Set totalMonth5
     *
     * @param string $totalMonth5
     *
     * @return StatisticsYearInterface
     */
    public function setTotalMonth5($totalMonth5);

    /**
     * Get totalMonth5
     *
     * @return string
     */
    public function getTotalMonth5();

    /**
     * Set totalMonth6
     *
     * @param string $totalMonth6
     *
     * @return StatisticsYearInterface
     */
    public function setTotalMonth6($totalMonth6);

    /**
     * Get totalMonth6
     *
     * @return string
     */
    public function getTotalMonth6();

    /**
     * Set totalMonth7
     *
     * @param string $totalMonth7
     *
     * @return StatisticsYearInterface
     */
    public function setTotalMonth7($totalMonth7);

    /**
     * Get totalMonth7
     *
     * @return string
     */
    public function getTotalMonth7();

    /**
     * Set totalMonth8
     *
     * @param string $totalMonth8
     *
     * @return StatisticsYearInterface
     */
    public function setTotalMonth8($totalMonth8);

    /**
     * Get totalMonth8
     *
     * @return string
     */
    public function getTotalMonth8();

    /**
     * Set totalMonth9
     *
     * @param string $totalMonth9
     *
     * @return StatisticsYearInterface
     */
    public function setTotalMonth9($totalMonth9);

    /**
     * Get totalMonth9
     *
     * @return string
     */
    public function getTotalMonth9();

    /**
     * Set totalMonth10
     *
     * @param string $totalMonth10
     *
     * @return StatisticsYearInterface
     */
    public function setTotalMonth10($totalMonth10);

    /**
     * Get totalMonth10
     *
     * @return string
     */
    public function getTotalMonth10();

    /**
     * Set totalMonth11
     *
     * @param string $totalMonth11
     *
     * @return StatisticsYearInterface
     */
    public function setTotalMonth11($totalMonth11);

    /**
     * Get totalMonth11
     *
     * @return string
     */
    public function getTotalMonth11();

    /**
     * Set totalMonth12
     *
     * @param string $totalMonth12
     *
     * @return StatisticsYearInterface
     */
    public function setTotalMonth12($totalMonth12);

    /**
     * Get totalMonth12
     *
     * @return string
     */
    public function getTotalMonth12();

    /**
     * Add month
     *
     * @param StatisticsMonthInterface $month
     *
     * @return StatisticsYearInterface
     */
    public function addMonth(StatisticsMonthInterface $month);

    /**
     * Remove month
     *
     * @param StatisticsMonthInterface $month
     */
    public function removeMonth(StatisticsMonthInterface $month);

    /**
     * Get months
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMonths();
    
    /**
     * @param StatisticsMonthInterface $month
     */
    public function getMonth($month);
    
    public function totalize();
}
