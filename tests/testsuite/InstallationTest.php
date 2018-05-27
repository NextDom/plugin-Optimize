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
        $this->assertCount(6, $actions);
        $this->assertEquals('save', $actions[0]['action']);
        $this->assertEquals('raspberry-config-file', $actions[0]['content']['key']);
        $this->assertEquals('save', $actions[1]['action']);
        $this->assertEquals('minify', $actions[1]['content']['key']);
        $this->assertEquals('save', $actions[2]['action']);
        $this->assertEquals('scenario-days-limit', $actions[2]['content']['key']);
        $this->assertEquals('save', $actions[3]['action']);
        $this->assertEquals('show-disclaimer', $actions[3]['content']['key']);
        $this->assertEquals('query_execute', $actions[4]['action']);
        $this->assertEquals('query_execute', $actions[5]['action']);
        $this->assertContains('CREATE TABLE', $actions[5]['content']['query']);
    }

    public function testUpdate()
    {
        Optimize_update();
        $actions = MockedActions::get();
        $this->assertCount(1, $actions);
        $this->assertEquals('save', $actions[0]['action']);
        $this->assertEquals('scenario-days-limit', $actions[0]['content']['key']);
    }

    public function testRemove()
    {
        Optimize_remove();
        $actions = MockedActions::get();
        $this->assertCount(5, $actions);
        $this->assertEquals('remove', $actions[0]['action']);
        $this->assertEquals('raspberry-config-file', $actions[0]['content']['key']);
        $this->assertEquals('Optimize', $actions[0]['content']['plugin']);
        $this->assertEquals('remove', $actions[1]['action']);
        $this->assertEquals('minify', $actions[1]['content']['key']);
        $this->assertEquals('remove', $actions[2]['action']);
        $this->assertEquals('scenario-days-limit', $actions[2]['content']['key']);
        $this->assertEquals('remove', $actions[3]['action']);
        $this->assertEquals('show-disclaimer', $actions[3]['content']['key']);
        $this->assertEquals('query_execute', $actions[4]['action']);
        $this->assertContains('DROP TABLE', $actions[4]['content']['query']);
    }
}
