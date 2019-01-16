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
    private $file;

    /**
     * @var DiskAdapter
     */
    private $adapter;
    
    protected function setUp()
    {
        parent::setUp();
        $basePath = $this->getTempPath("DiskAdapterFiles");
        $documentsPath = $this->getTempPath("DiskAdapter");
        $this->fs->dumpFile($basePath."/demo.txt","Demo archivo");
        $this->file = new File($basePath);
        
        $options = [
            "documents_path" => $documentsPath,
            "env" => "test",
        ];
        $this->adapter = new DiskAdapter($options);
        $this->adapter
            ->setFolder("Factura")
            ->setId("id-unico")
            ;
    }
    public function testUpload() {
         $this->adapter->upload($this->file);
    }
}
