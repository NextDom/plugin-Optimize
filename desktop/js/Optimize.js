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

$(document).ready(function () {
    $('.fa-exclamation-triangle').click(function () {
        askForChange($(this), applyCellChange);
    });
    $('.action-button').click(function () {
        askForChange($(this), applyButtonChange);
    });
    updateProgressBar();
});

/**
 * Affiche une fenêtre pour confirmer la modification
 *
 * @param item Elément lié à l'action
 * @param callbackFunction Fonction a appelée après la réception des données de la requête Ajax
 */
function askForChange(item, callbackFunction) {
    var category = item.data('category');
    var id = null;
    if (item.parent().is('td')) {
        var row = item.closest('tr');
        id = row.data('id');
    }
    else {
        id = item.data('id');
    }
    var type = item.data('type');

    $('#optimize-modal-content').html(msg[category + '_' + type]);
    $('#optimize-modal').modal();
    $('#optimize-modal-valid').unbind();
    $('#optimize-modal-valid').click(function () {
        ajaxPostRequest(item, category, id, type, callbackFunction);
        $('#optimize-modal').modal('hide');
    });
}

/**
 * Effectue une requête Ajax qui valide les modifications.
 *
 * @param item Elément HTML concerné
 * @param category Catégorie de la modification
 * @param id Identifiant de l'action
 * @param type Type de modification
 * @param callbackFunction Fonction a appelée après la réception des données de la requête Ajax
 */
function ajaxPostRequest(item, category, id, type, callbackFunction) {
    $.post({
        url: 'plugins/Optimize/core/ajax/Optimize.ajax.php',
        data: {
            category: category,
            id: id,
            type: type
        },
        dataType: 'json',
        success: function (data, status) {
            // Test si l'appel a réussi
            if (data.state !== 'ok' || status !== 'success') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
            }
            else {
                callbackFunction(item, category, type);
            }
        },
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        }
    });
}

/**
 * Applique les changements si la requête réussie
 *
 * @param item Elément HTML concerné
 * @param category Catégorie de la modification
 * @param type Type de modification
 */
function applyCellChange(item, category, type) {
    var row = item.closest('tr');
    if (type === 'enabled') {
        row.remove();
        var nbCheckItems = row.find('.fa-check-circle').length;
        var nbWarningItems = row.find('.fa-exclamation-triangle').length;
        currentScore -= nbCheckItems;
        bestScore -= nbCheckItems + nbWarningItems;
    }
    else {
        item.removeClass('fa-exclamation-triangle');
        item.addClass('fa-check-circle');
        if (category === 'raspberry') {
            $('#raspberry-change-msg').removeClass('hidden-msg');
            $('#raspberry-change-msg').html(msg['raspberry_config_change']);
        }
        currentScore++;
    }
    updateProgressBar();
}

/**
 * Applique les changements d'un bouton si la requête réussie
 *
 * @param item Elément HTML concerné
 * @param category Catégorie de la modification
 * @param type Type de modification
 */
function applyButtonChange(item, category, type) {
    if (type === 'install') {
        location.reload();
    }
}

/**
 * Met à jour l abarre de progression
 */
function updateProgressBar() {
    var percentage = parseInt(currentScore * 100 / bestScore);
    var scoreBar = $('#score');
    scoreBar.attr('aria-valuenow', percentage);
    scoreBar.css('width', percentage + '%');
    scoreBar.html(currentScore + ' / ' + bestScore);
}
