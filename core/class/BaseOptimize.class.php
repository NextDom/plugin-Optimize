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

    /**
     * Test si Jeedom peut exécuter des actions demandant des privilèges.
     *
     * @return bool true si c'est possible
     */
    public function canSudo()
    {
        return jeedom::isCapable('sudo');
    }

    /**
     * Obtenir le répertoire racine de Jeedom
     *
     * @return bool|string Chemin de la racine de Jeedom
     */
    public function getJeedomRootDirectory()
    {
        return realpath(dirname(__FILE__) . '/../../../../');
    }
}
