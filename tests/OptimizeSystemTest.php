<?php
/**
 * Created by PhpStorm.
 * User: dangin
 * Date: 18/03/2018
 * Time: 17:01
 */

use PHPUnit\Framework\TestCase;

require 'core/class/OptimizeSystem.class.php';

// Mocked config
class config
{
    public static $byKeyData;

    public static function byKey($key)
    {
        return config::$byKeyData[$key];
    }
}

class OptimizeSystemTest extends TestCase
{

    public function testGetLogInformations()
    {
        $optimizeSystem = new OptimizeSystem();
        $systemLogs = array(
            'log::level::scenario' => array(
                '100' => 1,
                '200' => 0,
                '300' => 0,
                '400' => 0,
                '500' => 0,
                '1000' => 0,
                'default' => 0
            ),
            'log::level::plugin' => array(
                '100' => 0,
                '200' => 1,
                '300' => 0,
                '400' => 0,
                '500' => 0,
                '1000' => 0,
                'default' => 0
            ),
            'log::level::market' => array(
                '100' => 0,
                '200' => 0,
                '300' => 1,
                '400' => 0,
                '500' => 0,
                '1000' => 0,
                'default' => 0
            ),
            'log::level::api' => array(
                '100' => 0,
                '200' => 0,
                '300' => 0,
                '400' => 1,
                '500' => 0,
                '1000' => 0,
                'default' => 0
            ),
            'log::level::connection' => array(
                '100' => 0,
                '200' => 0,
                '300' => 0,
                '400' => 0,
                '500' => 1,
                '1000' => 0,
                'default' => 0
            ),
            'log::level::interact' => array(
                '100' => 0,
                '200' => 0,
                '300' => 0,
                '400' => 0,
                '500' => 0,
                '1000' => 1,
                'default' => 0
            ),
            'log::level::tts' => array(
                '100' => 0,
                '200' => 0,
                '300' => 0,
                '400' => 0,
                '500' => 0,
                '1000' => 0,
                'default' => 1
            ),
            'log::level::report' => array(
                '100' => 0,
                '200' => 0,
                '300' => 0,
                '400' => 0,
                '500' => 0,
                '1000' => 0,
                'default' => 0
            )
        );
        config::$byKeyData = $systemLogs;
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
