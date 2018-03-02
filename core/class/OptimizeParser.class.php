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

class OptimizeParser
{
    /**
     * Analyse une requête Ajax
     *
     * @param string $category Catégorie de l'optimisation à apporter
     * @param integer $id Identifiant de l'objet à optimiser
     * @param string $type Type d'optimisation
     * @return bool True si la requête a été reconnue et exécutée.
     */
    public function parse($category, $id, $type)
    {
        $result = false;
        if ($category == 'scenario') {
            $result = $this->optimizeScenario($id, $type);
        } elseif ($category == 'plugin') {
            $result = $this->optimizePlugin($id, $type);
        } elseif ($category == 'system') {
            $result = $this->optimizeSystem($id, $type);
        }
        return $result;
    }

    /**
     * Requête d'optimisation d'un scénario
     *
     * @param integer $scenarioId Identifiant du scénario
     * @param string $type Type d'optimisation
     * @return bool True si la requête a été reconnue et exécutée.
     */
    private function optimizeScenario($scenarioId, $type)
    {
        $result = true;
        require_once(dirname(__FILE__) . '/OptimizeScenarios.class.php');

        $optimizeScenarios = new OptimizeScenarios();
        switch ($type) {
            case 'log':
                $optimizeScenarios->disableLogs($scenarioId);
                break;
            case 'syncmode':
                $optimizeScenarios->setSyncMode($scenarioId);
                break;
            case 'enabled':
                $optimizeScenarios->removeIfDisabled($scenarioId);
                break;
            default:
                $result = false;
                break;
        }
        return $result;
    }

    /**
     * Requête d'optimisation d'un plugin
     *
     * @param integer $pluginId Identifiant du plugin
     * @param string $type Type d'optimisation
     * @return bool True si la requête a été reconnue et exécutée.
     */
    private function optimizePlugin($pluginId, $type)
    {
        $result = true;
        require_once(dirname(__FILE__) . '/OptimizePlugins.class.php');

        $optimizePlugins = new OptimizePlugins();
        switch ($type) {
            case 'log':
                $optimizePlugins->disableLogs($pluginId);
                break;
            case 'path':
                $optimizePlugins->changePluginPath($pluginId);
                break;
            case 'enabled':
                $optimizePlugins->removeIfDisabled($pluginId);
                break;
            default:
                $result = false;
                break;
        }
        return $result;
    }

    /**
     * Requête d'optimisation du système
     *
     * @param integer $systemId Identifiant de l'élément à améliorer
     * @param string $type Type d'optimisation
     * @return bool True si la requête a été reconnue et exécutée.
     */
    private function optimizeSystem($systemId, $type)
    {
        $result = true;
        require_once(dirname(__FILE__) . '/OptimizeSystem.class.php');

        $optimizeSystem = new OptimizeSystem();
        if ($type == 'log') {
            $optimizeSystem->disableLogs($systemId);
        }
        else {
            $result = false;
        }
        return $result;
    }
}
