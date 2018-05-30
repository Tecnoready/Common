<?php

namespace Tecnoready\Common\Service\Email\Adapter;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface EmailAdapterInterface {
    public function find($key);
    
    /*
     * Guarda los cambios en la base de datos
     */
    public function flush();
    
    public function persist($entity);
    
    public function createNew();
}
