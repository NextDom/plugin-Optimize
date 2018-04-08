<?php

require_once('../../mocked_core.php');

/**
 * Mock de la classe scenario
 */
class scenario
{
    public static $scenariosList;

    public static function init()
    {
        static::$scenariosList = array(
            new scenarioItem(1, 'First scenario', 'none', 1, true),
            new scenarioItem(2, 'Second scenario', 'realtime', 1, true),
            new scenarioItem(3, 'First scenario', 'none', 0, true),
            new scenarioItem(4, 'First scenario', 'none', 1, false)
        );
    }

    public static function all()
    {
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
        return false;
    }

    public function getIsActive()
    {
        $result = 0;
        if (scenarioItem::$enabledScenario != null) {
            if (scenarioItem::$enabledScenario) {
                $result = 1;
            }
        } else {
            if ($this->enabled) {
                $result = 1;
            }
        }
        return $result;
    }

    public function setConfiguration($type, $value)
    {
        MockedActions::add('set_configuration', array('type' => $type, 'value' => $value));
    }

    public function save()
    {
        MockedActions::add('save');
    }

    public function remove()
    {
        MockedActions::add('remove');
    }
}
