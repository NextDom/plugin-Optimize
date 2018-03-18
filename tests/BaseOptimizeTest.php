<?php

use PHPUnit\Framework\TestCase;

require 'core/class/BaseOptimize.class.php';

class BaseOptimizeTest extends TestCase
{
    public function testGetCurrentScore()
    {
        $baseOptimize = new BaseOptimize();
        $reflectionClass = new ReflectionClass(BaseOptimize::class);
        $reflectionPropertyBadPoints = $reflectionClass->getProperty('badPoints');
        $reflectionPropertyBadPoints->setAccessible(true);
        $reflectionPropertyBadPoints->setValue($baseOptimize, 5);
        $reflectionPropertyBestScore = $reflectionClass->getProperty('bestScore');
        $reflectionPropertyBestScore->setAccessible(true);
        $reflectionPropertyBestScore->setValue($baseOptimize, 20);
        $this->assertEquals(15, $baseOptimize->getCurrentScore());
    }

    public function testGetJeedomRootDirectory()
    {
        $baseOptimize = new BaseOptimize();
        $this->assertEquals($baseOptimize->getJeedomRootDirectory(), realpath(dirname(__FILE__).'/../../../'));
    }
}
