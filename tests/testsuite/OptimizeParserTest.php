<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

use PHPUnit\Framework\TestCase;

require_once('../../core/class/system.class.php');
require_once('core/class/OptimizeParser.class.php');

class OptimizeParserTest extends TestCase
{
    private $parser = null;

    protected function setUp()
    {
        $this->parser = new OptimizeParser();
        scenario::init();
    }

    protected function tearDown()
    {
        unset($this->parser);
        scenarioItem::$enabledScenario = null;
        MockedActions::clear();
    }

    public function testParserScenarioLog()
    {
        $this->parser->parse('scenario', 1, 'log');
        $actions = MockedActions::get();
        $this->assertCount(2, $actions);
        $this->assertEquals('set_configuration', $actions[0]['action']);
        $this->assertEquals('logmode', $actions[0]['content']['config']);
        $this->assertEquals('none', $actions[0]['content']['value']);
        $this->assertEquals('save', $actions[1]['action']);
    }

    public function testParserScenarioSyncMode()
    {
        $this->parser->parse('scenario', 2, 'syncmode');
        $actions = MockedActions::get();
        $this->assertCount(2, $actions);
        $this->assertEquals('set_configuration', $actions[0]['action']);
        $this->assertEquals('syncmode', $actions[0]['content']['config']);
        $this->assertEquals(1, $actions[0]['content']['value']);
        $this->assertEquals('save', $actions[1]['action']);
    }

    public function testParserScenarioEnabledWithActivatedScenario()
    {
        scenarioItem::$enabledScenario = true;
        $this->parser->parse('scenario', 3, 'enabled');
        $actions = MockedActions::get();
        $this->assertCount(0, $actions);
    }

    public function testParserScenarioEnabledWithDisabledScenario()
    {
        scenarioItem::$enabledScenario = false;
        $this->parser->parse('scenario', 4, 'enabled');
        $actions = MockedActions::get();
        $this->assertCount(1, $actions);
        $this->assertEquals('remove', $actions[0]['action']);
    }

    public function testParserScenarioAllLog()
    {
        $this->parser->parse('scenario', 'optimize-all', 'log');
        $actions = MockedActions::get();
        $this->assertCount(8, $actions);
        $this->assertEquals('set_configuration', $actions[0]['action']);
        $this->assertEquals('logmode', $actions[0]['content']['config']);
        $this->assertEquals('none', $actions[0]['content']['value']);
        $this->assertEquals('save', $actions[1]['action']);
        $this->assertEquals('set_configuration', $actions[2]['action']);
        $this->assertEquals('save', $actions[3]['action']);
        $this->assertEquals('set_configuration', $actions[4]['action']);
        $this->assertEquals('save', $actions[5]['action']);
        $this->assertEquals('set_configuration', $actions[6]['action']);
        $this->assertEquals('save', $actions[7]['action']);
    }

    public function testParserPluginLog()
    {
        $this->parser->parse('plugin', 'thetemplate', 'log');
        $this->parser->parse('plugin', 'IOptimize', 'log');
        $actions = MockedActions::get();
        $this->assertCount(2, $actions);
        $this->assertEquals('save', $actions[0]['action']);
        $this->assertEquals('log::level::thetemplate', $actions[0]['content']['key']);
        $this->assertEquals('save', $actions[1]['action']);
        $this->assertEquals(1, $actions[1]['content']['data'][1000]);
    }

    public function testParserPluginAllLog()
    {
        $this->parser->parse('plugin', 'optimize-all', 'log');
        $actions = MockedActions::get();
        $this->assertCount(3, $actions);
        $this->assertEquals('save', $actions[0]['action']);
        $this->assertEquals('log::level::thetemplate', $actions[0]['content']['key']);
        $this->assertEquals(1, $actions[0]['content']['data'][1000]);
        $this->assertEquals('save', $actions[1]['action']);
        $this->assertEquals('log::level::IOptimize', $actions[1]['content']['key']);
        $this->assertEquals(1, $actions[1]['content']['data'][1000]);
    }

    public function testParserSystemLog()
    {
        $this->parser->parse('system', 'scenario', 'log');
        $this->parser->parse('system', 'plugin', 'log');
        $actions = MockedActions::get();
        $this->assertCount(2, $actions);
        $this->assertEquals('save', $actions[0]['action']);
        $this->assertEquals('log::level::scenario', $actions[0]['content']['key']);
        $this->assertEquals('save', $actions[1]['action']);
        $this->assertEquals('log::level::plugin', $actions[1]['content']['key']);
    }

    public function testParserScenarioBadAction()
    {
        $result = $this->parser->parse('scenario', 'useless', 'an-error');
        $this->assertFalse($result);
    }

    public function testParserPluginBadAction()
    {
        $result = $this->parser->parse('plugin', 'useless', 'an-error');
        $this->assertFalse($result);
    }

    public function testParserSystemBadAction()
    {
        $result = $this->parser->parse('system', 'useless', 'an-error');
        $this->assertFalse($result);
    }

    public function testParserRaspberryBadAction()
    {
        $result = $this->parser->parse('raspberry', 'useless', 'an-error');
        $this->assertFalse($result);
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
        $this->assertCount(2, $actions);
        $this->assertEquals('get_cmd_sudo', $actions[0]['action']);
        $this->assertEquals('get_cmd_sudo', $actions[1]['action']);
    }
}
