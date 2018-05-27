// Point d'entrée du script
$(document).ready(function () {
    $('#show-disclaimer-modal').click(function() {
        showModal('Informations', 'disclaimer');
        return false;
    });
    if (showDisclaimer || parseInt(showDisclaimer) == 1) {
        showModal('Informations', 'disclaimer');
    }
});

/**
 * Affiche la fenêtre de configuration
 */
function showConfigModal() {
    showModal('Configuration', 'config');
}

/**
 * Affiche un popup
 *
 * @param title Titre du popup
 * @param modalName Nom du fichier du popup
 */
function showModal(title, modalName) {
    var modal = $('#md_modal');
    modal.dialog({title: title});
    modal.load('index.php?v=d&plugin=Optimize&modal='+modalName+'.Optimize').dialog('open');
}