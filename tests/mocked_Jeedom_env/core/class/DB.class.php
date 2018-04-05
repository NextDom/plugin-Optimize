<?php

class DB
{
    private static $connection = null;

    public static function init()
    {
        static::$connection = new PDO('sqlite::memory:');
    }

    public static function getConnection()
    {
        return static::$connection;
    }
}
