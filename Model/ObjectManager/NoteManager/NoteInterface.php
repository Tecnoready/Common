<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\ObjectManager\NoteManager;

use Tecnoready\Common\Model\ObjectManager\BaseInterface;

/**
 * Definicion de una nota
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface NoteInterface extends BaseInterface
{
    /**
     * Tipo de nota publica. (notas que se veran en reportes o fichas)
     */
    const TYPE_PUBLIC = "public";
    
    /**
     * Tipo de nota privada. (notas que solo se vera en la seccion de notas privadas)
     */
    const TYPE_PRIVATE = "private";
    
    public function getType();
    
    public function setType($type);
}
