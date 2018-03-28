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
        scenarioItem::$enabledScenario = null;
        MockedActions::clear();
    }

    public function testParserScenarioLog()
    {
        scenario::all();
        $this->parser->parse('scenario', 1, 'log');
        $actions = MockedActions::get();
        $this->assertEquals(2, count($actions));
        $this->assertEquals('set_configuration', $actions[0]['action']);
        $this->assertEquals('logmode', $actions[0]['type']);
        $this->assertEquals('none', $actions[0]['value']);
        $this->assertEquals('save', $actions[1]['action']);
    }

    public function testParserScenarioSyncMode()
    {
        $this->parser->parse('scenario', 2, 'syncmode');
        $actions = MockedActions::get();
        $this->assertEquals(2, count($actions));
        $this->assertEquals('set_configuration', $actions[0]['action']);
        $this->assertEquals('syncmode', $actions[0]['type']);
        $this->assertEquals(1, $actions[0]['value']);
        $this->assertEquals('save', $actions[1]['action']);
    }

    public function testParserScenarioEnabledWithActivatedScenario()
    {
        scenarioItem::$enabledScenario = true;
        $this->parser->parse('scenario', 3, 'enabled');
        $actions = MockedActions::get();
        $this->assertEquals(0, count($actions));
    }

    public function testParserScenarioEnabledWithDisabledScenario()
    {
        scenarioItem::$enabledScenario = false;
        $this->parser->parse('scenario', 4, 'enabled');
        $actions = MockedActions::get();
        $this->assertEquals(1, count($actions));
        $this->assertEquals('remove', $actions[0]['action']);
    }

    public function testParserPluginLog() {
        $this->parser->parse('plugin', 'template', 'log');
        $this->parser->parse('plugin', 'Optimize', 'log');
        $actions = MockedActions::get();
        $this->assertEquals(2, count($actions));
        $this->assertEquals('save', $actions[0]['action']);
        $this->assertEquals('log::level::template', $actions[0]['key']);
        $this->assertEquals('save', $actions[1]['action']);
        $this->assertEquals('log::level::Optimize', $actions[1]['key']);
    }

    public function testParserSystemLog()
    {
        $this->parser->parse('system', 'scenario', 'log');
        $this->parser->parse('system', 'plugin', 'log');
        $actions = MockedActions::get();
        $this->assertEquals(2, count($actions));
        $this->assertEquals('save', $actions[0]['action']);
        $this->assertEquals('log::level::scenario', $actions[0]['key']);
        $this->assertEquals('save', $actions[1]['action']);
        $this->assertEquals('log::level::plugin', $actions[1]['key']);
    }

    /**
     * Test seulement que certains appels sont passés sur des fonctions
     *
     * Nécessité de détecter la commande exec pour approfondir
     */
    public function testParserSystemInstall()
    {
        $result = $this->parser->parse('system', 'csscompressor', 'install');
        $this->assertTrue($result);
        $result = $this->parser->parse('system', 'jsmin', 'install');
        $this->assertTrue($result);
        $result = $this->parser->parse('system', 'bad_item', 'install');
        $this->assertFalse($result);
        $actions = MockedActions::get();
        $this->assertEquals(2, count($actions));
        $this->assertEquals('get_cmd_sudo', $actions[0]['action']);
        $this->assertEquals('get_cmd_sudo', $actions[1]['action']);
    }
}
