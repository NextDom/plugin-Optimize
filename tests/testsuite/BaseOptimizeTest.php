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

use PHPUnit\Framework\TestCase;

require_once('../../core/php/core.inc.php');
require_once('core/class/BaseOptimize.class.php');

class BaseOptimizeMocked extends BaseOptimize
{
    public function setBadPoints($points)
    {
        self::$badPoints = $points;
    }

    public function setBestScore($bestScore)
    {
        self::$bestScore = $bestScore;
    }
}

class BaseOptimizeTest extends TestCase
{
    public function testInitEffects()
    {
        BaseOptimize::initScore();
        $this->assertEquals(0, BaseOptimize::getBestScore());
        $this->assertEquals(0, BaseOptimize::getCurrentScore());
    }

    public function testCanSudoOk()
    {
        $testBaseOptimize = new BaseOptimizeMocked();
        jeedom::$isCapableAnswer = true;
        $this->assertTrue($testBaseOptimize->canSudo());
    }

    public function testCanSudoBad()
    {
        $testBaseOptimize = new BaseOptimizeMocked();
        jeedom::$isCapableAnswer = false;
        $this->assertFalse($testBaseOptimize->canSudo());
    }

    public function testGetCurrentScore()
    {
        $testBaseOptimize = new BaseOptimizeMocked();
        $testBaseOptimize->setBadPoints(5);
        $testBaseOptimize->setBestScore(20);
        $this->assertEquals(15, $testBaseOptimize->getCurrentScore());
    }

    public function testGetBestScore()
    {
        $testBaseOptimize = new BaseOptimizeMocked();
        $testBaseOptimize->setBestScore(25);
        $this->assertEquals(25, $testBaseOptimize->getBestScore());
    }

    public function testCanSudoTrue()
    {
        jeedom::$isCapableAnswer = true;
        $this->assertTrue(jeedom::$isCapableAnswer);
    }

    public function testCanSudoFalse()
    {
        jeedom::$isCapableAnswer = false;
        $this->assertFalse(jeedom::$isCapableAnswer);
    }

    public function testGetJeedomRootDirectory()
    {
        $baseOptimize = new BaseOptimize();
        $this->assertEquals(realpath(dirname(__FILE__) . '/../../../'), $baseOptimize->getJeedomRootDirectory());
    }
}
