<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\ObjectManager\NoteManager\Adapter;

use Tecnoready\Common\Model\ObjectManager\NoteManager\NoteInterface;
use Tecnoready\Common\Service\ObjectManager\ConfigureInterface;

/**
 * Interfaz para generar notas
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface NoteAdapterInterface extends ConfigureInterface
{
    public function addPublic($note,array $options = []);
    public function addPrivate($note,array $options = []);
    
    /**
     * Crea una nota
     * @param NoteInterface $entity
     */
//    public function create(NoteInterface $entity);
    
    /**
     * Elimina una nota
     * @param NoteInterface $entity
     */
    public function delete(NoteInterface $entity);
    
    /**
     * Busca una nota
     * @param type $id
     */
    public function find($id);
    
    /**
     * Retorna el paginador por el tipo de nota
     * @param type $type
     * @param array $criteria
     * @param array $sortBy
     */
    public function getPaginator($type,array $criteria = [],array $sortBy = []);
}
