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

require_once(dirname(__FILE__) . '/../../../../core/php/core.inc.php');
require_once('DataStorage.class.php');
require_once('OptimizeSystem.class.php');

/**
 * Classe globale
 */
class Optimize extends eqLogic
{
    /**
     * Méthode appelée tous les jours
     */
    public static function cronDaily()
    {
        if (config::byKey('minify', 'Optimize')) {
            $dataStorage = new DataStorage('optimize');

            $currentPluginsList = static::getCurrentPluginsList();
            $oldPluginsList = static::getOldPluginsList($dataStorage);
            if (static::changesInPlugins($currentPluginsList, $oldPluginsList)) {
                $optimizeSystem = new OptimizeSystem();
                $optimizeSystem->minify('csscompressor');
                $optimizeSystem->minify('jsmin');
                static::storeCurrentPluginList($dataStorage, $currentPluginsList);
            }
        }
    }

    /**
     * Recherche la différence entre les plugins
     *
     * @param array $currentPluginsList Liste des plugins actuels
     * @param array $oldPluginsList Ancienne liste des plugins
     * @return bool True si une modification a été trouvée
     */
    private static function changesInPlugins($currentPluginsList, $oldPluginsList) {
        $result = false;
        foreach ($currentPluginsList as $currentPlugin) {
            $matched = false;
            foreach ($oldPluginsList as $oldPlugin) {
                if ($currentPlugin[0] == $oldPlugin[0]) {
                    $matched = true;
                    if ($currentPlugin[1] != $oldPlugin[1]) {
                        $result = true;
                    }
                }
            }
            if (!$matched) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Obtenir la liste des plugins actuellement installés
     *
     * @return array Liste des plugins avec leurs versions
     */
    private static function getCurrentPluginsList()
    {
        $currentPluginsList = array();
        $plugins = plugin::listPlugin();
        foreach ($plugins as $plugin) {
            $update = update::byLogicalId($plugin->getId());
            if (is_object($update)) {
                array_push($currentPluginsList, array($plugin->getId(), $update->getLocalVersion()));
            }
        }
        return $currentPluginsList;
    }

    /**
     * Obtenir la liste des plugins installés lors de la dernière passe
     *
     * @param $dataStorage Base de données
     *
     * @return array Liste des plugins avec leurs versions
     */
    private static function getOldPluginsList($dataStorage)
    {
        $oldPluginsList = $dataStorage->getJsonData('old_plugins_list');
        if ($oldPluginsList == null) {
            $oldPluginsList = array();
        }
        return $oldPluginsList;
    }

    /**
     * Stocker la liste des plugins après la procédure
     *
     * @param $dataStorage Base de données
     * @param $pluginsList Liste des plugins
     */
    private static function storeCurrentPluginList($dataStorage, $pluginsList)
    {
        $dataStorage->storeJsonData('old_plugins_list', $pluginsList);
    }
}

