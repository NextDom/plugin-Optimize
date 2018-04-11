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
        $result = null;
        if (is_array(static::$byLogicalIdResult)) {
            $result = static::$byLogicalIdResult[$id];
        }
        else {
            $result = static::$byLogicalIdResult;
        }
        return $result;
    }
}

