<?php

/**
 * Mock de la classe config
 */
class plugin
{
    public static function listPlugin()
    {
        $result = array();
        array_push($result, new pluginItem('thetemplate', 'TheTemplate', true));
        array_push($result, new pluginItem('IOptimize', 'IOptimize', false));
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

    public function getName()
    {
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