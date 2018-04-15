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
        $this->assertCount(4, $result);
        $this->assertEquals(1, $result[0]['id']);
        $this->assertEquals('Second scenario', $result[1]['name']);
        $this->assertEquals('realtime', $result[1]['log']);
        $this->assertEquals(0, $result[2]['syncmode']);
        $this->assertEquals(0, $result[3]['enabled']);
        $this->assertEquals(array('log' => 'ok', 'syncmode' => 'ok', 'enabled' => 'ok', 'last_launch' => 'ok'), $result[0]['rating']);
        $this->assertEquals(array('log' => 'warn', 'syncmode' => 'ok', 'enabled' => 'ok', 'last_launch' => 'ok'), $result[1]['rating']);
        $this->assertEquals(array('log' => 'ok', 'syncmode' => 'warn', 'enabled' => 'ok', 'last_launch' => 'ok'), $result[2]['rating']);
        $this->assertEquals(array('log' => 'ok', 'syncmode' => 'ok', 'enabled' => 'warn', 'last_launch' => 'ok'), $result[3]['rating']);
    }

    public function testScenariosGetInformationsLastLaunchOldies()
    {
        scenarioItem::$lastLaunch = new \DateTime('1988-08-01');
        $result = $this->optimize->getInformations();
        $this->assertCount(4, $result);
        $this->assertEquals(array('log' => 'ok', 'syncmode' => 'ok', 'enabled' => 'ok', 'last_launch' => 'warn'), $result[0]['rating']);
        $this->assertEquals(array('log' => 'warn', 'syncmode' => 'ok', 'enabled' => 'ok', 'last_launch' => 'warn'), $result[1]['rating']);
        $this->assertEquals(array('log' => 'ok', 'syncmode' => 'warn', 'enabled' => 'ok', 'last_launch' => 'warn'), $result[2]['rating']);
        $this->assertEquals(array('log' => 'ok', 'syncmode' => 'ok', 'enabled' => 'warn', 'last_launch' => 'warn'), $result[3]['rating']);
    }

    public function testScenarioDisableLogs()
    {
        $this->optimize->disableLogs(2);
        $actions = MockedActions::get();
        $this->assertCount(2, $actions);
        $this->assertEquals('set_configuration', $actions[0]['action']);
        $this->assertEquals('logmode', $actions[0]['content']['config']);
        $this->assertEquals('none', $actions[0]['content']['value']);
    }

    public function testScenarioDisableAllLogs()
    {
        $this->optimize->disableLogs('optimize-all');
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

    public function testScenarioSetSyncMode()
    {
        $this->optimize->setSyncMode(2);
        $actions = MockedActions::get();
        $this->assertCount(2, $actions);
        $this->assertEquals('set_configuration', $actions[0]['action']);
        $this->assertEquals('syncmode', $actions[0]['content']['config']);
        $this->assertEquals(1, $actions[0]['content']['value']);
        $this->assertEquals('save', $actions[1]['action']);
    }

    public function testScenarioDisableWithEnabledScenario()
    {
        $this->optimize->removeIfDisabled(1);
        $actions = MockedActions::get();
        $this->assertCount(0, $actions);
    }

    public function testScenarioDisableWithDisabledScenario()
    {
        $this->optimize->removeIfDisabled(4);
        $actions = MockedActions::get();
        $this->assertCount(1, $actions);
        $this->assertEquals('remove', $actions[0]['action']);
    }
}
