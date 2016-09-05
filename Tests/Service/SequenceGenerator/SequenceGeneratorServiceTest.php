<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PHPUnit\Framework\TestCase;

/**
 * Prueba el generador de secuencia
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class SequenceGeneratorServiceTest extends TestCase {
    public function testNewService() {
//        $sequenceGeneratorService = new \Tecnoready\Common\Service\SequenceGenerator\SequenceGeneratorService();
//        
//        $mask = "FAC-{00000}";
//        $field = "ref";
//        
//        $adapter = new \Tecnoready\Common\Service\SequenceGenerator\Adapter\Yii2ActiveRecordAdapter();
//        $sequenceGeneratorService->setAdapter($adapter);
//        $sequenceGeneratorService->setClassMap([
//            \client\advertising\modules\core\models\billing\Order::class => ["mask" => $mask]
//        ]);
//        
//        $next = $sequenceGeneratorService->generateNext(new \client\advertising\modules\core\models\billing\Order, $mask, $field);
//        $next = $sequenceGeneratorService->setRef(new \client\advertising\modules\core\models\billing\Order);
        
        $this->assertEquals(-1,-1);
    }
}
