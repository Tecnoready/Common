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

use Doctrine\Common\Util\ClassUtils;
use LogicException;

/**
 * Base del generador de secuencias
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class SequenceGeneratorServiceBase implements SequenceGeneratorBaseInterface
{
    /**
     * Instancia del generador de secuencias
     * @var \Tecnoready\Common\Service\SequenceGenerator\SequenceGenerator
     */
    protected $sequenceGenerator;
    
    /**
     *
     * @var \Tecnoready\Common\Service\SequenceGenerator\Adapter\SequenceGeneratorAdapterInterface
     */
    protected $adapter;

    /**
     * Construye la referencia por defecto
     * @param ItemReferenceInterface $item
     * @param array $config
     * @return type
     */
    public function buildRef(ItemReferenceInterface $item,array $config) {
        $mask = $config['mask'];
        $className = $config['className'];
        $field = $config['field'];
        
        $adapter = $this->adapter->createAdapter($className);
        return $this->sequenceGenerator->generateNext($adapter, $mask,$field);
    }
    
    /**
     * Establece la referencia aun objeto
     * @param ItemReferenceInterface $item
     * @return type
     * @throws LogicException
     */
    public function setRef(ItemReferenceInterface $item) 
    {
        $className = ClassUtils::getRealClass(get_class($item));
        
        $classMap = $this->getClassMap();
        if(!isset($classMap[$className])){
            throw new LogicException(sprintf("No ha definido la configuracion de '%s' para generar su referencia",$className));
        }
        $defaultConfig = [
            'method' => 'buildRef',
            'field' => 'ref',
        ];
        $config = array_merge($defaultConfig,$classMap[$className]);
        $config['className'] = $className;
        
        $method = $config['method'];
        $ref = $this->$method($item,$config);
        $item->setRef($ref);
        return $ref;
    }
    
    /**
     * Establece el generador de secuencia
     * @param \Tecnocreaciones\Bundle\ToolsBundle\Service\SequenceGenerator $sequenceGenerator
     */
    function setSequenceGenerator(\Tecnocreaciones\Bundle\ToolsBundle\Service\SequenceGenerator $sequenceGenerator) {
        $this->sequenceGenerator = $sequenceGenerator;
    }
    
    public function setAdapter(\Tecnoready\Common\Service\SequenceGenerator\Adapter\SequenceGeneratorAdapterInterface $adapter) {
        $this->adapter = $adapter;
        return $this;
    }
}
