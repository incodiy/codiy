/* [ START ] MAPPING PAGE FUNCTION */
function setAjaxSelectionBox(object, id, target_id, url, method = 'POST', onError = 'Error') {
	var qtarget = null;
	var idsplit = id.split('__node__');
	var inputSource = $('input#qmod-' + idsplit[0]);
	
	$.ajax({
		type    : method,
		url     : url,
		data    : object.serialize(),
		success : function(d) {
			sourcebox = $('select#' + id);
			qtarget   = sourcebox.val();
			
			if (~$('select#' + target_id).attr('class').indexOf('field_name')) {
				$('input#qmod-' + idsplit[0]).attr({'name': 'module[' + inputSource.attr('class') + ']'});
				$('select#' + target_id).attr({'name': 'field_name[' + idsplit[0] + '][]'});
			}
			
			if (~$('select#' + target_id).attr('class').indexOf('field_value')) {
				$('select#' + target_id).attr({'name': 'field_value[' + inputSource.attr('class') + '][' + idsplit[0] + '][' + qtarget + '][]'});
			}
			
			loader(target_id, 'show');
			updateSelectChosen('select#' + target_id, true, false);
			
			$.each(JSON.parse(d), function(index, item) {
				if (item != '') {
					var optValue = null;
					
					if (~item.indexOf('_')) {
						optValue = ucwords(item.replace('_', ' '));
					} else if (~item.indexOf('.')) {
						optValue = ucwords(item.replace('.', ' '));
					} else {
						optValue = ucwords(item);
					}
					
					$('select#' + target_id).append('<option value=\"' + item + '\">' + optValue + '</option>');
				}
			});
			
			updateSelectChosen('select#' + target_id, false, false);
		},
		error: function() {
			alert(onError);
		},
		complete: function() {
			loader(target_id, 'fadeOut');
		}
	});
}

function mappingPageTableFieldname(id, target_id, url, target_opt = null, nodebtn = null, method = 'POST', onError = 'Error') {
	var node_add    = 'role-add-' + target_id;	
	var node_btn    = $('#' + nodebtn);
	var firstRemove = $('span#remove-row' + target_id);	
//	updateSelectChosen('select#' + target_id, true); if (null != target_opt) updateSelectChosen('select#' + target_opt, true);
	
	node_btn.hide();
	if ($('#' + id).is(':checked')) {
		node_btn.fadeIn(1800);
	}
	
	$('#' + id).change(function(e) {
		if ($(this).is(':checked')) {
			node_btn.fadeIn(1800);
			
			setAjaxSelectionBox($(this), id, target_id, url, method, onError);
		} else {
			loader(target_id, 'show');
			loader(target_id, 'fadeOut');
			updateSelectChosen('select#' + target_id, true);
			
			if (null != target_opt) {
				loader(target_opt, 'show');
				loader(target_opt, 'fadeOut');
				updateSelectChosen('select#' + target_opt, true);
			}
			
			firstRemove.fadeOut(1000);
			
			node_btn.fadeOut(1800);
			$('#reset' + nodebtn).fadeOut(500);
			$('.' + node_add).chosen('destroy').fadeOut(500, function() { $(this).remove(); });
		}
	});
}

function rowButtonRemovalMapRoles(id, target_id, url = null) {
	$('span#remove-row' + id).click(function(e) {
		$('tr#row-box-' + id).fadeOut(300, function() { $(this).remove(); });
	});
}

function mappingPageFieldnameValues(id, target_id, url = null, method = 'POST', onError = 'Error') {
	var firstRemove = $('span#remove-row' + id);
//	updateSelectChosen('select#' + target_id, true);
	
	$('#' + id).change(function(e) {
		if ($(this).val() !== '') {
			setAjaxSelectionBox($(this), id, target_id, url, method, onError);
			
			firstRemove.fadeIn(1000);
		} else {
			loader(target_id, 'show');
			loader(target_id, 'fadeOut');
			updateSelectChosen('select#' + target_id, true);
			firstRemove.fadeOut(1000);
		}
	});
}

function firstResetRowButton(id, target_id, second_target, url, method = 'POST', onError = 'Error', withAction = true) {
	var firstRemove = $('span#remove-row' + target_id);
	
	if (true === withAction) {
		firstRemove.click(function(e) {
			setAjaxSelectionBox($('#' + id), id, target_id, url.replace('field_name', 'table_name'), method, onError);
			mappingPageFieldnameValues(target_id, second_target, url, method, onError);
			updateSelectChosen('select#' + second_target, true, false);
			$(this).fadeOut(1000);
		});
		
	} else {
		setAjaxSelectionBox($('#' + id), id, target_id, url.replace('field_name', 'table_name'), method, onError);
		mappingPageFieldnameValues(target_id, second_target, url, method, onError);
		updateSelectChosen('select#' + second_target, true, false);
		firstRemove.fadeOut();
	}
}

function mappingPageButtonManipulation(node_btn, id, target_id, second_target, url, method = 'POST', onError = 'Error') {
	var node_add      = 'role-add-' + target_id;
	var baserowbox    = $('tr#row-box-' + target_id);
	var tablecource   = baserowbox.parent('tbody').parent('table');
	
	var firstRemove   = $('span#remove-row' + target_id);
	var fieldnamebox  = $('select#' + target_id);
	var fieldvaluebox = $('select#' + second_target);
	
	$('span#remove-row' + target_id).click(function(e) {
	//	alert($(this));
	});
	
	$('#reset' + node_btn).hide();	
	$('#plusn' + node_btn).click(function(e) {
		$('span.inputloader').removeAttr('style').hide();
		
		if (firstRemove.attr('style').trim()) {
			firstRemove.attr({'style': ''}).fadeIn();
		}
		
		var random_target_id     = target_id       + diy_random();
		var random_second_target = second_target   + diy_random();
		var node_row             = 'remove-row'    + random_target_id;
		var nextcloneid          = 'row-box-'      + random_target_id;
		var clonerowbox          = baserowbox.clone().attr({'id': nextcloneid, 'class': baserowbox.attr('class') + ' ' + node_add});
		
		clonerowbox.find('td').each(function(x, n) {
			if (~$(this).attr('class').indexOf("field-name-box")) {
				$(this).children('div.chosen-container').remove();				
				$(this).children('select').attr({'id': random_target_id}).chosen();
			}
			
			if (~$(this).attr('class').indexOf("field-value-box")) {
				$(this).children('div.chosen-container').remove();
				$(this).children('select').attr({'id': random_second_target, 'name': ''}).chosen();
				$(this).children('span#remove-row' + target_id)
					.removeAttr('id').attr({'id': node_row})
					.find('.fa')
					.attr({'class': 'fa fa-minus-circle danger'});
			}
		});
		clonerowbox.appendTo(tablecource);
		mappingPageFieldnameValues(random_target_id, random_second_target, url, method, onError);
		
		if (clonerowbox.length >= 1) {
			firstRemove.fadeIn();
			$('#reset' + node_btn).fadeIn();
		} else {
			$('#reset' + node_btn).fadeOut();
		}
		
		$('span#' + node_row).click(function(x) {
			$('tr#row-box-' + random_target_id).fadeOut(300, function() { $(this).remove(); });
		});
	});
	
	$('#reset' + node_btn).click(function(e) {
		$('.'  + node_add).chosen('destroy').fadeOut(500, function() { $(this).remove(); });
		$('#reset' + node_btn).fadeOut(500);
		
		firstResetRowButton(id, target_id, second_target, url, method, onError, false);
	});
	
	firstResetRowButton(id, target_id, second_target, url, method, onError);
}
/* [ CLOSED ] MAPPING PAGE FUNCTION */