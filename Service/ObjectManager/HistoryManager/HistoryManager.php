<?php

namespace Tecnoready\Common\Service\ObjectManager\HistoryManager;

use Tecnoready\Common\Service\ObjectManager\HistoryManager\Adapter\HistoryAdapterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Administrador del historial
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class HistoryManager implements HistoryAdapterInterface
{
    use \Tecnoready\Common\Service\ObjectManager\TraitConfigure;
    
    /**
     * Adaptador
     * @var HistoryAdapterInterface 
     */
    private $adapter;
    
    /**
     * Opciones de configuracion
     * @var array
     */
    private $options;
    
    public function __construct(HistoryAdapterInterface $adapter = null,array $options = [])
    {
        $this->adapter = $adapter;
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
        ]);
        $this->options = $resolver->resolve($options);
    }
    
    public function configure($objectId, $objectType, array $options = array())
    {
        $this->objectId = $objectId;
        $this->objectType = $objectType;
        $this->adapter->configure($objectId, $objectType, $options);
    }

    
    public function create(\Tecnoready\Common\Model\ObjectManager\HistoryManager\HistoryInterface $entity)
    {
        return $this->adapter->create($entity);
    }

    public function delete(\Tecnoready\Common\Model\ObjectManager\HistoryManager\HistoryInterface $entity)
    {
        return $this->adapter->delete($entity);
    }

    public function find($id)
    {
        return $this->adapter->find($id);
    }

    public function getPaginator(array $criteria = array(), array $sortBy = array())
    {
        return $this->adapter->getPaginator($criteria,$sortBy);
    }

    public function createNew(array $options = array())
    {
        return $this->adapter->createNew($options);
    }
}
