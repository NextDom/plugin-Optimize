<?php
/*
use PHPUnit\Framework\TestCase;

require_once('core/class/DataStorage.class.php');

class DB
{
    private static $connection = null;

    public static function init()
    {
        static::$connection = new PDO('sqlite::memory:');
    }

    public static function getConnection()
    {
        return static::connection;
    }
}

class DataStorageTest extends TestCase
{
    public $optimize;

    protected function setUp()
    {
        DB::init();
    }

    protected function tearDown()
    {
    }

    public function testCreateDataTableWithEmptyDatabase()
    {

    }
}
*/