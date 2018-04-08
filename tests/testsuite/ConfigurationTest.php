<?php

use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public $dataStorage;

    protected function setUp()
    {
        JeedomVars::$isConnected = true;
    }

    protected function tearDown()
    {
    }

    public function testNotConnected()
    {
        JeedomVars::$isConnected = false;
        ob_start();
        include(dirname(__FILE__) . '/../plugin_info/configuration.php');
        ob_get_clean();
        // Le fichier exécute die(), cette partie ne doit normalement pas être exécutée
        $this->assertTrue(false);

    }

    public function testWithUserConnected()
    {
        ob_start();
        include(dirname(__FILE__) . '/../plugin_info/configuration.php');
        $content = ob_get_clean();
        $actions = MockedActions::get();
        $this->assertEquals(1, count($actions));
        $this->assertEquals('include_file', $actions[0]['action']);
        $this->assertEquals('authentification', $actions[0]['content']['name']);
        $this->assertContains('<form', $content);
        $this->assertContains('raspberry-config-file', $content);
    }
}
