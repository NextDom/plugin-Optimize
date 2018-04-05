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

class JeedomVars {
  public static $jeedomIsConnected = true;

  public static $initAnswers = array();
}

function include_file($folder, $name, $type, $plugin = null)
{
    MockedActions::add(array('action' => 'include_file', 'folder' => $folder, 'name' => $name, 'type' => $type, 'plugin' => $plugin));
}

function isConnect($user)
{
	return JeedomVars::$jeedomIsConnected;
}

function init($key) {
  return JeedomVars::$initAnswers[$key];
}

function __($msg) {
  return $msg;
}

function displayExeption($exceptionMsg) {
  displayException($exceptionMsg);
}

function displayException($exceptionMsg) {

}
