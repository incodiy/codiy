function setAjaxSelectionBox(object, id, target_id, url, method = 'POST', onError = 'Error') {
	$.ajax({
		type    : method,
		url     : url,
		data    : object.serialize(),
		success : function(d) {
			
			loader(target_id, 'show');
			updateSelectChosen('select#' + target_id, true, '');
			
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
					
					$('select#' + target_id).append('<option value=\"' + id + '::' + item + '\">' + optValue + '</option>');
				}
			});
			
			updateSelectChosen('select#' + target_id, false, '');
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
	var node_btn = $('#' + nodebtn);
	node_btn.hide();
	
	updateSelectChosen('select#' + target_id);
	if (null != target_opt) {
		updateSelectChosen('select#' + target_opt);
	}
	
	$('#' + id).change(function(e) {
		if ($(this).is(':checked')) {
			node_btn.fadeIn(1800);
			
			setAjaxSelectionBox($(this), id, target_id, url, method, onError);
		} else {
			node_btn.fadeOut(1800);
			
			loader(target_id, 'show');
			loader(target_id, 'fadeOut');
			updateSelectChosen('select#' + target_id);
			
			if (null != target_opt) {
				loader(target_opt, 'show');
				loader(target_opt, 'fadeOut');
				updateSelectChosen('select#' + target_opt);
			}
		}
	});
}

function mappingPageFieldnameValues(id, target_id, url, method = 'POST', onError = 'Error') {
	updateSelectChosen('select#' + target_id);
	$('#' + id).change(function(e) {
		if ($(this).val() !== '') {
			setAjaxSelectionBox($(this), id, target_id, url, method, onError);
		} else {
			loader(target_id, 'show');
			loader(target_id, 'fadeOut');
			updateSelectChosen('select#' + target_id);
		}
	});
}

function mappingPageButtonManipulation(node_btn, id, target_id, second_target, url, method = 'POST', onError = 'Error') {
	$('#plus' + node_btn).click(function(e) {
		var random_target_id     = target_id + diy_random();
		var random_second_target = second_target + diy_random();
		
		var clone_target_id      = $('#' + target_id).clone().attr({'id': random_target_id, 'style': 'margin-top:4px'}).insertAfter($('#' + target_id));
		clone_target_id.chosen();
		
		var clone_second_target  = $('#' + second_target).clone().attr({'id': random_second_target, 'style': 'margin-top:4px'}).insertAfter($('#' + second_target));
		clone_second_target.chosen();
		
		mappingPageFieldnameValues(random_target_id, random_second_target, url, method, onError);
	});
}