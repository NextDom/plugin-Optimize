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
        ),
        'log::level::template' => array(
            '100' => 1,
            '200' => 0,
            '300' => 0,
            '400' => 0,
            '500' => 0,
            '1000' => 0,
            'default' => 0
        ),
        'log::level::Optimize' => array(
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

    public static function byKey($key)
    {
        return config::$byKeyData[$key];
    }

    public static function save($key, $data)
    {
        MockedActions::add(array('action' => 'save', 'key' => $key, 'data' => $data));
    }
}

/**
 * Mock de la classe config
 */
class plugin
{
    public static function listPlugin()
    {
        $result = array();
        array_push($result, new pluginItem('template', 'Template', true));
        array_push($result, new pluginItem('Optimize', 'Optimize', false));
        array_push($result, new pluginItem('supa_plugin', 'A superb plugin', true));
        return $result;
    }

    public static function byId($id)
    {
        // Renvoie toujours un plugin valide
        return new pluginItem($id);
    }
}

class pluginItem
{
    public $id;
    public $name;
    public $enabled;

    public static $base_plugin_path = 'MockedPlugins';

    public function __construct($id = null, $name = null, $enabled = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->enabled = $enabled;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function isActive()
    {
        $result = 0;
        // TODO $enabledScenario à supprimer
        if ($this->enabled) {
            $result = 1;
        }
        return $result;
    }

    public function getFilePath()
    {
        return static::$base_plugin_path . '/' . $this->id . '/plugin_info/info.json';
    }
}

/**
 * Mock de la classe scenario
 */
class scenario
{
    public static $scenariosList;

    public static function all()
    {
        static::$scenariosList = array(
            new scenarioItem(1, 'First scenario', 'none', 1, true),
            new scenarioItem(2, 'Second scenario', 'realtime', 1, true),
            new scenarioItem(3, 'First scenario', 'none', 0, true),
            new scenarioItem(4, 'First scenario', 'none', 1, false)
        );
        return static::$scenariosList;
    }

    public static function byId($scenarioId)
    {
        return static::$scenariosList[$scenarioId - 1];
    }
}

/**
 * Mock d'un scenario
 */
class scenarioItem
{
    public $id;
    public $name;
    public $logmode;
    public $syncmode;
    public $enabled;
    public static $enabledScenario = null;


    public function __construct($id = null, $name = null, $logmode = null, $syncmode = null, $enabled = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->logmode = $logmode;
        $this->syncmode = $syncmode;
        $this->enabled = $enabled;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getConfiguration($item)
    {
        switch ($item) {
            case 'logmode':
                return $this->logmode;
                break;
            case 'syncmode':
                return $this->syncmode;
                break;
        }
    }

    public function getIsActive()
    {
        $result = 0;
        if (scenarioItem::$enabledScenario != null) {
            if (scenarioItem::$enabledScenario) {
                $result = 1;
            }
        }
        else {
            if ($this->enabled) {
                $result = 1;
            }
        }
        return $result;
    }

    public function setConfiguration($type, $value)
    {
        MockedActions::add(array('action' => 'set_configuration', 'type' => $type, 'value' => $value));
    }

    public function save()
    {
        MockedActions::add(array('action' => 'save'));
    }

    public function remove()
    {
        MockedActions::add(array('action' => 'remove'));
    }
}

class system
{
    public static $cmdSudo = 'exit && ';

    public static function getCmdSudo()
    {
        MockedActions::add(array('action' => 'get_cmd_sudo'));
        return self::$cmdSudo;
    }
}

