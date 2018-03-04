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

header('Content-Type: application/json');

try {
    require_once(dirname(__FILE__) . '/../../../../core/php/core.inc.php');
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new \Exception(__('401 - Refused access', __FILE__));
    }

    require_once(dirname(__FILE__) . '/../class/OptimizeParser.class.php');

    ajax::init();

    // Lecture des données passées par la requête
    $category = init('category');
    $id = init('id');
    $type = init('type');

    // Analyse de la requête Ajax
    $ajaxParser = new OptimizeParser();
    if ($ajaxParser->parse($category, $id, $type)) {
        // Renvoie les données pour éviter les doubles appels
        ajax::success(array('category' => $category, 'id' => $id, 'type' => $type));
    }

    throw new \Exception(__('No method corresponding to : ', __FILE__) . init('category'));

} catch (\Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
