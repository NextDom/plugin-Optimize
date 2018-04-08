<?php

use PHPUnit\Framework\TestCase;

require_once('../../core/class/config.class.php');
require_once('core/class/OptimizeSystem.class.php');

class MockedOptimizeSystem extends OptimizeSystem
{
    public function mock_isFileNotBeMinify($filePath, $fileHash)
    {
        return $this->isFileNotBeMinify($filePath, $fileHash);
    }

    public function mock_storeFileHash($filePath) {
        return $this->storeFileHash($filePath);
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
        $this->assertEquals($result[7]['rating']['log'], 'ok');
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
        $this->assertEquals(1, count($actions));
        $this->assertEquals('query_execute', $actions[0]['action']);
        $this->assertContains('SELECT', $actions[0]['content']['query']);
    }

    public function testIsFileNotBeMinifyWithoutData()
    {
        $hash = md5_file(__FILE__);
        $result = $this->optimizeSystem->mock_isFileNotBeMinify(__FILE__, $hash);
        $this->assertTrue($result);
        $actions = MockedActions::get();
        $this->assertEquals(1, count($actions));
        $this->assertEquals('query_execute', $actions[0]['action']);
        $this->assertContains('SELECT', $actions[0]['content']['query']);
    }

    public function testStoreFileHashNewFile() {
        $this->optimizeSystem->mock_storeFileHash(__FILE__);
        $actions = MockedActions::get();
        $this->assertEquals(2, count($actions));
        $this->assertEquals('query_execute', $actions[0]['action']);
        $this->assertEquals('query_execute', $actions[1]['action']);
        $this->assertContains('INSERT INTO', $actions[1]['content']['query']);
        $this->assertEquals(array(__FILE__, md5_file(__FILE__)), $actions[1]['content']['data']);
    }

    public function testStoreFileHashOldFile() {
        DB::setAnswer(array('data' => md5('test_for_hash')));
        $this->optimizeSystem->mock_storeFileHash(__FILE__);
        $actions = MockedActions::get();
        $this->assertEquals(2, count($actions));
        $this->assertEquals('query_execute', $actions[0]['action']);
        $this->assertEquals('query_execute', $actions[1]['action']);
        $this->assertContains('UPDATE', $actions[1]['content']['query']);
        $this->assertEquals(array(md5_file(__FILE__), __FILE__), $actions[1]['content']['data']);
    }
}
