<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\SequenceGenerator;

/**
 * Interfaz para establecer referencia a objetos
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface ItemReferenceInterface 
{
    public function getRef();
    
    public function setRef($ref);
}
