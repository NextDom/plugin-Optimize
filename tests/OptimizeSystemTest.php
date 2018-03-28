<?php

use PHPUnit\Framework\TestCase;

require_once('core/class/OptimizeSystem.class.php');
require_once('JeedomMock.php');

class OptimizeSystemTest extends TestCase
{

    public function testGetLogInformations()
    {
        $optimizeSystem = new OptimizeSystem();
        $result = $optimizeSystem->getLogInformations();
        $this->assertEquals($result[0]['name'], 'Scenario');
        $this->assertEquals($result[0]['rating']['log'], 'warn');
        $this->assertEquals($result[1]['name'], 'Plugin');
        $this->assertEquals($result[1]['rating']['log'], 'warn');
        $this->assertEquals($result[2]['name'], 'Market');
        $this->assertEquals($result[2]['rating']['log'], 'warn');
        $this->assertEquals($result[3]['name'], 'Api');
        $this->assertEquals($result[3]['rating']['log'], 'warn');
        $this->assertEquals($result[4]['name'], 'Connection');
        $this->assertEquals($result[4]['rating']['log'], 'warn');
        $this->assertEquals($result[5]['name'], 'Interact');
        $this->assertEquals($result[5]['rating']['log'], 'ok');
        $this->assertEquals($result[6]['name'], 'TTS');
        $this->assertEquals($result[6]['rating']['log'], 'warn');
        $this->assertEquals($result[7]['name'], 'Report');
        $this->assertEquals($result[7]['rating']['log'], 'ok');
        $this->assertEquals($result[8]['name'], 'Event');
        $this->assertEquals($result[8]['rating']['log'], 'warn');
    }
    /*
        public function testIsPipInstalled()
        {
            global $MOCKED_EXEC_RETURN_VALUE;
            $optimizeSystem = new OptimizeSystem();
            $MOCKED_EXEC_RETURN_VALUE = 'Coucou';
            $optimizeSystem->isPipInstalled();
        }
    /*
        public function testIsCssCompressorInstalled()
        {

        }

        public function testIsJsMinInstalled()
        {

        }

        public function testTestPipPackage()
        {

        }

        public function testInstall()
        {

        }

        public function testMinify()
        {

        }

        public function testDisableLogs()
        {

        }
    */
}
