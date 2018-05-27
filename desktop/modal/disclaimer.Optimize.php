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

include_file('core', 'authentification', 'php');

if (!isConnect('admin')) {
    throw new \Exception(__('401 - Refused access', __FILE__));
}
?>
    <div id="div_pluginOptimizeAlert"></div>
    <div id="disclaimer-modal">
        <div class="alert alert-danger" role="alert">
            <div class="row">
            <span>
                <i class="fa fa-5x fa-exclamation-triangle"></i>
            </span>
                <span>
                <p>{{Si vous avez fait l'acquisition d'un Service Pack, la société Jeedom SAS pourra vous refuser le support suite à l'utilisation de ce plugin.}}</p>
            </span>
            </div>
        </div>
        <div class="panel panel-info" style="height: 100%;">
            <div class="panel-heading" role="tab">
                <h4 class="panel-title">
                    {{Présentation}}
                </h4>
            </div>
            <div class="panel-body">
                <p>{{<b>Optimize</b> vous permettra d'optimiser l'interface et la réactivité de votre installation.}}</p>
                <ul>
                    <li>{{Désactiver des fonctionnalités inutilisées,}}</li>
                    <li>{{Minifier les fichiers CSS et Javascript,}}</li>
                    <li>{{Améliorer les paramètres système du Raspberry Pi,}}</li>
                    <li>{{Supprimer les objets inutiles.}}</li>
                </ul>
                <p>{{Il est conseillé de réaliser une sauvegarde de votre installation avant tout.}}</p>
            </div>
        </div>
        <div id="choices-row" class="row">
            <div class="col-md-6">
                <button id="do-backup" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> {{Faire une sauvegarde}}</button>
            </div>
            <div class="col-md-6">
                <button id="delete-plugin" class="btn btn-lg btn-danger"><i class="fa fa-close"></i> {{Supprimer ce plugin}}</button>
            </div>
        </div>
    </div>
<style>
    #disclaimer-modal .alert .col-md-2 {
        text-align: right;
    }

    #disclaimer-modal .alert .row {
        display: table;
    }

    #disclaimer-modal .alert .row>span {
        display: table-cell;
        float: none;
        vertical-align: middle;
    }

    #disclaimer-modal .alert i {
        padding-left: 0.2em;
    }

    #disclaimer-modal .alert p {
        padding-left: 2em;
    }

    #delete-plugin {
        float: right;
    }
</style>
<script>
    // Point d'entrée du script
    $(document).ready(function () {
        // Evènement du bouton fermer
        $('#do-backup').click(function() {
            window.location.href = '/index.php?v=d&p=backup';
        });
        // Evènement du bouton supprimer
        $('#delete-plugin').click(function() {
            $.post({
                url: 'core/ajax/update.ajax.php',
                data: {action: 'remove', id: 'Optimize'},
                dataType: 'json',
                success: function (data, status) {
                    // Test si l'appel a échoué
                    if (data.state !== 'ok' || status !== 'success') {
                        $('#div_alert').showAlert({message: data.result, level: 'danger'});
                    }
                },
                error: function (request, status, error) {
                    handleAjaxError(request, status, error);
                }
            });
        });
    });

</script>