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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

include_file('core', 'authentification', 'php');

if (!isConnect()) {
    // @codeCoverageIgnoreStart
    include_file('desktop', '404', 'php');
    die();
    // @codeCoverageIgnoreEnd
}

?>
<form class="form-horizontal">
    <div class="form-group">
        <label for="raspberry-config-file" class="col-sm-2 control-label">{{Raspberry Pi config file}}</label>
        <div class="col-sm-10">
            <input type="text" data-l1key="raspberry-config-file" class="configKey form-control" id="raspberry-config-file" placeholder="/boot/config.txt" />
        </div>
    </div>
    <div class="form-group">
        <label for="minify" class="col-sm-2 control-label">{{Raspberry Pi config file}}</label>
        <div class="col-sm-10">
            <input type="checkbox" class="configKey form-control" data-l1key="minify"/>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="alert alert-info">
            {{A first files minification must be executed manually before.}}
        </div>
    </div>
</form>
