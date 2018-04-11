<?php

use PHPUnit\Framework\TestCase;

require_once('../../core/php/core.inc.php');
require_once('desktop/class/DesktopOptimize.class.php');

class DesktopOptimizeTest extends TestCase
{
    private $desktopOptimize;

    public function setUp()
    {
        $this->desktopOptimize = new DesktopOptimize();
        scenario::init();
    }

    public function testInit()
    {
        config::$byKeyPluginData = array('Optimize' => array('raspberry-config-file' => '/tmp/rasp-config.txt'));
        file_put_contents('/tmp/rasp-config.txt', 'gpu_mem=66');
        $this->desktopOptimize->init();
        $this->assertFalse(DesktopOptimize::$viewData['rpi']);
        $this->assertTrue(DesktopOptimize::$viewData['system_logs'][0]['log']);
        $this->assertEquals('plugin', DesktopOptimize::$viewData['system_logs'][1]['id']);
    }

    public function testShowActionCellWithOk()
    {
        ob_start();
        $this->desktopOptimize->showActionCell(array('log' => 'ok'), 'scenario', 'log');
        $result = ob_get_clean();
        $this->assertEquals('<td class="action-cell"><i class="fa fa-check-circle fa-2x"></i></td>', $result);
    }

    public function testShowActionCellWithWarn()
    {
        ob_start();
        $this->desktopOptimize->showActionCell(array('log' => 'warn'), 'plugin', 'log');
        $result = ob_get_clean();
        $this->assertEquals('<td class="action-cell"><i class="fa fa-exclamation-triangle fa-2x" data-category="plugin" data-type="log"></i></td>', $result);
    }
}
