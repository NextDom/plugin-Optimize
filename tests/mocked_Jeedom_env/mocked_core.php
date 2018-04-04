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

function include_file($folder, $name, $type, $plugin = null)
{
    MockedActions::add(array('action' => 'include_file', 'folder' => $folder, 'name' => $name, 'type' => $type, 'plugin' => $plugin));
}

function isConnect($user)
{
	return true;
}

function init() {
	return true;
}
