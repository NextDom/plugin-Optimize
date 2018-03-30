<?php

use PHPUnit\Framework\TestCase;

//require_once ('./desktop/class/DesktopOptimize.class.php');
//require_once('JeedomMock.php');

class DesktopOptimize
{
    public static $viewData = array();

    public static $showedCells = array();

    public static function showActionCell($rating, $category, $type)
    {
        array_push(static::$showedCells, array($rating, $category, $type));
    }
}


class DesktopViewTest extends TestCase
{
    protected function setUp()
    {
        DesktopOptimize::$viewData = array();
        DesktopOptimize::$viewData['rpi'] = false;
        DesktopOptimize::$viewData['rpi_can_optimize'] = false;
        DesktopOptimize::$viewData['rpi_sudo'] = false;
        DesktopOptimize::$viewData['system_pip'] = false;
        DesktopOptimize::$viewData['system_jsmin'] = false;
        DesktopOptimize::$viewData['system_csscompressor'] = false;
        DesktopOptimize::$viewData['scenarios'] = array();
        DesktopOptimize::$viewData['plugins'] = array();
        DesktopOptimize::$viewData['system_logs'] = array();
        DesktopOptimize::$viewData['currentScore'] = 0;
        DesktopOptimize::$viewData['bestScore'] = 0;
        DesktopOptimize::$viewData['rating'] = array();
        DesktopOptimize::$showedCells = array();
    }

    protected function tearDown()
    {
        MockedActions::clear();
    }

    private function requireView()
    {
        ob_start();
        include('./desktop/templates/view.php');
        return ob_get_clean();
    }

    public function testShowActionCell()
    {
        DesktopOptimize::$viewData['scenarios'] = array(
            array('id' => '1', 'name' => 'First scenario', 'rating' => array('log' => 'ok', 'syncmode' => 'ok', 'enabled' => 'ok')),
            array('id' => '2', 'name' => 'Second scenario', 'rating' => array('log' => 'warn', 'syncmode' => 'ok', 'enabled' => 'ok')),
            array('id' => '3', 'name' => 'Third scenario', 'rating' => array('log' => 'ok', 'syncmode' => 'warn', 'enabled' => 'ok')),
            array('id' => '4', 'name' => 'Fourth scenario', 'rating' => array('log' => 'ok', 'syncmode' => 'ok', 'enabled' => 'warn')),
        );
        $this->requireView();
        $cells = DesktopOptimize::$showedCells;
        $this->assertEquals(12, count($cells));
        $this->assertEquals(array('log' => 'ok', 'syncmode' => 'ok', 'enabled' => 'ok'), $cells[0][0]);
        $this->assertEquals('scenario', $cells[1][1]);
        $this->assertEquals(array('log' => 'warn', 'syncmode' => 'ok', 'enabled' => 'ok'), $cells[3][0]);
        $this->assertEquals('syncmode', $cells[4][2]);
        $this->assertEquals(array('log' => 'ok', 'syncmode' => 'warn', 'enabled' => 'ok'), $cells[6][0]);
        $this->assertEquals('log', $cells[6][2]);
        $this->assertEquals(array('log' => 'ok', 'syncmode' => 'ok', 'enabled' => 'warn'), $cells[9][0]);
        $this->assertEquals('enabled', $cells[11][2]);
    }

    public function testViewRenderEmpty()
    {
        $result = $this->requireView();
        $this->assertTrue(strstr($result, 'optimize-plugin') !== 0);
        $this->assertEquals(9, substr_count($result, 'table'));
    }

    public function testViewRenderRPi()
    {
        DesktopOptimize::$viewData['rpi'] = true;
        $result = $this->requireView();
        $this->assertContains('#raspberry', $result);
        $this->assertEquals(9, substr_count($result, 'table'));
    }

    public function testViewRenderRPiCanOptimizeCantSudo()
    {
        DesktopOptimize::$viewData['rpi'] = true;
        DesktopOptimize::$viewData['rpi_can_optimize'] = true;
        DesktopOptimize::$viewData['rpi_sudo'] = false;
        $result = $this->requireView();
        $this->assertContains('#raspberry', $result);
        $this->assertContains('Jeedom doesn\'t have sudo rights.', $result);
    }

    public function testViewRenderRPiCanSudo()
    {
        DesktopOptimize::$viewData['rpi'] = true;
        DesktopOptimize::$viewData['rpi_can_optimize'] = true;
        DesktopOptimize::$viewData['rpi_sudo'] = true;
        $result = $this->requireView();
        $this->assertTrue(strstr($result, '#raspberry') !== 0);
        $this->assertContains('GPU memory', $result);
        $this->assertContains('L2 Cache', $result);
    }

    public function testSystemCantPip()
    {
        DesktopOptimize::$viewData['system_pip'] = false;
        $result = $this->requireView();
        $this->assertContains('Python pip is not installed', $result);
    }

    public function testSystemCanPip()
    {
        DesktopOptimize::$viewData['system_pip'] = true;
        $result = $this->requireView();
        $this->assertContains('Minification', $result);
    }

    public function testSystemNeedCssCompressorInstall()
    {
        DesktopOptimize::$viewData['system_pip'] = true;
        DesktopOptimize::$viewData['system_csscompressor'] = false;
        $result = $this->requireView();
        $this->assertContains('The Python module \'csscompressor\' is not installed.', $result);
    }

    public function testSystemCssCompressorInstalled()
    {
        DesktopOptimize::$viewData['system_pip'] = true;
        DesktopOptimize::$viewData['system_csscompressor'] = true;
        $result = $this->requireView();
        $this->assertContains('Minify CSS', $result);
    }

    public function testSystemNeedJsMinInstall()
    {
        DesktopOptimize::$viewData['system_pip'] = true;
        DesktopOptimize::$viewData['system_jsmin'] = false;
        $result = $this->requireView();
        $this->assertContains('The Python module \'jsmin\' is not installed.', $result);
    }

    public function testSystemJsMinInstalled()
    {
        DesktopOptimize::$viewData['system_pip'] = true;
        DesktopOptimize::$viewData['system_jsmin'] = true;
        $result = $this->requireView();
        $this->assertContains('Minify Javascript', $result);
    }
}
