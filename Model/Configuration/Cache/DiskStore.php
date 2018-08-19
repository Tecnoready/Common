<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Configuration\Cache;

use Tecnoready\Common\Model\Configuration\BaseCache;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Cache de disco
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DiskStore extends BaseCache 
{
    /**
     * @var Filesystem
     */
    protected $fs;
    protected $configurations;
    
    private $isInit = false;

    public function __construct(array $options) {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'debug' => false,
            'filename' => "configuration_cache.php",
            'folder' => "tecnoready_tools",
            'method_encrypt' => "AES-256-CBC", 
        ]);
        
        $resolver->setRequired(["cache_dir","password"]);
        $resolver->addAllowedTypes("cache_dir","string");
        
        $this->options = $resolver->resolve($options);
        
        $this->options["key"] = hash('sha256', $this->options["password"]);
        
        $this->fs = new \Symfony\Component\Filesystem\Filesystem();
        $this->init();
    }
    
    private function init(){
        if($this->isInit){
            return;
        }
        $filename = $this->getCacheFileName();
        if($this->fs->exists($filename)){
            $configurations = include $filename;
            $this->values = $configurations["values"];
            $this->isInit = true;
        }
    }

    public function contains($key, $wrapperName) {
        $this->init();
        return isset($this->values[$this->getId($key, $wrapperName)]);
    }

    public function delete($key, $wrapperName) {
        $this->init();
        if($this->contains($key, $wrapperName)){
            unset($this->values[$this->getId($key, $wrapperName)]);
            $this->updateFromValues();
        }
    }

    public function fetch($key, $wrapperName) {
        $this->init();
        $data = null;
        $data =unserialize($this->decrypt($this->values[$this->getId($key, $wrapperName)]));
        return $data;
    }

    public function save($key, $wrapperName, $data, $lifeTime = 0) {
        $this->init();
        $this->values[$this->getId($key, $wrapperName)] = $this->encrypt(serialize($data));
        $this->updateFromValues();
    }
    
    public function flush() {
        //eliminar cache
        $this->values = [];
        $filename = $this->getCacheFileName();
        $this->fs->remove($filename);
        $this->isInit = false;
    }

    function getConfiguration($key,$wrapperName)
    {
        if($this->contains($key, $wrapperName)){
            $data = $this->fetch($key, $wrapperName);
            $configuration = $this->adapter->createNew();
            $configuration->setValue($data["value"]);
            $configuration->setType($data["type"]);
            $configuration->setDataType($data["data_type"]);
            return $configuration;
        }
        return null;
    }
    
    public function warmUp(array $configurations) {
        $code = '';
        
        foreach ($configurations as $key => $configuration) {
            $data = array();
            $data['value'] = $configuration->getValue();
            $data['type'] = $configuration->getType();
            $data['data_type'] = $configuration->getDataType();
            
            $id = $this->getId($configuration->getKey(),$configuration->getNameWrapper());
            $code .= sprintf("'%s' => '%s',", $id ,$this->encrypt(serialize($data)));
        }
        
        $code = rtrim($code);
        
        $success = $this->saveToDisk($code);
        $this->isInit = false;
        return $success;
    }

    private function getCacheFileName() {
        $ds = DIRECTORY_SEPARATOR;
        return $this->options["cache_dir"].$ds.$this->options["folder"].$ds.$this->options["filename"];
    }

    private function updateFromValues() {
        return $this->saveToDisk(var_export($this->values,true));
    }
    
    private function saveToDisk($code){
        $now = new \DateTime();
        $dumpedAt = $now->format("Y-m-d H:i:s");
        $content = <<<EOF
<?php
/**
 * This class has been auto-generated
 * by the Tecnoready Tools Component.
 * dumped at {$dumpedAt}
 */
return array(
    "dumped_at" => "{$dumpedAt}",
    "values" => array(
        $code
    )
);
EOF;
        $filename = $this->getCacheFileName();
        $this->fs->remove($filename);
        $this->fs->dumpFile($filename, $content);
        return true;
    }
}
