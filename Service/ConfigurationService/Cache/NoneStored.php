<?php

namespace Tecnoready\Common\Service\ConfigurationService\Cache;

use Tecnoready\Common\Model\Configuration\BaseCache;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Sin cache
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class NoneStored extends BaseCache
{
    private $configurations = [];
    
    public function __construct(array $options = []) {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'folder' => "tecnoready_tools",
            'method_encrypt' => "AES-256-CBC", 
            'password' => "40cb3cbe84491f86c8e53157e5a91b88", 
        ]);
        
        $resolver->setRequired(["password"]);
        
        $this->options = $resolver->resolve($options);
        $this->options["key"] = hash('sha256', $this->options["password"]);
        
    }
    
    public function contains($key, $wrapperName) {
        return isset($this->configurations[$this->getId($key, $wrapperName)]);
    }

    public function delete($key, $wrapperName) {
        if($this->contains($key, $wrapperName)){
            unset($this->configurations[$this->getId($key, $wrapperName)]);
        }
    }

    public function fetch($key, $wrapperName) {
        //Forzar lectura de la BD para evitar cache de memorira
//        $this->warmUp($this->adapter->findAll());
        
        $data = $this->configurations[$this->getId($key, $wrapperName)];
        return $data;
    }

    public function save($key, $wrapperName, $data, $lifeTime = 0) {
        $this->configurations[$this->getId($key, $wrapperName)] = $data;
    }
    
    public function flush() {
        //eliminar cache
        $this->configurations = [];
    }
    
    public function warmUp(array $configurations) {
        $this->configurations = [];
        foreach ($configurations as $key => $configuration) {
            $data = array();
            $data['value'] = $configuration->getValue();
            $data['type'] = $configuration->getType();
            $data['dataType'] = $configuration->getDataType();
            $data['description'] = $configuration->getDescription();
            
            $id = $this->getId($configuration->getKey(),$configuration->getNameWrapper());
            $this->configurations[$id] = $data;
        }
//        var_dump($this->configurations);
        return true;
    }

}
