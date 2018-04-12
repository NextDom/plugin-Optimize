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
     *
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
        } elseif ($category == 'raspberry') {
            $result = $this->optimizeRaspberryPi($type);
        }
        return $result;
    }

    /**
     * Requête d'optimisation d'un scénario
     *
     * @param integer $scenarioId Identifiant du scénario
     * @param string $type Type d'optimisation
     *
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
     *
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
     * @param string $systemId Identifiant de l'élément à améliorer
     * @param string $type Type d'optimisation
     *
     * @return bool True si la requête a été reconnue et exécutée.
     */
    private function optimizeSystem($systemId, $type)
    {
        $result = true;
        require_once(dirname(__FILE__) . '/OptimizeSystem.class.php');

        $optimizeSystem = new OptimizeSystem();
        switch ($type) {
            case 'log':
                $optimizeSystem->disableLogs($systemId);
                break;
            case 'install':
                $result = $optimizeSystem->install($systemId);
                break;
            case 'minify':
                $optimizeSystem->minify($systemId);
                break;
            default:
                $result = false;
                break;
        }
        return $result;
    }

    /**
     * Requête d'optimisation pour Raspberry Pi
     *
     * @param string $type Type d'optimisation
     * @return bool True si la requête a été reconnue et exécutée.
     */
    private function optimizeRaspberryPi($type)
    {
        $result = false;
        require_once(dirname(__FILE__) . '/OptimizeRPi.class.php');

        $optimizeRPi = new OptimizeRPi();
        if ($type == 'gpu_mem') {
            $result = $optimizeRPi->optimizeGpuMem();
        } elseif ($type == 'l2_cache') {
            $result = $optimizeRPi->optimizeL2Cache();
        }
        return $result;
    }
}
