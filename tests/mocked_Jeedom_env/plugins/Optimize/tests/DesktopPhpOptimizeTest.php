<?php

use PHPUnit\Framework\TestCase;

require_once('../../core/php/core.inc.php');

class DesktopPhpOptimizeTest extends TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testNotConnected()
    {
        scenario::init();
        JeedomVars::$isConnected = false;
        try {
            include(dirname(__FILE__) . '/../desktop/php/Optimize.php');
            $this->fail("L'exception n'a pas été déclenchée.");
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), '401 - Refused access');
        }
    }

    public function testWithUserConnected()
    {
        scenario::init();
        ob_start();
        include(dirname(__FILE__) . '/../desktop/php/Optimize.php');
        $content = ob_get_clean();
        $actions = MockedActions::get();
        $this->assertCount(3, $actions);
        $this->assertEquals('include_file', $actions[0]['action']);
        $this->assertEquals('authentification', $actions[0]['content']['name']);
        $this->assertContains('<ul class="nav nav-tabs">', $content);
    }
}
