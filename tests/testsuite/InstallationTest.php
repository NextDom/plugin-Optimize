<?php

use PHPUnit\Framework\TestCase;

require_once('../../core/class/DB.class.php');
require_once('plugin_info/installation.php');

class InstallationTest extends TestCase
{
    public $dataStorage;

    protected function setUp()
    {
        DB::init();
    }

    protected function tearDown()
    {
        MockedActions::clear();
    }

    public function testInstall()
    {
        Optimize_install();
        $actions = MockedActions::get();
        $this->assertEquals(3, count($actions));
        $this->assertEquals('save', $actions[0]['action']);
        $this->assertEquals('raspberry-config-file', $actions[0]['content']['key']);
        $this->assertEquals('query_execute', $actions[1]['action']);
        $this->assertEquals('query_execute', $actions[2]['action']);
    }

    public function testUpdate()
    {
        Optimize_update();
        $actions = MockedActions::get();
        $this->assertEquals(0, count($actions));
    }

    public function testRemove()
    {
        Optimize_remove();
        $actions = MockedActions::get();
        $this->assertEquals(2, count($actions));
        $this->assertEquals('remove', $actions[0]['action']);
        $this->assertEquals('raspberry-config-file', $actions[0]['content']);
        $this->assertEquals('query_execute', $actions[1]['action']);
    }
}
