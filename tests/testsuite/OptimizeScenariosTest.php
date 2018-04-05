<?php

use PHPUnit\Framework\TestCase;

require_once('../../core/class/scenario.class.php');
require_once('core/class/OptimizeScenarios.class.php');

class OptimizeScenariosTest extends TestCase
{
    public $optimize;

    protected function setUp()
    {
        $this->optimize = new OptimizeScenarios();
        scenario::init();
    }

    protected function tearDown()
    {
        unset($this->optimize);
        scenarioItem::$enabledScenario = null;
        MockedActions::clear();
    }

    public function testScenariosGetInformations()
    {
        $result = $this->optimize->getInformations();
        $this->assertEquals(4, count($result));
        $this->assertEquals(1, $result[0]['id']);
        $this->assertEquals('Second scenario', $result[1]['name']);
        $this->assertEquals('realtime', $result[1]['log']);
        $this->assertEquals(0, $result[2]['syncmode']);
        $this->assertEquals(0, $result[3]['enabled']);
        $this->assertEquals(array('log' => 'ok', 'syncmode' => 'ok', 'enabled' => 'ok'), $result[0]['rating']);
        $this->assertEquals(array('log' => 'warn', 'syncmode' => 'ok', 'enabled' => 'ok'), $result[1]['rating']);
        $this->assertEquals(array('log' => 'ok', 'syncmode' => 'warn', 'enabled' => 'ok'), $result[2]['rating']);
        $this->assertEquals(array('log' => 'ok', 'syncmode' => 'ok', 'enabled' => 'warn'), $result[3]['rating']);
    }


    public function testScenarioDisableLogs()
    {
        $this->optimize->disableLogs(2);
        $actions = MockedActions::get();
        $this->assertEquals(2, count($actions));
        $this->assertEquals('set_configuration', $actions[0]['action']);
        $this->assertEquals('logmode', $actions[0]['type']);
        $this->assertEquals('none', $actions[0]['value']);
    }

    public function testScenarioSetSyncMode()
    {
        $this->optimize->setSyncMode(2);
        $actions = MockedActions::get();
        $this->assertEquals(2, count($actions));
        $this->assertEquals('set_configuration', $actions[0]['action']);
        $this->assertEquals('syncmode', $actions[0]['type']);
        $this->assertEquals(1, $actions[0]['value']);
        $this->assertEquals('save', $actions[1]['action']);
    }

    public function testScenarioDisableWithEnabledScenario()
    {
        $this->optimize->removeIfDisabled(1);
        $actions = MockedActions::get();
        $this->assertEquals(0, count($actions));
    }

    public function testScenarioDisableWithDisabledScenario()
    {
        $this->optimize->removeIfDisabled(4);
        $actions = MockedActions::get();
        $this->assertEquals(1, count($actions));
        $this->assertEquals('remove', $actions[0]['action']);
    }
}
