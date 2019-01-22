<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\ConfigurationService\Cache;

use Tecnoready\Common\Model\Configuration\BaseCache;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

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
    private $dumpedAt = null;

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
        $finder = new Finder();
        $finder->in($this->getCacheFolder())->files()->depth(0)->sortByName();
        $last = null;
        foreach ($finder as $file) {
            $last = $file;
        }
        if($last === null){
            return;
        }
        $filename = $last->getPathName();
        if($this->fs->exists($filename)){
            $configurations = include $filename;
            $this->dumpedAt = $configurations["dumped_at"];
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
        $data = unserialize($this->decrypt($this->values[$this->getId($key, $wrapperName)]));
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
        $this->clearCacheFolder();
        $this->isInit = false;
    }
    
    public function warmUp(array $configurations) {
        $code = '';
        
        foreach ($configurations as $key => $configuration) {
            $data = array();
            $data['value'] = $configuration->getValue();
            $data['type'] = $configuration->getType();
            $data['dataType'] = $configuration->getDataType();
            $data['description'] = $configuration->getDescription();
            
            $id = $this->getId($configuration->getKey(),$configuration->getNameWrapper());
            $code .= sprintf("'%s' => '%s',\n", $id ,$this->encrypt(serialize($data)));
        }
        $code = rtrim($code);
        
        $success = $this->saveToDisk($code);
        $this->isInit = false;
        return $success;
    }

    private function getCacheFileName() {
        $ds = DIRECTORY_SEPARATOR;
        return $this->getCacheFolder().$ds.$this->options["filename"];
    }
    
    private function getCacheFolder()
    {
        $ds = DIRECTORY_SEPARATOR;
        return $this->options["cache_dir"].$ds.$this->options["folder"].$ds."params";
    }
    
    private function clearCacheFolder()
    {
        $this->fs->remove($this->getCacheFolder());
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
        
        $filename = $this->getCacheFileName().".".time();
        $this->clearCacheFolder();
        $this->fs->dumpFile($filename, $content);
        return true;
    }
}
