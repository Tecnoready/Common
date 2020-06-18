<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Block\Adapter;

use Tecnoready\Common\Model\Block\BlockWidgetBox;

/**
 * Base de adaptador
 *
 * @author Carlos Mendoza <inhack20@tecnocreaciones.com>
 */
abstract class BaseAdapter implements WidgetBoxAdapterInterface
{
    /**
     *
     * @var BlockWidgetBox
     */
    protected $classBox;
            
    function __construct($classBox) {
        $this->classBox = $classBox;
    }
    
    /**
     * 
     * @return BlockWidgetBox
     */
    public function createNew() {
        return new $this->classBox;
    }

    public function buildBlockWidget(array $parameters = array()) {
        $blockWidget = $this->createNew();
        return $blockWidget;
    }
    

}
