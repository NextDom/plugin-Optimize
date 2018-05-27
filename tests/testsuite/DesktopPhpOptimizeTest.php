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

require_once('../../core/php/core.inc.php');

class DesktopPhpOptimizeTest extends TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testNotConnected()
    {
        scenario::init();
        JeedomVars::$isConnected = false;
        try {
            include(dirname(__FILE__) . '/../desktop/php/Optimize.php');
            $this->fail("L'exception n'a pas été déclenchée.");
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), '401 - Refused access');
        }
    }

    public function testWithUserConnected()
    {
        config::$byKeyPluginData = array('Optimize' => array('scenario-days-limit' => 30));
        scenario::init();
        ob_start();
        include(dirname(__FILE__) . '/../desktop/php/Optimize.php');
        $content = ob_get_clean();
        $actions = MockedActions::get();
        $this->assertCount(5, $actions);
        $this->assertEquals('include_file', $actions[0]['action']);
        $this->assertEquals('authentification', $actions[0]['content']['name']);
        $this->assertEquals('include_file', $actions[1]['action']);
        $this->assertEquals('Optimize', $actions[1]['content']['name']);
        $this->assertEquals('css', $actions[1]['content']['type']);
        $this->assertEquals('include_file', $actions[2]['action']);
        $this->assertEquals('Optimize', $actions[2]['content']['name']);
        $this->assertEquals('js', $actions[2]['content']['type']);
        $this->assertEquals('sendVarToJs', $actions[3]['action']);
        $this->assertEquals('eqType', $actions[3]['content']['var']);
        $this->assertEquals('Optimize', $actions[3]['content']['value']);
        $this->assertEquals('include_file', $actions[4]['action']);
        $this->assertEquals('plugin.template', $actions[4]['content']['name']);
        $this->assertContains('<ul class="nav nav-tabs">', $content);
    }
}
