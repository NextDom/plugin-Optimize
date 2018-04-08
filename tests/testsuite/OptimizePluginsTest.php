<?php

use PHPUnit\Framework\TestCase;

require_once('../../core/php/core.inc.php');
require_once('core/class/OptimizePlugins.class.php');

class OptimizePluginsTest extends TestCase
{
    public $optimize;

    protected function setUp()
    {
        $this->optimize = new OptimizePlugins();
        MockedActions::clear();
    }

    protected function tearDown()
    {
        unset($this->optimize);
    }

    public function testGetInformations()
    {
        $result = $this->optimize->getInformations();
        $this->assertEquals(3, count($result));
        $this->assertEquals('TheTemplate', $result[0]['name']);
        $this->assertEquals('IOptimize', $result[1]['id']);
        $this->assertEquals(true, $result[1]['log']);
        $this->assertEquals(false, $result[2]['log']);
        $this->assertEquals(1, $result[0]['enabled']);
        $this->assertEquals(0, $result[1]['enabled']);
        $this->assertEquals('MockedPlugins/IOptimize/plugin_info/info.json', $result[1]['filepath']);
        $this->assertEquals(array('log' => 'warn', 'path' => 'warn', 'enabled' => 'ok'), $result[0]['rating']);
        $this->assertEquals(array('log' => 'warn', 'path' => 'warn', 'enabled' => 'warn'), $result[1]['rating']);
        $this->assertEquals(array('log' => 'ok', 'path' => 'warn', 'enabled' => 'ok'), $result[2]['rating']);
    }

    public function testRemoveIfDisabledWithoutUninstall() {
        update::$byIdResult = 'IOptimize';
        update::$byLogicalIdResult = 'IOptimize';
        mkdir('../IOptimize');
        $this->assertDirectoryExists('../IOptimize');
        $this->optimize->removeIfDisabled('IOptimize');
        $this->assertDirectoryNotExists('../IOptimize');
    }

    public function testRemoveIfDisabledWithUninstall() {
        $installationContent = "<?php
        function IOptimize_remove() {
        MockedActions::add('remove_plugin');
        }
        ";
        update::$byIdResult = 'IOptimize';
        update::$byLogicalIdResult = 'IOptimize';
        mkdir('../IOptimize');
        mkdir('../IOptimize/plugin_info');
        file_put_contents('../IOptimize/plugin_info/installation.php', $installationContent);
        $this->assertDirectoryExists('../IOptimize');
        $this->optimize->removeIfDisabled('IOptimize');
        $this->assertDirectoryNotExists('../IOptimize');
        $result = MockedActions::get();
        $this->assertEquals(1, count($result));
        $this->assertEquals('remove_plugin', $result[0]['action']);
    }
}
