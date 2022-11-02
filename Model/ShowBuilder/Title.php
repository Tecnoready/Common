<?php

namespace Tecnoready\Common\Model\ShowBuilder;

/**
 * Titulo
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class Title extends ModelItem
{
    public function __construct()
    {
        $this->widget = self::WIDGET_TITLE;
    }
}
