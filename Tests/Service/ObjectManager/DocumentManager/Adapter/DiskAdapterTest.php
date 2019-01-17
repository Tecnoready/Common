<?php

namespace Tecnoready\Common\Tests\Service\ObjectManager\DocumentManager\Adapter;

use Tecnoready\Common\Tests\BaseTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Tecnoready\Common\Service\ObjectManager\DocumentManager\Adapter\DiskAdapter;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Pruebas de Adaptador para guardar los documentos en el disco
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DiskAdapterTest extends BaseTestCase
{
    /**
     * @var Filesystem
     */
    private $fs;
    
    /**
     *
     * @var File
     */
    private $files;

    /**
     * @var DiskAdapter
     */
    private $adapter;
    
    protected function setUp()
    {
        parent::setUp();
        $this->fs = new Filesystem();
        $documentsPath = $this->getTempPath("DiskAdapter");
        $this->fs->remove($documentsPath);
        
        $this->initFiles();
        $options = [
            "documents_path" => $documentsPath,
            "env" => "test",
        ];
        $this->adapter = new DiskAdapter($options);
        $this->adapter
            ->setType("Factura")
            ->setId("id-unico")
            ;
    }
    
    private function initFiles($andClear = true){
        $basePath = $this->getTempPath("DiskAdapterFiles");
        
        for($i=0;$i<5;$i++){
            $filename = $basePath."/demo_".$i.".txt";
            $this->fs->dumpFile($filename,"Demo archivo ".$i);
            $this->files[] = new File($filename);
        }
    }

        /**
     * @return File
     */
    public function testUpload($andClear = true,$filePos = 0) {
         $file = $this->adapter->upload($this->files[$filePos]);
         $this->assertFileExists($file->getPathname());
         $this->initFiles(false);
         $this->assertFalse($this->adapter->upload($this->files[$filePos]));
         if($andClear){
             unlink($file->getPathname());
         }
         return $file;
    }
    
    public function testDelete()
    {
        $file = $this->testUpload(false);
        $this->adapter->delete($file->getFilename());
        $this->assertFileNotExists($file->getPathname());
    }
    
    public function testGet()
    {
        $file = $this->testUpload(false);
        $file = $this->adapter->get($file->getFilename());
        $this->assertNotNull($file);
        $file = $this->adapter->get("wrong");
        $this->assertNull($file);
    }
    
    public function testGetAll()
    {
        $filePos = 0;
        foreach ($this->files as $file) {
            $this->testUpload(false, $filePos);
            $filePos++;
        }
        $files = $this->adapter->getAll();
        $this->assertCount(5,$files);
        foreach ($files as $file) {
            $this->assertFileExists($file->getPathname());
        }
    }
}
