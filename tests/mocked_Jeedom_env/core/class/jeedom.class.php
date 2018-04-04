<?php

/**
 * Mock de la classe Jeddom
 */
class jeedom
{
    public static $sudoAnswer = false;

    public static $hardwareName;

    public static function getHardwareName()
    {
        return jeedom::$hardwareName;
    }

    public static function isCapable($str)
    {
        return self::$sudoAnswer;
    }
}
