<?php

namespace Tecnoready\Common\Service\ObjectManager\HistoryManager\Adapter;

use Tecnoready\Common\Model\ObjectManager\HistoryManager\HistoryInterface;
use Tecnoready\Common\Service\ObjectManager\ConfigureInterface;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface HistoryAdapterInterface extends ConfigureInterface
{
    public function create(HistoryInterface $entity);
    
    public function delete(HistoryInterface $entity);
    
    public function find($id);
    
    public function getPaginator(array $criteria = [],array $sortBy = []);
}
