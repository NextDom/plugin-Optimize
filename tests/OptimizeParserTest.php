<?php

use PHPUnit\Framework\TestCase;

require_once('core/class/OptimizeParser.class.php');
require_once('JeedomMock.php');

class OptimizeParserTest extends TestCase
{
    protected function setUp()
    {
        $this->parser = new OptimizeParser();
    }

    protected function tearDown()
    {
        unset($this->parser);
        scenario::clearMockedActions();
        scenario::$activatedScenarios = true;
    }

    public function testParseScenarioLog()
    {
        $this->parser->parse('scenario', 1, 'log');
        $this->assertEquals(2, count(scenario::$mockedActions));
        $this->assertEquals('configuration', scenario::$mockedActions[0]['action']);
        $this->assertEquals('logmode', scenario::$mockedActions[0]['type']);
        $this->assertEquals('none', scenario::$mockedActions[0]['value']);
        $this->assertEquals('save', scenario::$mockedActions[1]['action']);
    }

    public function testParseScenarioSyncMode()
    {
        $this->parser->parse('scenario', 1, 'syncmode');
        $this->assertEquals(2, count(scenario::$mockedActions));
        $this->assertEquals('configuration', scenario::$mockedActions[0]['action']);
        $this->assertEquals('syncmode', scenario::$mockedActions[0]['type']);
        $this->assertEquals(1, scenario::$mockedActions[0]['value']);
        $this->assertEquals('save', scenario::$mockedActions[1]['action']);
    }

    public function testParseScenarioEnabledWithActivatedScenario()
    {
        $this->parser->parse('scenario', 1, 'enabled');
        $this->assertEquals(0, count(scenario::$mockedActions));
    }

    public function testParseScenarioEnabledWithDisabledScenario()
    {
        scenario::$activatedScenarios = false;
        $this->parser->parse('scenario', 1, 'enabled');
        $this->assertEquals(1, count(scenario::$mockedActions));
        $this->assertEquals('remove', scenario::$mockedActions[0]['action']);
    }
}
