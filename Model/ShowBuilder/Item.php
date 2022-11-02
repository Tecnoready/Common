<?php

namespace Tecnoready\Common\Model\ShowBuilder;

/**
 * Item
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class Item extends ModelItem
{
    public function __construct()
    {
        $this->widget = self::WIDGET_ITEM;
    }
}
