<?php

use PHPUnit\Framework\TestCase;

require 'core/class/OptimizeRPi.class.php';

// Mocked jeedom
class jeedom
{
    public static $hardwareName;

    public static function getHardwareName()
    {
        return jeedom::$hardwareName;
    }
}

class OptimizeRPiTest extends TestCase
{
    private $optRpi = null;

    protected function setUp()
    {
        $this->optRpi = new OptimizeRPi();
    }

    protected function tearDown()
    {
        unset($this->optRpi);
    }

    public function testIsRaspberryPi()
    {
        jeedom::$hardwareName = 'RPi';
        $this->assertTrue($this->optRpi->isRaspberryPi());
        jeedom::$hardwareName = 'Raspberry';
        $this->assertTrue($this->optRpi->isRaspberryPi());
        jeedom::$hardwareName = 'Raspberry Pi';
        $this->assertTrue($this->optRpi->isRaspberryPi());
        jeedom::$hardwareName = 'DIY';
        $this->assertFalse($this->optRpi->isRaspberryPi());
        jeedom::$hardwareName = 'Linux';
        $this->assertFalse($this->optRpi->isRaspberryPi());
        jeedom::$hardwareName = 'Other';
        $this->assertFalse($this->optRpi->isRaspberryPi());
    }
/*
    public function testCanParseSystemConfigFile()
    {

    }

    public function testGetRating()
    {

    }

    public function testGetGpuMemOptimizationInformation()
    {

    }

    public function testGetL2CacheOptimizationInformation()
    {

    }

    public function testOptimizeGpuMem()
    {

    }

    public function testOptimizeL2Cache()
    {

    }
*/
}
