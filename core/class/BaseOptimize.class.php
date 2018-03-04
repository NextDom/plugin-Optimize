<?php
/**
 * Created by PhpStorm.
 * User: dangin
 * Date: 04/03/2018
 * Time: 17:35
 */

class BaseOptimize
{
    /**
     * @var int Score obtenu
     */
    protected static $badPoints;

    /**
     * @var int Meilleur score
     */
    protected static $bestScore;

    /**
     * Initialisation du score
     */
    public static function initScore()
    {
        self::$badPoints = 0;
        self::$bestScore = 0;
    }

    /**
     * Obtenir le score obtenu
     *
     * @return int Score obtenu
     */
    public static function getCurrentScore()
    {
        return self::$bestScore - self::$badPoints;
    }

    /**
     * Obtenir le meilleur score
     *
     * @return int Meilleur score
     */
    public static function getBestScore()
    {
        return self::$bestScore;
    }
}
