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
 * Base del servicio del generador de secuencias
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class SequenceGeneratorService
{
    /**
     * Instancia del generador de secuencias
     * @var \Tecnoready\Common\Service\SequenceGenerator\SequenceGenerator
     */
    protected $sequenceGenerator;
    
    /**
     * Adaptador con el cual trabajara el generador
     * @var \Tecnoready\Common\Service\SequenceGenerator\Adapter\SequenceGeneratorAdapterInterface
     */
    protected $adapter;
    
    /**
     * Configuracion de las clases
     * @var array
     */
    protected $classMap;
    /**
     * Opciones
     * @var array
     */
    protected $options;
    
    public function __construct(array $options = []) {
        
        if(isset($options["sequenceGeneratorOptions"])){
            $this->sequenceGenerator = new SequenceGenerator($options["sequenceGeneratorOptions"]);
        }
    }

    /**
     * Construye la referencia por defecto
     * @param ItemReferenceInterface $item
     * @param array $config
     * @return type
     */
    public function buildRef($item,array $config,array $parameters = []) {
        $mask = $config['mask'];
        $className = $config['className'];
        $field = $config['field'];
        
        $adapter = $this->adapter->createAdapter($className);
        return $this->getSequenceGenerator()->generateNext($adapter, $mask,$field,$parameters);
    }
    
    /**
     * Establece la referencia aun objeto
     * @param ItemReferenceInterface $item
     * @return type
     * @throws LogicException
     */
    public function setRef(ItemReferenceInterface $item,array $parameters = []) 
    {
        $className = $this->getClassName($item);
        $classMap = $this->getClassMap();
        if(!isset($classMap[$className])){
            throw new LogicException(sprintf("No ha definido la configuracion de '%s' para generar su referencia",$className));
        }
        $config = $this->mergeConfig($className);
        $ref = $this->generateNext($item,$config["mask"],$config["field"],$parameters);
        $item->setRef($ref);
        return $ref;
    }
    /**
     * Obtiene la siguiente secuencia de un objeto
     * @param type $item
     * @return type
     * @throws LogicException
     */
    public function generateSequence($item,array $parameters = []) {
        $className = $this->getClassName($item);
        $classMap = $this->getClassMap();
        if(!isset($classMap[$className])){
            throw new LogicException(sprintf("No ha definido la configuracion de '%s' para generar su referencia",$className));
        }
        $config = $this->mergeConfig($className);
        $ref = $this->generateNext($item,$config["mask"],$config["field"],$parameters);
        return $ref;
    }
    
    /**
     * Genera el siguiente valor del item
     * @param type $item
     * @return type
     */
    public function generateNext($item,$mask, $field = "ref",array $parameters = []) {
        $className = $this->getClassName($item);
        $config = $this->mergeConfig($className);
        
        $method = $config['method'];
        $config['mask'] = $mask;
        $config['field'] = $field;
        $ref = $this->$method($item,$config,$parameters);
        return $ref;
    }
    
    /**
     * Combina las configuraciones 
     * @param type $className
     * @param array $config
     * @return type
     */
    protected function mergeConfig($className,array $config = []) {
        $classMap = $this->getClassMap();
        $defaultConfig = [
            'method' => 'buildRef',
            'field' => 'ref',
        ];
        $configClass = [];
        if(isset($classMap[$className])){
            $configClass = $classMap[$className];
        }
        $config = array_merge($defaultConfig,$configClass);
        $config['className'] = $className;
        return $config;
    }
    
    /**
     * Resuelve el nombre real de un objeto
     * @param type $item
     * @return type
     */
    protected function getClassName($item) {
        $className = get_class($item);
        //Si se usa doctrine2 ORM, con esto se obtiene la clase original
        if(class_exists("Doctrine\Common\Util\ClassUtils")){
            $className = ClassUtils::getRealClass($className);
        }
        return $className;
    }


    /**
     * Establece el generador de secuencia
     * @param SequenceGenerator $sequenceGenerator
     */
    function setSequenceGenerator(SequenceGenerator $sequenceGenerator) {
        $this->sequenceGenerator = $sequenceGenerator;
    }
    
    public function getSequenceGenerator() {
        if($this->sequenceGenerator === null){
            $this->sequenceGenerator = new SequenceGenerator();
        }
        return $this->sequenceGenerator;
    }
    
    /**
     * Establece el adapdador con el cual trabajara el generador
     * @param \Tecnoready\Common\Service\SequenceGenerator\Adapter\SequenceGeneratorAdapterInterface $adapter
     * @return \Tecnoready\Common\Service\SequenceGenerator\SequenceGeneratorService
     */
    public function setAdapter(\Tecnoready\Common\Service\SequenceGenerator\Adapter\SequenceGeneratorAdapterInterface $adapter) {
        $this->adapter = $adapter;
        return $this;
    }
    
    public function getClassMap() {
        return $this->classMap;
    }

    /**
     * 
     * @param array $classMap
     * [
     *  'Pandco\Bundle\AppBundle\Entity\User\TransactionItem\Commission\Commission' => array('method' => 'getNextCommission'),
        'Pandco\Bundle\AppBundle\Entity\Support\Issue' => array('mask' => 'I{yy}{mm}{dd}{00000000}'),
     * ]
     * @return \Tecnoready\Common\Service\SequenceGenerator\SequenceGeneratorService
     */
    public function setClassMap(array $classMap) {
        $this->classMap = $classMap;
        return $this;
    }
    /**
     * Añade mas configuracion al generador de secuencia
     * @param array $classMap
     * @return \Tecnoready\Common\Service\SequenceGenerator\SequenceGeneratorService
     */
    public function addToClassMap(array $classMap) {
        if($this->classMap === null){
            $this->classMap = [];
        }
        $this->classMap = array_merge($this->classMap,$classMap);
        return $this;
    }
}
