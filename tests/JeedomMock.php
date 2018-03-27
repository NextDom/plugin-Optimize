<?php

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

class config
{
    public static $byKeyData;

    public static function byKey($key)
    {
        return config::$byKeyData[$key];
    }
}

class scenario
{
    public static $mockedActions = array();

    public static $activatedScenarios = true;

    public static function byId($scenarioId)
    {
        $scenarioItem = new scenarioItem();
        return $scenarioItem;
    }

    public static function clearMockedActions() {
        self::$mockedActions = array();
    }
}

class scenarioItem
{
    public function setConfiguration($type, $value)
    {
        array_push(scenario::$mockedActions, array('action' => 'configuration', 'type' => $type, 'value' => $value));
    }

    public function save()
    {
        array_push(scenario::$mockedActions, array('action' => 'save'));
    }

    public function getIsActive() {
        $result = 0;
        if (scenario::$activatedScenarios) {
            return 1;
        }
    }

    public function remove()
    {
        array_push(scenario::$mockedActions, array('action' => 'remove'));
    }
}
