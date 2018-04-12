$(document).ready(function(){$('.fa-exclamation-triangle').click(function(){if($(this).is('td')){askForChange($(this),applyCellChange);}
else{askForChange($(this),applyAllCellsChange);}});$('.action-button').click(function(){askForChange($(this),applyButtonChange);});updateProgressBar();});function askForChange(item,callbackFunction){var category=item.data('category');var id=null;var itemParent=item.parent();if(itemParent.is('td')||itemParent.is('th')){var row=item.closest('tr');id=row.data('id');}
else{id=item.data('id');}
var type=item.data('type');$('#optimize-modal-content').html(msg[category+'_'+type]);$('#optimize-modal').modal();$('#optimize-modal-valid').unbind();$('#optimize-modal-valid').click(function(){ajaxPostRequest(item,category,id,type,callbackFunction);$('#optimize-modal').modal('hide');});}
function ajaxPostRequest(item,category,id,type,callbackFunction){$.post({url:'plugins/Optimize/core/ajax/Optimize.ajax.php',data:{category:category,id:id,type:type},dataType:'json',success:function(data,status){if(data.state!=='ok'||status!=='success'){$('#div_alert').showAlert({message:data.result,level:'danger'});}
else{callbackFunction(item,category,type);}},error:function(request,status,error){handleAjaxError(request,status,error);}});}
function applyCellChange(item,category,type){var row=item.closest('tr');if(type==='enabled'){row.remove();var nbCheckItems=row.find('.fa-check-circle').length;var nbWarningItems=row.find('.fa-exclamation-triangle').length;currentScore-=nbCheckItems;bestScore-=nbCheckItems+nbWarningItems;}
else{item.removeClass('fa-exclamation-triangle');item.addClass('fa-check-circle');if(category==='raspberry'){$('#raspberry-change-msg').removeClass('hidden-msg');$('#raspberry-change-msg').html(msg['raspberry_config_change']);}
currentScore++;}
updateProgressBar();}
function applyAllCellsChange(item,category,type){var th=item.parent();var position=th.index();var tbody=th.parent().parent().parent().find('tbody');tbody.children().each(function(){applyCellChange($(this).find('i'),category,type);});item.removeClass('fa-exclamation-triangle');item.addClass('fa-check-circle');}
function applyButtonChange(item,category,type){if(type==='install'){location.reload();}}
function updateProgressBar(){var percentage=parseInt(currentScore*100/bestScore);var scoreBar=$('#score');scoreBar.attr('aria-valuenow',percentage);scoreBar.css('width',percentage+'%');scoreBar.html(currentScore+' / '+bestScore);}
