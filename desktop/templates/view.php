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

/**
 * Affiche contenu d'une cellule pouvan nécessiter une action de l'utilisateur
 *
 * @param $rating Note de l'élément
 * @param $category Catégorie
 * @param $type Type de modification
 */
function showActionCell($rating, $category, $type)
{
    echo '<td>';
    if ($rating[$type] == 'ok') {
        echo '<i class="fa fa-check-circle fa-2x"></i>';
    } else {
        echo '<i class="fa fa-exclamation-triangle fa-2x" data-category="' . $category . '" data-type="' . $type . '"></i>';
    }
    echo '</td>';
}

?>
<div id="optimize-plugin" class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <button class="btn btn-primary" data-toggle="collapse" data-target="#scenarios">{{Scenarios}}</button>
            <button class="btn btn-default" data-toggle="collapse" data-target="#scenarios-informations">
                {{Informations}}
            </button>
        </div>
    </div>
    <div id="scenarios-informations" class="row collapse">
        <div class="alert alert-info fade in">
            <strong>{{Scenarios optimization}}</strong>
            <ul>
                <li>
                    {{Logs enabled: Log writing slows down execution. They must be disabled if they are not used,}}
                </li>
                <li>
                    {{Synchronous mode: Scenarios executed in synchronous mode do not wait for a return of commands.
                    Attention, this option can cause malfunctions,}}
                </li>
                <li>
                    {{Disabled: A disabled scenario is stored in the database. It is best to delete it to speed up
                    queries in the database.}}
                </li>
            </ul>
        </div>
    </div>
    <div id="scenarios" class="row collapse">
        <div class="col-sm-12">
            <table class="table">
                <thead>
                <tr>
                    <th>{{Name}}</th>
                    <th>{{Logs}}</th>
                    <th>{{Mode}}</th>
                    <th>{{Enabled}}</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tplData['scenarios'] as $scenario) : ?>
                    <tr data-id="<?php echo $scenario['id']; ?>">
                        <td>
                            <a href="/index.php?v=d&p=scenario&id=<?php echo $scenario['id']; ?>"><?php echo $scenario['name']; ?></a>
                        </td>
                        <?php
                        showActionCell($scenario['rating'], 'scenario', 'log');
                        showActionCell($scenario['rating'], 'scenario', 'syncmode');
                        showActionCell($scenario['rating'], 'scenario', 'enabled');
                        ?>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <button class="btn btn-primary" data-toggle="collapse" data-target="#plugins">{{Plugins}}</button>
            <button class="btn btn-default" data-toggle="collapse" data-target="#plugins-informations">{{Informations}}
            </button>
        </div>
    </div>
    <div id="plugins-informations" class="row collapse">
        <div class="alert alert-info fade in">
            <strong>{{Plugins optimization}}</strong>
            <ul>
                <li>
                    {{Logs enabled: Log writing slows down execution. They must be disabled if they are not used,}}
                </li>
                <li>
                    {{Bad path: The plugin is not in the right directory,}}
                </li>
                <li>
                    {{Disabled: Information from all plugins are read even if they are disabled. Removing a plugin that
                    is not used will also save disk space.}}
                </li>
            </ul>
        </div>
    </div>
    <div id="plugins" class="row collapse">
        <div class="col-sm-12">
            <table class="table">
                <thead>
                <tr>
                    <th>{{Name}}</th>
                    <th>{{Logs}}</th>
                    <th>{{Path}}</th>
                    <th>{{Enabled}}</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tplData['plugins'] as $plugin) : ?>
                    <tr data-id="<?php echo $plugin['id']; ?>">
                        <td>
                            <a href="/index.php?v=d&m=<?php echo $plugin['id']; ?>&p=<?php echo $plugin['id']; ?>"><?php echo $plugin['name']; ?></a>
                        </td>
                        <?php
                        showActionCell($plugin['rating'], 'plugin', 'log');
                        showActionCell($plugin['rating'], 'plugin', 'path');
                        showActionCell($plugin['rating'], 'plugin', 'enabled');
                        ?>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <button class="btn btn-primary" data-toggle="collapse" data-target="#system">{{System}}</button>
            <button class="btn btn-default" data-toggle="collapse" data-target="#system-informations">{{Informations}}
            </button>
        </div>
    </div>
    <div id="system-informations" class="row collapse">
        <div class="alert alert-info fade in">
            <strong>{{System optimization}}</strong>
            <ul>
                <li>
                    {{Logs enabled: Log writing slows down execution. They must be disabled if they are not used,}}
                </li>
            </ul>
        </div>
    </div>
    <div id="system" class="row collapse">
        <div class="col-sm-12">
            <table class="table">
                <thead>
                <tr>
                    <th>{{Name}}</th>
                    <th>{{Logs}}</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tplData['systemLogs'] as $systemLog) : ?>
                    <tr data-id="<?php echo $systemLog['id']; ?>">
                        <td>
                            <?php echo $systemLog['name']; ?>
                        </td>
                        <?php
                        showActionCell($systemLog['rating'], 'system', 'log');
                        ?>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="optimize-modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×
                </button>
                <h4 class="modal-title">{{Information}}</h4>
            </div>
            <div class="modal-body">
                <p id="optimize-modal-content"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="optimize-modal-valid">{{Confirm}}</button>
                <button type="button" class="btn btn-warning" data-dismiss="modal">{{Close}}</button>
            </div>
        </div>
    </div>
</div>
<script>
    var msg = [];
    msg['plugin_log'] = '{{Do you want to disable logs for this plugin?}}';
    msg['plugin_path'] = '{{Do you want to rename the plugin directory?}}';
    msg['plugin_enabled'] = '{{Do you want to delete this plugin?}}';
    msg['scenario_log'] = '{{Do you want to disable logs for this scenario?}}';
    msg['scenario_syncmode'] = '{{Do you want to enable synchronous mode for this scenario?}}';
    msg['scenario_enabled'] = '{{Do you want to delete this scenario?}}';
    msg['system_log'] = '{{Do you want to disable logs for this item?}}';
</script>
