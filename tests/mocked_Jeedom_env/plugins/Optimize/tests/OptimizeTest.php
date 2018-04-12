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

require_once('core/class/Optimize.class.php');

/**
 * Mock de l'objet renvoyée par la méthode renvoyée par la méthode update::byLogicalId
 */
class MockUpdateLogicalId
{
    /**
     * @var Version de l'objet
     */
    public $version;

    /**
     * Constructeur permettant de définir l'objet
     *
     * @param string $version Version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * Obtenir la version de l'objet
     *
     * @return Version
     */
    public function getLocalVersion()
    {
        return $this->version;
    }
}

class OptimizeTest extends TestCase
{
    protected function setUp()
    {
        DB::init();
        MockedActions::clear();
        config::$byKeyPluginData = array('Optimize' => array('minify' => true));
    }

    protected function tearDown()
    {
    }

    public function testCronDailyWithoutPreviousData()
    {
        file_put_contents('test.js', "function test()\n{\nalert('test');\n}\n");
        file_put_contents('test.css', ".test-class\n{\nbackground-color: black;\n color: white;\n}\n");
        $testJsMd5 = md5_file('test.js');
        $testCssMd5 = md5_file('test.css');
        update::$byLogicalIdResult = array(
            'thetemplate' => new MockUpdateLogicalId("1.0"),
            'IOptimize' => new MockUpdateLogicalId("2.0"),
            'supa_plugin' => new MockUpdateLogicalId("3.0"));
        Optimize::cronDaily();

        $newTestJsMd5 = md5_file('test.js');
        $newTestCssMd5 = md5_file('test.css');
        $this->assertNotEquals($newTestJsMd5, $testJsMd5);
        $this->assertNotEquals($newTestCssMd5, $testCssMd5);
    }

    public function testCronDailyWithoutChange()
    {
        file_put_contents('test.js', "function test()\n{\nalert('test');\n}\n");
        file_put_contents('test.css', ".test-class\n{\nbackground-color: black;\n color: white;\n}\n");
        $testJsMd5 = md5_file('test.js');
        $testCssMd5 = md5_file('test.css');
        DB::setAnswer(array('data' => json_encode(array(array('thetemplate', '1.0'), array('IOptimize', '2.0'), array('supa_plugin', '3.0')))));
        update::$byLogicalIdResult = array(
            'thetemplate' => new MockUpdateLogicalId('1.0'),
            'IOptimize' => new MockUpdateLogicalId('2.0'),
            'supa_plugin' => new MockUpdateLogicalId('3.0'));
        Optimize::cronDaily();

        $newTestJsMd5 = md5_file('test.js');
        $newTestCssMd5 = md5_file('test.css');
        $this->assertEquals($newTestJsMd5, $testJsMd5);
        $this->assertEquals($newTestCssMd5, $testCssMd5);
    }

    public function testCronDailyDesactivated()
    {
        config::$byKeyPluginData = array('Optimize' => array('minify' => false));
        file_put_contents('test.js', "function test()\n{\nalert('test');\n}\n");
        $testJsMd5 = md5_file('test.js');
        Optimize::cronDaily();
        $newTestJsMd5 = md5_file('test.js');
        $this->assertEquals($newTestJsMd5, $testJsMd5);
    }

}
