function setAjaxSelectionBox(object, id, target_id, url, method = 'POST', onError = 'Error') {
	$.ajax({
		type    : method,
		url     : url,
		data    : object.serialize(),
		success : function(d) {
			loader(target_id, 'show');
			updateSelectChosen('select#' + target_id, true, '');
			$.each(JSON.parse(d), function(index, item) {
				$('select#' + target_id).append('<option value=\"' + id + '::' + item + '\">' + ucwords(item.replace('_', ' ')) + '</option>');
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

function mappingPageTableFieldname(id, target_id, url, method = 'POST', onError = 'Error') {
	updateSelectChosen('select#' + target_id);
	$('#' + id).change(function(e) {
		if ($(this).is(':checked')) {
			setAjaxSelectionBox($(this), id, target_id, url, method, onError);
		} else {
			loader(target_id, 'show');
			loader(target_id, 'fadeOut');
			updateSelectChosen('select#' + target_id);
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