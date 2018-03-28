<?php

/**
 * Classe inscrivant l'historique des actions effectuées lors des tests
 */
class MockedActions
{
    public static $actionsList = array();

    public static function add($toAdd)
    {
        array_push(self::$actionsList, $toAdd);
    }

    public static function get()
    {
        return self::$actionsList;
    }

    public static function clear()
    {
        self::$actionsList = array();
    }
}

/**
 * Mock de la classe Jeddom
 */
class jeedom
{
    public static $sudoAnswer = false;

    public static $hardwareName;

    public static function getHardwareName()
    {
        return jeedom::$hardwareName;
    }

    public static function isCapable($str)
    {
        return self::$sudoAnswer;
    }
}

/**
 * Mock de la classe config
 */
class config
{
    public static $byKeyData = array(
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
        ),
        'log::level::event' => array(
            '100' => 1,
            '200' => 0,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 0,
            'default' => 0
        )
    );

    public static function byKey($key)
    {
        return config::$byKeyData[$key];
    }

    public static function save($key, $data) {
        MockedActions::add(array('action' => 'save', 'key' => $key, 'data' => $data));
    }
}

/**
 * Mock de la classe scenario
 */
class scenario
{
    public static $activatedScenarios = true;

    public static function byId($scenarioId)
    {
        $scenarioItem = new scenarioItem();
        return $scenarioItem;
    }
}

/**
 * Mock d'un scenario
 */
class scenarioItem
{
    public function setConfiguration($type, $value)
    {
        MockedActions::add(array('action' => 'set_configuration', 'type' => $type, 'value' => $value));
    }

    public function save()
    {
        MockedActions::add(array('action' => 'save'));
    }

    public function getIsActive()
    {
        $result = 0;
        if (scenario::$activatedScenarios) {
            $result = 1;
        }
        return $result;
    }

    public function remove()
    {
        MockedActions::add(array('action' => 'remove'));
    }
}

class system {
    public static $cmdSudo = 'exit && ';

    public static function getCmdSudo() {
        MockedActions::add(array('action' => 'get_cmd_sudo'));
        return self::$cmdSudo;
    }
}