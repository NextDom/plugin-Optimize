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

$(document).ready(function ()
{
    $('.fa-exclamation-triangle').click(function ()
    {
        askForChange($(this));
    });
});

/**
 * Affiche une fenêtre pour confirmer la modification
 *
 * @param item
 */
function askForChange(item)
{
    var category = item.data('category');
    var row = item.closest('tr');
    var id = row.data('id');
    var type = item.data('type');

    $('#optimize-modal-content').html(msg[category + '_' + type]);
    $('#optimize-modal').modal();
    $('#optimize-modal-valid').click(function ()
    {
        applyChange(item, category, id, type);
        $('#optimize-modal').modal('hide');
    });
}

/**
 * Effectue une requête Ajax qui valide les modifications.
 *
 * @param item
 * @param category
 * @param id
 * @param type
 */
function applyChange(item, category, id, type)
{
    var row = item.closest('tr');
    $.post({
        url: 'plugins/Optimize/core/ajax/Optimize.ajax.php',
        data: {
            category: category,
            id: id,
            type: type
        },
        dataType: 'json',
        success: function (data, status)
        {
            if (data.state !== 'ok' || status !== 'success')
            {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
            }
            else
            {
                if (type === 'active')
                {
                    row.remove();
                }
                else
                {
                    item.removeClass('fa-exclamation-triangle');
                    item.addClass('fa-check-circle');
                }
            }
        },
        error: function (request, status, error)
        {
            handleAjaxError(request, status, error);
        }
    });
}
