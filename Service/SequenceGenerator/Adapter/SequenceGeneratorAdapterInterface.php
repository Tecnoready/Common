<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Commom\Service\SequenceGenerator\Adapter;

/**
 * Adaptador de generacion de secuencia
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface SequenceGeneratorAdapterInterface {
    
    public function getRootAliases();
    
    public function select($select = null);
    
    public function andWhere();
    public function like($x, $y);
    public function notLike($x, $y);
    
    public function getOneOrNullResult();
}
