<?php

require_once ('../../mocked_core.php');

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
        ),
        'log::level::thetemplate' => array(
            '100' => 1,
            '200' => 0,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 0,
            'default' => 0
        ),
        'log::level::IOptimize' => array(
            '100' => 0,
            '200' => 1,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 0,
            'default' => 0
        ),
        'log::level::supa_plugin' => array(
            '100' => 0,
            '200' => 0,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 1,
            'default' => 0
        )
    );
    public static $byKeyPluginData = array();

    public static function byKey($key, $plugin = 'core')
    {
        if ($plugin == 'core') {
            return config::$byKeyData[$key];
        }
        else {
            return config::$byKeyPluginData[$plugin][$key];
        }
    }

    public static function save($key, $data)
    {
        MockedActions::add(array('action' => 'save', 'key' => $key, 'data' => $data));
    }
}