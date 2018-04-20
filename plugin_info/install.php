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

require_once(dirname(__FILE__) . '/../../../core/php/core.inc.php');
require_once(dirname(__FILE__) . '/../core/class/DataStorage.class.php');


/**
 * Fonction appelée à l'installation du plugin
 */
function Optimize_install()
{
    config::save('raspberry-config-file', '/boot/config.txt', 'Optimize');
    config::save('minify', false, 'Optimize');
    config::save('scenario-days-limit', 30, 'Optimize');
    $dataStorage = new DataStorage('optimize');
    $dataStorage->createDataTable();
}

/**
 * Fonction appelée à la mise à jour du plugin
 */
function Optimize_update()
{
    if (!config::byKey('scenario-days-limit', 'Optimize')) {
        config::save('scenario-days-limit', 30, 'Optimize');
    }
}

/**
 * Fonction appelée à la suppression du plugin
 */
function Optimize_remove()
{
    config::remove('raspberry-config-file', 'Optimize');
    config::remove('minify', 'Optimize');
    config::remove('scenario-days-limit', 'Optimize');
    $dataStorage = new DataStorage('optimize');
    $dataStorage->dropDataTable();
}
