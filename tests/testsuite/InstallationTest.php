<?php

use PHPUnit\Framework\TestCase;

require_once('plugin_info/installation.php');

class InstallationTest extends TestCase
{
    public $dataStorage;

    protected function setUp()
    {

    }

    protected function tearDown()
    {
    }

    public function testInstall() {
      Optimize_install();
      $actions = MockedActions::get();
      $this->assertEquals(1, count($actions));
      $this->assertEquals('save', $actions[0]['action']);
      $this->assertEquals('raspberry-config-file', $actions[0]['key']);
    }

    public function testUpdate() {
      Optimize_update();
      $actions = MockedActions::get();
      $this->assertEquals(0, count($actions));
    }

    public function testRemove() {
      Optimize_remove();
      $actions = MockedActions::get();
      $this->assertEquals(1, count($actions));
      $this->assertEquals('remove', $actions[0]['action']);
      $this->assertEquals('raspberry-config-file', $actions[0]['key']);
    }
}
