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

?>
<div id="optimize-plugin" class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="progress">
                <div id="score" class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0"
                     aria-valuemax="100">
                </div>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a data-toggle="tab" href="#scenarios">{{Scenarios}}</a></li>
        <li role="presentation"><a data-toggle="tab" href="#plugins">{{Plugins}}</a></li>
        <li role="presentation"><a data-toggle="tab" href="#system">{{System}}</a></li>
        <?php if (DesktopOptimize::$viewData['rpi'] === true): ?>
            <li role="presentation"><a data-toggle="tab" href="#raspberry">{{Raspberry Pi}}</a></li>
        <?php endif; ?>
    </ul>
    <div class="tab-content clearfix">
        <div id="scenarios" class="tab-pane active">
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-info" data-toggle="collapse" data-target="#scenarios-informations">
                        {{Informations}}
                    </button>
                </div>
            </div>
            <div id="scenarios-informations" class="row collapse">
                <div class="col-sm-12">
                    <div class="alert alert-info fade in">
                        <strong>{{Scenarios optimization}}</strong>
                        <ul>
                            <li>
                                {{Logs enabled: Log writing slows down execution. They must be disabled if they are not used,}}
                            </li>
                            <li>
                                {{Synchronous mode: Scenarios executed in synchronous mode do not wait for a return of commands. Attention, this option can cause malfunctions,}}
                            </li>
                            <li>
                                {{Disabled: A disabled scenario is stored in the database. It is best to delete it to speed up queries in the database.}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
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
                        <?php foreach (DesktopOptimize::$viewData['scenarios'] as $scenario) : ?>
                            <tr data-id="<?php echo $scenario['id']; ?>">
                                <td>
                                    <a href="/index.php?v=d&p=scenario&id=<?php echo $scenario['id']; ?>"><?php echo $scenario['name']; ?></a>
                                </td>
                                <?php
                                DesktopOptimize::showActionCell($scenario['rating'], 'scenario', 'log');
                                DesktopOptimize::showActionCell($scenario['rating'], 'scenario', 'syncmode');
                                DesktopOptimize::showActionCell($scenario['rating'], 'scenario', 'enabled');
                                ?>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="plugins" class="tab-pane">
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-info" data-toggle="collapse" data-target="#plugins-informations">
                        {{Informations}}
                    </button>
                </div>
            </div>
            <div id="plugins-informations" class="row collapse">
                <div class="col-sm-12">
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
                                {{Disabled: Information from all plugins are read even if they are disabled. Removing a plugin that is not used will also save disk space.}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
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
                        <?php foreach (DesktopOptimize::$viewData['plugins'] as $plugin) : ?>
                            <tr data-id="<?php echo $plugin['id']; ?>">
                                <td>
                                    <a href="/index.php?v=d&m=<?php echo $plugin['id']; ?>&p=<?php echo $plugin['id']; ?>"><?php echo $plugin['name']; ?></a>
                                </td>
                                <?php
                                DesktopOptimize::showActionCell($plugin['rating'], 'plugin', 'log');
                                DesktopOptimize::showActionCell($plugin['rating'], 'plugin', 'path');
                                DesktopOptimize::showActionCell($plugin['rating'], 'plugin', 'enabled');
                                ?>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="system" class="tab-pane">
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-info" data-toggle="collapse" data-target="#system-informations">
                        {{Informations}}
                    </button>
                </div>
            </div>
            <div id="system-informations" class="row collapse">
                <div class="col-sm-12">
                    <div class="alert alert-info fade in">
                        <strong>{{System optimization}}</strong>
                        <ul>
                            <li>
                                {{Logs enabled: Log writing slows down execution. They must be disabled if they are not used,}}
                            </li>
                            <li>
                                {{Minify: Reduce size of CSS and Javascript to limit bandwidth.}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{Name}}</th>
                            <th>{{Logs}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach (DesktopOptimize::$viewData['system_logs'] as $systemLog) : ?>
                            <tr data-id="<?php echo $systemLog['id']; ?>">
                                <td>
                                    <?php echo $systemLog['name']; ?>
                                </td>
                                <?php
                                DesktopOptimize::showActionCell($systemLog['rating'], 'system', 'log');
                                ?>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if (DesktopOptimize::$viewData['system_pip'] === true) : ?>
                <div class="row">
                    <div class="col-sm-12">
                        <h3>{{Minification}}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php if (DesktopOptimize::$viewData['system_csscompressor'] === true) : ?>
                            <button class="action-button btn btn-primary" data-id="csscompressor" data-category="system"
                                    data-type="minify"><i class="fa fa-compress"></i> {{Minify CSS}}
                            </button>
                        <?php else: ?>
                            {{The Python module 'csscompressor' is not installed.}}
                            <button class="action-button btn btn-primary" data-id="csscompressor" data-category="system"
                                    data-type="install"><i class="fa fa-puzzle-piece"></i> {{Install}}
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php if (DesktopOptimize::$viewData['system_jsmin'] === true) : ?>
                            <button class="action-button btn btn-primary" data-id="jsmin" data-category="system"
                                    data-type="minify"><i class="fa fa-compress"></i> {{Minify Javascript}}
                            </button>
                        <?php else: ?>
                            {{The Python module 'jsmin' is not installed.}}
                            <button class="action-button btn btn-primary" data-id="jsmin" data-category="system"
                                    data-type="install"><i class="fa fa-puzzle-piece"></i> {{Install}}
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-sm-12">
                        {{Python pip is not installed on your system.}}
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div id="raspberry" class="tab-pane">
            <?php if (DesktopOptimize::$viewData['rpi'] === true): ?>
                <div class="col-sm-12">
                    <?php if (DesktopOptimize::$viewData['rpi_can_optimize'] === true): ?>
                        <?php if (DesktopOptimize::$viewData['rpi_sudo'] === false): ?>
                            <div class="alert alert-warning">{{Jeedom doesn't have sudo rights.}}</div>
                        <?php endif; ?>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{Name}}</th>
                                <th>{{Description}}</th>
                                <th>{{Actions}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{GPU memory}}</td>
                                <td>{{If you don't use display of your Raspberry Pi, you can limit the GPU memory to the minimum. This parameter is called gpu_mem and is stored in the file /boot/config.txt. The default value is 64 but you can limit it to 16.}}
                                </td>
                                <?php DesktopOptimize::showActionCell(DesktopOptimize::$viewData['rating'], 'raspberry', 'gpu_mem'); ?>
                            </tr>
                            <tr>
                                <td>{{L2 Cache}}</td>
                                <td>{{If you don't use display of your Raspberry Pi, you can limit the access to the L2 Cache to the CPU.}}
                                </td>
                                <?php DesktopOptimize::showActionCell(DesktopOptimize::$viewData['rating'], 'raspberry', 'l2_cache'); ?>
                            </tr>
                            </tbody>
                        </table>
                        <div id="raspberry-change-msg" class="alert alert-danger hidden-msg"></div>
                    <?php else: ?>
                        <div class="alert alert-danger">{{Jeedom can't read the file /boot/config.txt.}}</div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
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
    msg['raspberry_config_change'] = '{{A backup file of /boot/config.txt was created at /boot/config.txt.bak. Its recommanded to reboot your system.}}';
    msg['raspberry_gpu_mem'] = '{{Do you want to limit GPU memory?}}';
    msg['raspberry_l2_cache'] = '{{Do you want disable L2 Cache?}}';
    msg['system_install'] = '{{Do you want to install selected Python module ?}}';
    msg['system_minify'] = '{{Do you want to minify all files of selected type ?}}';

    var currentScore = <?php echo DesktopOptimize::$viewData['currentScore']; ?>;
    var bestScore = <?php echo DesktopOptimize::$viewData['bestScore']; ?>;
</script>
