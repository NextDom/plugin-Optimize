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

require_once(dirname(__FILE__) . '/../../core/class/BaseOptimize.class.php');
require_once(dirname(__FILE__) . '/../../core/class/OptimizePlugins.class.php');
require_once(dirname(__FILE__) . '/../../core/class/OptimizeScenarios.class.php');
require_once(dirname(__FILE__) . '/../../core/class/OptimizeSystem.class.php');
require_once(dirname(__FILE__) . '/../../core/class/OptimizeRPi.class.php');

class DesktopOptimize
{
    public static $viewData = array();

    protected function init()
    {
        static::$viewData = array();
        BaseOptimize::initScore();

        $optimizeScenarios = new OptimizeScenarios();
        static::$viewData['scenarios'] = $optimizeScenarios->getInformations();

        $optimizePlugins = new OptimizePlugins();
        static::$viewData['plugins'] = $optimizePlugins->getInformations();

        $optimizeSystem = new OptimizeSystem();
        static::$viewData['system_logs'] = $optimizeSystem->getLogInformations();
        static::$viewData['system_pip'] = $optimizeSystem->isPipInstalled();
        if (static::$viewData['system_pip'] === true) {
            static::$viewData['system_csscompressor'] = $optimizeSystem->isCssCompressorInstalled();
            static::$viewData['system_jsmin'] = $optimizeSystem->isJsMinInstalled();
        }

        static::$viewData['rpi'] = false;
        $optimizeRPi = new OptimizeRPi();
        if ($optimizeRPi->isRaspberryPi()) {
            static::$viewData['rpi'] = true;
            static::$viewData['rpi_can_optimize'] = $optimizeRPi->canParseSystemConfigFile();
            if (static::$viewData['rpi_can_optimize'] === true) {
                static::$viewData['rpi_sudo'] = $optimizeRPi->canSudo();
                static::$viewData['rating'] = $optimizeRPi->getRating();
            }
        }

        static::$viewData['currentScore'] = BaseOptimize::getCurrentScore();
        static::$viewData['bestScore'] = BaseOptimize::getBestScore();
    }

    /**
     * Affiche contenu d'une cellule pouvant nécessiter une action de l'utilisateur
     *
     * @param array $rating Note de l'élément
     * @param string $category Catégorie
     * @param string $type Type de modification
     */
    public static function showActionCell($rating, $category, $type)
    {
        echo '<td class="action-cell">';
        if ($rating[$type] == 'ok') {
            echo '<i class="fa fa-check-circle fa-2x"></i>';
        } else {
            echo '<i class="fa fa-exclamation-triangle fa-2x" data-category="' . $category . '" data-type="' . $type . '"></i>';
        }
        echo '</td>';
    }

    public function show()
    {
        $this->init();
        include_file('desktop', 'Optimize', 'css', 'Optimize');
        include_file('desktop', 'Optimize', 'js', 'Optimize');
        include(dirname(__FILE__) . '/../templates/view.php');
    }
}