<?php

namespace Tecnoready\Common\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Base para pruebas unitarias
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class BaseTestCase extends TestCase
{

    protected $tmpPath;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $tmp = realpath(__DIR__ . "/Temp");
        $this->tmpPath = $tmp;
    }

    protected function getTempPath($dirname)
    {
        $tmp = $this->tmpPath . "/" . $dirname;
        @mkdir($tmp, 0777, true);
        return $tmp;
    }

}
