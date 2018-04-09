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
        $this->dataStorage = new DataStorage('phpunit_test');
    }

    protected function tearDown()
    {
    }

    public function testCreateDataTableOnEmptyDatabase()
    {
      $this->assertFalse($this->dataStorage->createDataTable());
    }
}
