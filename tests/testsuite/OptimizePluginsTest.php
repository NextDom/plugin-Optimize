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
require_once('core/class/OptimizePlugins.class.php');

class OptimizePluginsTest extends TestCase
{
    public $optimize;

    protected function setUp()
    {
        $this->optimize = new OptimizePlugins();
        MockedActions::clear();
    }

    protected function tearDown()
    {
        unset($this->optimize);
    }

    public function testGetInformations()
    {
        $result = $this->optimize->getInformations();
        $this->assertCount(3, $result);
        $this->assertEquals('TheTemplate', $result[0]['name']);
        $this->assertEquals('IOptimize', $result[1]['id']);
        $this->assertEquals(true, $result[1]['log']);
        $this->assertEquals(false, $result[2]['log']);
        $this->assertEquals(1, $result[0]['enabled']);
        $this->assertEquals(0, $result[1]['enabled']);
        $this->assertEquals('MockedPlugins/IOptimize/plugin_info/info.json', $result[1]['filepath']);
        $this->assertEquals(array('log' => 'warn', 'path' => 'warn', 'enabled' => 'ok'), $result[0]['rating']);
        $this->assertEquals(array('log' => 'warn', 'path' => 'warn', 'enabled' => 'warn'), $result[1]['rating']);
        $this->assertEquals(array('log' => 'ok', 'path' => 'warn', 'enabled' => 'ok'), $result[2]['rating']);
    }

    public function testRemoveIfDisabledWithoutUninstall()
    {
        update::$byIdResult = 'IOptimize';
        update::$byLogicalIdResult = 'IOptimize';
        mkdir('../IOptimize');
        $this->assertDirectoryExists('../IOptimize');
        $this->optimize->removeIfDisabled('IOptimize');
        $this->assertDirectoryNotExists('../IOptimize');
    }

    public function testRemoveIfDisabledWithUninstall()
    {
        $installationContent = "<?php
        function IOptimize_remove() {
        MockedActions::add('remove_plugin');
        }
        ";
        update::$byIdResult = 'IOptimize';
        update::$byLogicalIdResult = 'IOptimize';
        mkdir('../IOptimize');
        mkdir('../IOptimize/plugin_info');
        file_put_contents('../IOptimize/plugin_info/install.php', $installationContent);
        $this->assertDirectoryExists('../IOptimize');
        $this->optimize->removeIfDisabled('IOptimize');
        $this->assertDirectoryNotExists('../IOptimize');
        $result = MockedActions::get();
        $this->assertCount(1, $result);
        $this->assertEquals('remove_plugin', $result[0]['action']);
    }
}
