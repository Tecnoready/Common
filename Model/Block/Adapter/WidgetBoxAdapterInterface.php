<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Block\Manager;

use Tecnoready\Common\Model\Block\BlockInterface;

/**
 *
 * @author Carlos Mendoza <inhack20@tecnocreaciones.com>
 */
interface WidgetBoxAdapterInterface
{
    /**
     * Guardar el widget
     * @param BlockInterface $blockWidgetBox
     */
    function save(BlockInterface $blockWidgetBox);

    /**
     * Eliminar el widget
     * @param BlockInterface $blockWidgetBox
     */
    function remove(BlockInterface $blockWidgetBox);

    /**
     * Crear un widget nuevo
     */
    function createNew();

    /**
     * Buscar por id
     * @param type $id
     */
    function find($id);

    /**
     * Buscar varios por IDs
     * @param array $ids
     */
    function findByIds(array $ids);

    /**
     * Buscar los publicados de un evento
     * @param type $event
     */
    function findAllPublishedByEvent($event);

    /**
     * Busca un widget publicado por el tipo y el nombre
     * @param type $type
     * @param type $name
     */
    function findPublishedByTypeAndName($type, $name);

    /**
     * Cuenta los widgets publicados en el evento
     * @param type $event
     */
    function countPublishedByEvent($event);

    /**
     * @param array $parameters
     * @return BlockWidgetBox Description
     */
    function buildBlockWidget(array $parameters = array());

    /**
     * Elimina todos los widget de un area
     * @param type $eventName
     */
    function clearAllByEvent($eventName);
}
