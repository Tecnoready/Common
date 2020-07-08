<?php

namespace Tecnoready\Common\Service\ObjectManager\NoteManager;

use Tecnoready\Common\Service\ObjectManager\NoteManager\Adapter\NoteAdapterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Administrador de notas publicas y privadas
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class NoteManager implements NoteAdapterInterface
{
    use \Tecnoready\Common\Service\ObjectManager\TraitConfigure;
    
    /**
     * Adaptador
     * @var NoteAdapterInterface 
     */
    private $adapter;
    
    /**
     * Opciones de configuracion
     * @var array
     */
    private $options;
    
    public function __construct(NoteAdapterInterface $adapter = null,array $options = [])
    {
        $this->adapter = $adapter;
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
        ]);
        $this->options = $resolver->resolve($options);
    }
    
    public function configure($objectId, $objectType,array $options = [])
    {
        if($this->adapter){
            $this->adapter->configure($objectId, $objectType,$options);
        }
    }

    public function delete(\Tecnoready\Common\Model\ObjectManager\NoteManager\NoteInterface $entity)
    {
        return $this->adapter->delete($entity);
    }

    public function find($id)
    {
        return $this->adapter->find($id);
    }

    public function getPaginator($type, array $criteria = array(), array $sortBy = array())
    {
        return $this->adapter->getPaginator($type,$criteria,$sortBy);
    }

    public function addPrivate($note,array $options = [])
    {
        return $this->adapter->addPrivate($note,$options);
    }

    public function addPublic($note,array $options = [])
    {
        return $this->adapter->addPublic($note,$options);
    }
}
