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

class OptimizeAjaxTest extends TestCase
{
    public $dataStorage;

    protected function setUp()
    {
        JeedomVars::$isConnected = true;
    }

    protected function tearDown()
    {
    }

    public function testNotConnected()
    {
        JeedomVars::$isConnected = false;
        include(dirname(__FILE__) . '/../core/ajax/Optimize.ajax.php');
        $actions = MockedActions::get();
        $this->assertCount(2, $actions);
        $this->assertEquals('include_file', $actions[0]['action']);
        $this->assertEquals('authentification', $actions[0]['content']['name']);
        $this->assertEquals('ajax_error', $actions[1]['action']);
    }

    public function testConnected()
    {
        scenario::init();
        JeedomVars::$initAnswers = array('category' => 'scenario', 'id' => 1, 'type' => 'log');
        include(dirname(__FILE__) . '/../core/ajax/Optimize.ajax.php');
        $actions = MockedActions::get();
        $this->assertCount(6, $actions);
        $this->assertEquals('include_file', $actions[0]['action']);
        $this->assertEquals('authentification', $actions[0]['content']['name']);
        $this->assertEquals('ajax_init', $actions[1]['action']);
        $this->assertEquals('set_configuration', $actions[2]['action']);
        $this->assertEquals('save', $actions[3]['action']);
        $this->assertEquals('ajax_success', $actions[4]['action']);
    }
}
