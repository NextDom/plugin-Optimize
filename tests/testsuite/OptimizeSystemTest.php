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

require_once('../../core/class/config.class.php');
require_once('core/class/OptimizeSystem.class.php');

class MockedOptimizeSystem extends OptimizeSystem
{
    public function mock_isFileNotBeMinify($filePath, $fileHash)
    {
        return $this->isFileNotBeMinify($filePath, $fileHash);
    }

    public function mock_storeFileHash($filePath)
    {
        return $this->storeFileHash($filePath);
    }

    public function mock_findFilesRecursively($path, $extension)
    {
        return $this->findFilesRecursively($path, $extension);
    }
}

class OptimizeSystemTest extends TestCase
{
    private $optimizeSystem;

    public function setUp()
    {
        DB::init();
        $this->optimizeSystem = new MockedOptimizeSystem();
    }

    public function testGetLogInformations()
    {
        $result = $this->optimizeSystem->getLogInformations();
        $this->assertEquals($result[0]['name'], 'Scenario');
        $this->assertEquals($result[0]['rating']['log'], 'warn');
        $this->assertEquals($result[1]['name'], 'Plugin');
        $this->assertEquals($result[1]['rating']['log'], 'warn');
        $this->assertEquals($result[2]['name'], 'Market');
        $this->assertEquals($result[2]['rating']['log'], 'warn');
        $this->assertEquals($result[3]['name'], 'Api');
        $this->assertEquals($result[3]['rating']['log'], 'warn');
        $this->assertEquals($result[4]['name'], 'Connection');
        $this->assertEquals($result[4]['rating']['log'], 'warn');
        $this->assertEquals($result[5]['name'], 'Interact');
        $this->assertEquals($result[5]['rating']['log'], 'ok');
        $this->assertEquals($result[6]['name'], 'TTS');
        $this->assertEquals($result[6]['rating']['log'], 'warn');
        $this->assertEquals($result[7]['name'], 'Report');
        $this->assertEquals($result[7]['rating']['log'], 'warn');
        $this->assertEquals($result[8]['name'], 'Event');
        $this->assertEquals($result[8]['rating']['log'], 'warn');
    }

    public function testIsFileNotBeMinifyWithData()
    {
        $hash = md5_file(__FILE__);
        DB::setAnswer(array('data' => $hash));
        $result = $this->optimizeSystem->mock_isFileNotBeMinify(__FILE__, $hash);
        $this->assertFalse($result);
        $actions = MockedActions::get();
        $this->assertCount(1, $actions);
        $this->assertEquals('query_execute', $actions[0]['action']);
        $this->assertContains('SELECT', $actions[0]['content']['query']);
    }

    public function testIsFileNotBeMinifyWithoutData()
    {
        $hash = md5_file(__FILE__);
        $result = $this->optimizeSystem->mock_isFileNotBeMinify(__FILE__, $hash);
        $this->assertTrue($result);
        $actions = MockedActions::get();
        $this->assertCount(1, $actions);
        $this->assertEquals('query_execute', $actions[0]['action']);
        $this->assertContains('SELECT', $actions[0]['content']['query']);
    }

    public function testStoreFileHashNewFile()
    {
        $this->optimizeSystem->mock_storeFileHash(__FILE__);
        $actions = MockedActions::get();
        $this->assertCount(2, $actions);
        $this->assertEquals('query_execute', $actions[0]['action']);
        $this->assertEquals('query_execute', $actions[1]['action']);
        $this->assertContains('INSERT INTO', $actions[1]['content']['query']);
        $this->assertEquals(array(__FILE__, md5_file(__FILE__)), $actions[1]['content']['data']);
    }

    public function testStoreFileHashOldFile()
    {
        DB::setAnswer(array('data' => md5('test_for_hash')));
        $this->optimizeSystem->mock_storeFileHash(__FILE__);
        $actions = MockedActions::get();
        $this->assertCount(2, $actions);
        $this->assertEquals('query_execute', $actions[0]['action']);
        $this->assertEquals('query_execute', $actions[1]['action']);
        $this->assertContains('UPDATE', $actions[1]['content']['query']);
        $this->assertEquals(array(md5_file(__FILE__), __FILE__), $actions[1]['content']['data']);
    }

    public function testFindFilesRecursively()
    {
        $result = $this->optimizeSystem->mock_findFilesRecursively(dirname(__FILE__) . '/../../../core', 'php');
        $this->assertContains(dirname(__FILE__) . '/../../../core/class/config.class.php', $result);
        $this->assertContains(dirname(__FILE__) . '/../../../core/class/jeedom.class.php', $result);
        $this->assertCount(13, $result);
    }

    public function testFindFilesRecursivelyWithIgnore()
    {
        mkdir('for_test');
        mkdir('for_test/plouf');
        file_put_contents('for_test/test1.js', '');
        file_put_contents('for_test/test1.css', '');
        file_put_contents('for_test/plouf/test2.js', '');
        file_put_contents('for_test/plouf/.optimize-ignore', '');
        $result = $this->optimizeSystem->mock_findFilesRecursively('for_test', 'js');
        $this->assertContains('for_test/test1.js', $result);
        $this->assertNotContains('for_test/plouf/test2.js', $result);
        $this->assertCount(1, $result);
        unlink('for_test/test1.js');
        unlink('for_test/test1.css');
        unlink('for_test/plouf/test2.js');
        unlink('for_test/plouf/.optimize-ignore');
        rmdir('for_test/plouf');
        rmdir('for_test');
    }


    public function testDisableLogs()
    {
        $this->optimizeSystem->disableLogs('scenario');
        $actions = MockedActions::get();
        $this->assertCount(1, $actions);
        $this->assertEquals('save', $actions[0]['action']);
        $this->assertEquals('log::level::scenario', $actions[0]['content']['key']);
        $this->assertEquals(1, $actions[0]['content']['data'][1000]);
    }

    public function testDisableAllLogs()
    {
        $this->optimizeSystem->disableLogs('optimize-all');
        $actions = MockedActions::get();
        $this->assertCount(9, $actions);
        $this->assertEquals('save', $actions[0]['action']);
        $this->assertEquals('log::level::scenario', $actions[0]['content']['key']);
        $this->assertEquals(1, $actions[0]['content']['data'][1000]);
        $this->assertEquals('save', $actions[1]['action']);
        $this->assertEquals('log::level::plugin', $actions[1]['content']['key']);
        $this->assertEquals(1, $actions[1]['content']['data'][1000]);
        $this->assertEquals('save', $actions[2]['action']);
        $this->assertEquals('log::level::market', $actions[2]['content']['key']);
        $this->assertEquals(1, $actions[2]['content']['data'][1000]);
    }
}
