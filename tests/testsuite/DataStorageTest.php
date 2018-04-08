<?php

use PHPUnit\Framework\TestCase;

require_once('../../core/class/DB.class.php');
require_once('core/class/DataStorage.class.php');

class DataStorageTest extends TestCase
{
    public $dataStorage;

    protected function setUp()
    {
        DB::init();
        $this->dataStorage = new DataStorage('test_DB');
    }

    protected function tearDown()
    {
        MockedActions::clear();
    }

    public function testIsDataTableExistsWithEmptyDatabase()
    {
        DB::setAnswer(null);
        $this->assertFalse($this->dataStorage->isDataTableExists());
        $actions = MockedActions::get();
        $this->assertEquals(1, count($actions));
        $this->assertEquals('query_execute', $actions[0]['action']);
        $this->assertEquals('SHOW TABLES LIKE ?', $actions[0]['content']['query']);
        $this->assertEquals(array('data_test_DB'), $actions[0]['content']['data']);
    }

    public function testIsDataTableExistsWithCreatedTable()
    {
        DB::setAnswer(array('Tables_in_jeedom (data_test_DB)' => 'data_test_DB'));
        $this->assertTrue($this->dataStorage->isDataTableExists());
        $actions = MockedActions::get();
        $this->assertEquals(1, count($actions));
        $this->assertEquals('query_execute', $actions[0]['action']);
        $this->assertEquals('SHOW TABLES LIKE ?', $actions[0]['content']['query']);
        $this->assertEquals(array('data_test_DB'), $actions[0]['content']['data']);
    }

    public function testCreateDataTableWithEmptyDatabase()
    {
        $this->dataStorage->createDataTable();
        $actions = MockedActions::get();
        $this->assertEquals(2, count($actions));
        $this->assertEquals('query_execute', $actions[0]['action']);
        $this->assertEquals('query_execute', $actions[1]['action']);
        $this->assertContains('CREATE TABLE `data_test_DB`', $actions[1]['content']['query']);
    }

    public function testCreateDataTableWithCreatedTable()
    {
        DB::setAnswer(array('Tables_in_jeedom (data_test_DB)' => 'data_test_DB'));
        $this->dataStorage->createDataTable();
        $actions = MockedActions::get();
        $this->assertEquals(1, count($actions));
        $this->assertEquals('query_execute', $actions[0]['action']);
        $this->assertEquals('SHOW TABLES LIKE ?', $actions[0]['content']['query']);
        $this->assertEquals(array('data_test_DB'), $actions[0]['content']['data']);
    }
}
