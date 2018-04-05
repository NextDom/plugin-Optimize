<?php

use PHPUnit\Framework\TestCase;

require_once ('../../core/php/core.inc.php');
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
    public function testInit()
    {
        $this->assertEquals(0, BaseOptimize::getBestScore());
        $this->assertEquals(0, BaseOptimize::getCurrentScore());
    }

    public function testCanSudoOk()
    {
        $testBaseOptimize = new BaseOptimizeMocked();
        jeedom::$sudoAnswer = true;
        $this->assertTrue($testBaseOptimize->canSudo());
    }

    public function testCanSudoBad()
    {
        $testBaseOptimize = new BaseOptimizeMocked();
        jeedom::$sudoAnswer = false;
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
        jeedom::$sudoAnswer = true;
        $this->assertTrue(jeedom::$sudoAnswer);
    }

    public function testCanSudoFalse()
    {
        jeedom::$sudoAnswer = false;
        $this->assertFalse(jeedom::$sudoAnswer);
    }

    public function testGetJeedomRootDirectory()
    {
        $baseOptimize = new BaseOptimize();
        $this->assertEquals(realpath(dirname(__FILE__) . '/../../../'), $baseOptimize->getJeedomRootDirectory());
    }
}
