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

require_once('BaseOptimize.class.php');

class OptimizePlugins extends BaseOptimize
{
    /**
     * Obtenir la liste de tous les plugins.
     *
     * @return array Tableau contenant la liste des plugins.
     */
    private function getAllPlugins()
    {
        return plugin::listPlugin();
    }

    /**
     * Extraire les informations pertinentes d'un plugin.
     *
     * @param mixed $plugin Plugin concernées
     *
     * @return array Informations du plugin
     */
    private function getInformationsFromPlugin($plugin)
    {
        $informations = array();

        $informations['id'] = $plugin->getId();
        $informations['name'] = $plugin->getName();
        $pluginLogConfig = config::byKey('log::level::' . $plugin->getId());
        $informations['log'] = false;
        $informations['filepath'] = $plugin->getFilepath();
        // Chaque type de log est stocké dans un tableau et identifié par un nombre sauf "default"
        // 1000 représente "Aucun"
        foreach ($pluginLogConfig as $logType => $value) {
            if ($value == 1 && $logType != 1000) {
                $informations['log'] = true;
            }
        }
        $informations['enabled'] = $plugin->isActive();

        return $informations;
    }

    /**
     * Evalue les informations d'un plugin.
     *
     * @param array $informations Informations à évaluer
     *
     * @return array Rapport sur les informations évaluées
     */
    private function ratePluginInformations($informations)
    {
        $rating = array();

        // Valeurs par défaut
        $rating['log'] = 'ok';
        $rating['path'] = 'ok';
        $rating['enabled'] = 'ok';
        self::$bestScore += 3;

        // Les logs doivent être désactivés
        if ($informations['log'] === true) {
            self::$badPoints++;
            $rating['log'] = 'warn';
        }

        // Chemin vers le plugin
        if (!file_exists($this->getJeedomRootDirectory() . '/plugins/' . $informations['id'])) {
            self::$badPoints++;
            $rating['path'] = 'warn';
        }

        // Les plugins doivent être activés
        if ($informations['enabled'] == 0) {
            self::$badPoints++;
            $rating['enabled'] = 'warn';
        }

        return $rating;
    }

    /**
     * Obtenir les informations et une évaluation de l'ensemble des plugins.
     *
     * @return array Informations sur l'ensemble des plugins
     */
    public function getInformations()
    {
        $plugins = $this->getAllPlugins();
        $informations = array();

        foreach ($plugins as $plugin) {
            $pluginInformations = $this->getInformationsFromPlugin($plugin);
            $rating = $this->ratePluginInformations($pluginInformations);
            $pluginInformations['rating'] = $rating;
            \array_push($informations, $pluginInformations);
        }
        return $informations;
    }

    /**
     * Obtenir l'objet d'un plugin à partir de son identifiant.
     *
     * @param integer $pluginId Identifiant du plugin
     *
     * @return mixed Plugin
     */
    private function getPluginById($pluginId)
    {
        return plugin::byId($pluginId);
    }

    /**
     * Obtenir le chemin du répertoire des plugins.
     *
     * @return string Chemin du répertoire
     */
    private function getPluginsDirectory()
    {
        return \realpath(__DIR__ . '/../../../');
    }

    /**
     * Désactive les logs d'un ou tous les plugins.
     *
     * @param integer|string $pluginId Identifiant du plugin ou optimize-all
     */
    public function disableLogs($pluginId)
    {
        if ($pluginId == 'optimize-all') {
            $plugins = $this->getAllPlugins();
            foreach ($plugins as $plugin) {
                $this->disablePluginLog($plugin);
            }
        } else {
            $this->disablePluginLog($this->getPluginById($pluginId));
        }
    }

    /**
     * @param $plugin
     */
    private function disablePluginLog($plugin)
    {
        $pluginLogConfig = config::byKey('log::level::' . $plugin->getId());
        foreach ($pluginLogConfig as $key => $value) {
            if ($value != 0) {
                $pluginLogConfig[$key] = "0";
            }
        }
        $pluginLogConfig[1000] = "1";
        config::save('log::level::' . $plugin->getId(), $pluginLogConfig);
    }

    /**
     * Corrige le nom du répertoire d'un plugin.
     *
     * @param integer $pluginId Identifiant du plugin
     */
    public function changePluginPath($pluginId)
    {
        // Le plugin n'est pas accessible par son identifiant directement
        $pluginsList = $this->getAllPlugins();
        $infoJsonPath = '';
        foreach ($pluginsList as $plugin) {
            if ($plugin->getId() == $pluginId) {
                // Filepath renvoie le chemin vers le fichier info.json
                $infoJsonPath = $plugin->getFilepath();
            }
        }
        if (\strlen($infoJsonPath) > 0) {
            $currentPluginDirectory = strstr($infoJsonPath, '/plugin_info/info.json', true);
            \rename($currentPluginDirectory, $this->getJeedomRootDirectory() . '/plugins/' . $pluginId);
        }
    }

    /**
     * Supprime un répertoire avec son contenu.
     *
     * @param string $path Chemin du répertoire à supprimer
     */
    private function deleteDirectory($path)
    {
        $items = \scandir($path);
        foreach ($items as $item) {
            if ($item != '.' && $item != '..') {
                $currentItemPath = $path . '/' . $item;
                if (\is_dir($currentItemPath)) {
                    $this->deleteDirectory($currentItemPath);
                } else {
                    \unlink($currentItemPath);
                }
            }
        }
        \rmdir($path);
    }

    /**
     * Supprime un plugin désactivé.
     *
     * @param integer $pluginId Identifiant du plugin
     *
     * @return True si le plugin a été supprimé
     */
    public function removeIfDisabled($pluginId)
    {
        $result = false;
        if ($this->getPluginById($pluginId)->isActive() == 0) {
            $update = update::byId($pluginId);
            if (!\is_object($update)) {
                $update = update::byLogicalId($pluginId);
            }
            if (\is_object($update)) {
                // Suppression par Jeedom
                $update->deleteObjet();
                $result = true;
            } else {
                // Appele du script de désinstallation
                $installationFile = $this->getPluginsDirectory() . '/' . $pluginId . '/plugin_info/install.php';
                if (file_exists($installationFile)) {
                    require_once($installationFile);
                    $functionName = $pluginId . '_remove';
                    if (function_exists($functionName)) {
                        $functionName();
                    }
                }
                $this->deleteDirectory($this->getPluginsDirectory() . '/' . $pluginId);
                $result = true;
            }
        }
        return $result;
    }
}
