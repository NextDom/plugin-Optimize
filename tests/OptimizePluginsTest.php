<?php

use PHPUnit\Framework\TestCase;

require_once('core/class/OptimizePlugins.class.php');
require_once('JeedomMock.php');

class OptimizePluginsTest extends TestCase
{
    public $optimize;

    protected function setUp()
    {
        $this->optimize = new OptimizePlugins();
    }

    protected function tearDown()
    {
        unset($this->optimize);
    }

    public function testGetInformations()
    {
        $result = $this->optimize->getInformations();
        $this->assertEquals(3, count($result));
        $this->assertEquals('Template', $result[0]['name']);
        $this->assertEquals('Optimize', $result[1]['id']);
        $this->assertEquals(true, $result[1]['log']);
        $this->assertEquals(false, $result[2]['log']);
        $this->assertEquals(1, $result[0]['enabled']);
        $this->assertEquals(0, $result[1]['enabled']);
        $this->assertEquals('plugins/Optimize/plugin_info/info.json', $result[1]['filepath']);
        $this->assertEquals(array('log' => 'warn', 'path' => 'ok', 'enabled' => 'ok'), $result[0]['rating']);
        $this->assertEquals(array('log' => 'warn', 'path' => 'ok', 'enabled' => 'warn'), $result[1]['rating']);
        $this->assertEquals(array('log' => 'ok', 'path' => 'warn', 'enabled' => 'ok'), $result[2]['rating']);
    }
}
