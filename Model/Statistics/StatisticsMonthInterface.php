<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Statistics;

use Tecnoready\Common\Model\ObjectManager\BaseInterface;

/**
 * StatisticsMonthInterface
 * 
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface StatisticsMonthInterface extends BaseInterface 
{
    public function getYear();

    public function getMonth();

    public function getDay1();

    public function getDay2();

    public function getDay3();

    public function getDay4();

    public function getDay5();

    public function getDay6();

    public function getDay7();

    public function getDay8();

    public function getDay9();

    public function getDay10();

    public function getDay11();

    public function getDay12();

    public function getDay13();

    public function getDay14();

    public function getDay15();

    public function getDay16();

    public function getDay17();

    public function getDay18();

    public function getDay19();

    public function getDay20();

    public function getDay21();

    public function getDay22();

    public function getDay23();

    public function getDay24();

    public function getDay25();

    public function getDay26();

    public function getDay27();

    public function getDay28();

    public function getDay29();

    public function getDay30();

    public function getDay31();

    public function setYear($year);

    public function setMonth($month);
    
    public function setDay1($day1);

    public function setDay2($day2);

    public function setDay3($day3);

    public function setDay4($day4);

    public function setDay5($day5);

    public function setDay6($day6);

    public function setDay7($day7);

    public function setDay8($day8);

    public function setDay9($day9);

    public function setDay10($day10);

    public function setDay11($day11);

    public function setDay12($day12);

    public function setDay13($day13);

    public function setDay14($day14);

    public function setDay15($day15);

    public function setDay16($day16);

    public function setDay17($day17);

    public function setDay18($day18);

    public function setDay19($day19);

    public function setDay20($day20);

    public function setDay21($day21);

    public function setDay22($day22);

    public function setDay23($day23);

    public function setDay24($day24);

    public function setDay25($day25);

    public function setDay26($day26);

    public function setDay27($day27);

    public function setDay28($day28);

    public function setDay29($day29);

    public function setDay30($day30);

    public function setDay31($day31);
    
    public function getTotal();
    
    public function totalize();
    
    public function getYearEntity();

    public function setYearEntity(StatisticsYearInterface $yearEntity);
}
