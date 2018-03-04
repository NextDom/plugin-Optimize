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

require_once(dirname(__FILE__) . '/../../core/class/OptimizePlugins.class.php');
require_once(dirname(__FILE__) . '/../../core/class/OptimizeScenarios.class.php');
require_once(dirname(__FILE__) . '/../../core/class/OptimizeSystem.class.php');
require_once(dirname(__FILE__) . '/../../core/class/OptimizeRPi.class.php');

include_file('desktop', 'Optimize', 'css', 'Optimize');
include_file('desktop', 'Optimize', 'js', 'Optimize');
include_file('core', 'authentification', 'php');

if (!isConnect('admin')) {
    throw new Exception(__('401 - Refused access', __FILE__));
}

$tplData = array();

$optimizeScenarios = new OptimizeScenarios();
$tplData['scenarios'] = $optimizeScenarios->getInformations();

$optimizePlugins = new OptimizePlugins();
$tplData['plugins'] = $optimizePlugins->getInformations();

$optimizeSystem = new OptimizeSystem();
$tplData['systemLogs'] = $optimizeSystem->getInformations();

$tplData['rpi'] = false;
$optimizeRPi = new OptimizeRPi();
if ($optimizeRPi->isRaspberryPi()
    ||  true // DEBUG
) {
    $tplData['rpi'] = true;
    $tplData['rpi_can_optimize'] = $optimizeRPi->canParseSystemConfigFile();
    if ($tplData['rpi_can_optimize'] === true) {
        $tplData['rpi_sudo'] = $optimizeRPi->canSudo();
        $tplData['rating'] = array();
        $tplData['rating']['gpu_mem'] = $optimizeRPi->rateGpuMemOptimization();
        $tplData['rating']['l2_cache'] = $optimizeRPi->rateL2CacheOptimization();
    }
}

// Affichage
include(dirname(__FILE__) . '/../templates/view.php');
