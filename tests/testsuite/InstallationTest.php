<?php

use PHPUnit\Framework\TestCase;

require_once('../../core/class/DB.class.php');
require_once('plugin_info/install.php');

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
        $this->assertCount(4, $actions);
        $this->assertEquals('save', $actions[0]['action']);
        $this->assertEquals('raspberry-config-file', $actions[0]['content']['key']);
        $this->assertEquals('save', $actions[1]['action']);
        $this->assertEquals('minify', $actions[1]['content']['key']);
        $this->assertEquals('query_execute', $actions[2]['action']);
        $this->assertEquals('query_execute', $actions[3]['action']);
    }

    public function testUpdate()
    {
        Optimize_update();
        $actions = MockedActions::get();
        $this->assertCount(0, $actions);
    }

    public function testRemove()
    {
        Optimize_remove();
        $actions = MockedActions::get();
        $this->assertCount(3, $actions);
        $this->assertEquals('remove', $actions[0]['action']);
        $this->assertEquals('raspberry-config-file', $actions[0]['content']['key']);
        $this->assertEquals('Optimize', $actions[0]['content']['plugin']);
        $this->assertEquals('remove', $actions[1]['action']);
        $this->assertEquals('minify', $actions[1]['content']['key']);
        $this->assertEquals('query_execute', $actions[2]['action']);
    }
}
