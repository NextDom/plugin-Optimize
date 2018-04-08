<?php

require_once('../../mocked_core.php');

class update
{
    public static $byIdResult = null;

    public static $byLogicalIdResult = null;

    public static function byId($id)
    {
        return static::$byIdResult;
    }

    public static function byLogicalId($id)
    {
        return static::$byLogicalIdResult;
    }
}

