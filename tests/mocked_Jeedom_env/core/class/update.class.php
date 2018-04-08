<?php

require_once('../../mocked_core.php');

class update
{
    /**
     * @var mixed
     */
    public static $byIdResult = null;

    /**
     * @var mixed
     */
    public static $byLogicalIdResult = null;

    /**
     *
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public static function byId($id)
    {
        return static::$byIdResult;
    }

    /**
     *
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public static function byLogicalId($id)
    {
        return static::$byLogicalIdResult;
    }
}

