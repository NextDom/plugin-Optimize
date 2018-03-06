<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
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
}
