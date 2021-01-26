<?php

namespace Tecnoready\Common\Model\Snippet;

/**
 * Definicion de parametro de snipped
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface ParameterInterface
{
    public function getDescription();

    public function setDescription($description);

    public function getItemKey();

    public function setItemKey($itemKey);
}
