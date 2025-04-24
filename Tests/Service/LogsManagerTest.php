<?php

namespace Tecnoready\Common\Tests\Service;

use Pcv\CommomLibs\Services\LogsManager;

/**
 * Test del log manager
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class LogsManagerTest extends \TestCase {
    public function testLogSimple() {
        $logsManager = new LogsManager([
            "root-path" => storage_path(),
        ]);
        
        $demoLogger = $logsManager->getLogger("demo");
        $demoLogger->info("Hola!");
    }
    public function testLogCategoryDefaultDir() {
        $logsManager = new LogsManager([
            "root-path" => storage_path(),
        ]);
        
        $demoLogger = $logsManager->getLogger("demo",[
            "category" => "api",
        ]);
        $demoLogger->info("Hola!");
    }
    
    public function testLogCategorySubDir() {
        $logsManager = new LogsManager([
            "root-path" => storage_path(),
        ]);
        
        $demoLogger = $logsManager->getLogger("file",[
            "category" => "api/demo",
        ]);
        $demoLogger->info("Hola!");
    }
    public function testLogCategoryFromObject() {
        $logsManager = new LogsManager([
            "root-path" => storage_path(),
        ]);
        
        $demoLogger = $logsManager->getFromObject($this,[
            "category" => "api/demo",
        ]);
        $demoLogger->info("Hola!");
    }
}
