function ajaxSelectionProcess(object, id, target_id, url, data = [], method = 'POST', onError = 'Error') {
	var dataInfo = JSON.parse(data);
	
	if (typeof dataInfo.labels   != 'undefined') var lURL     = 'l=' + dataInfo.labels;
	if (typeof dataInfo.values   != 'undefined') var vURL     = 'v=' + dataInfo.values;
	if (typeof dataInfo.selected != 'undefined') var sURL     = 's=' + dataInfo.selected;
	if (typeof dataInfo.query    != 'undefined') var qURL     = diy_random() + '=' + dataInfo.query;
	
	if (typeof dataInfo.labels != 'undefined' && typeof dataInfo.values != 'undefined' && typeof dataInfo.selected != 'undefined' && typeof dataInfo.query != 'undefined') {
		var urls = url + '&' + lURL + '&' + vURL + '&' + sURL + '&' + qURL;
	} else {
		if (typeof dataInfo.selected != 'undefined') {
			var urls = url + '&' + sURL;
		} else {
			var urls = url;
		}
	}
	
	var selected = null;
	var pinned   = '';
	
	$.ajax({
		type    : method,
		url     : urls,
		data    : object.serialize(),
		success : function(d) {
			var result = JSON.parse(d);
			selected   = result.selected;
			
			loader(target_id, 'show');
			updateSelectChosen('select#' + target_id, true, '');
			$.each(result.data, function(value, label) {				
				if (selected === value) {
					pinned = ' selected';
				} else {
					pinned = '';
				}
				
				if (value != '') {
					var optionLabel = null;
					
					if (~label.indexOf('_')) {
						optionLabel = ucwords(label.replaceAll('_', ' '));
					} else if (~label.indexOf('.')) {
						optionLabel = ucwords(label.replaceAll('.', ' '));
					} else {
						optionLabel = ucwords(label);
					}
					
					$('select#' + target_id).append('<option value=\"' + value + '\"' + pinned + '>' + optionLabel + '</option>');
				}
			});
			updateSelectChosen('select#' + target_id, false, false);
		},
		error: function(xhr, status, error) {
			var err = eval("(" + xhr.responseText + ")");
			console.log(xhr);
			alert(xhr);
		},
		complete: function() {
			loader(target_id, 'fadeOut');
		}
	});
}

function ajaxSelectionBox(id, target_id, url, data = [], method = 'POST', onError = 'Error') {
	var object   = $('select#' + id);
	if (object.val() !== '') {
		ajaxSelectionProcess(object, id, target_id, url, data, method, onError);
	}
	object.change(function(e) {
		ajaxSelectionProcess(object, id, target_id, url, data, method, onError);
	});
}

function ucwords(str, force) {
	str=force ? str.toLowerCase() : str;  
	return str.replace(/(\b)([a-zA-Z])/g, function(firstLetter) {
		return firstLetter.toUpperCase();
	});
}

function updateSelectChosen(target, reset = true, optstring = 'Select an Option') {
	var chosenTarget = $(target);
	if (true === reset) {
		chosenTarget.find('option').remove().end();
	}
	if (false !== optstring) {
		chosenTarget.append('<option value=\"\">' + optstring + '</option>').trigger('chosen:updated');
	} else {
		chosenTarget.trigger('chosen:updated');
	}
}

function loader(target_id, view = 'hide') {
	var _loaderTarget = '#' + target_id;
	var _loaderID     = 'cdyInpLdr' + target_id;
	
	if ('remove' == view) {
		$('span.inputloader').remove();
	} else if ('fadeOut' == view) {
		$('span.inputloader').fadeOut(1800, function() { $(this).remove(); });
	} else {
		$(_loaderTarget).before('<span class=\"inputloader loader ' + view + '\" id=\"'+ _loaderID + '\"></span>');
	}
}

function diy_random(length = 8) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
	for ( var i = 0; i < length; i++ ) {
		result += characters.charAt(Math.floor(Math.random() * charactersLength));
	}
	return result;
}

function diy_array_to_object(array) {
	return Object.assign({}, array);
}

function exportFromModal(modalID, exportID, filterID, token, url, link) {
	$('#exportFilterButton' + modalID).on('click', function(event) {
		$(this).css({
			'position': 'relative',
			'width': '138px',
			'text-align': 'left'
		}).append('<span id="loader_'+ modalID +'" class="inputloader loader" style="right:8px;width:20px;height:20px;top:7px;background-size:20px"></span>');
		
		var inputFilters        = $('#' + modalID + ' > .form-group.row > .input-group.col-sm-9 > select.' + exportID);
		var inputData           = [];
		inputData['exportData'] = true;
		inputData['_token']     = token;
		inputFilters.each(function(x, y) {
			inputData[y.name]   = y.value;
		});
		if (null != link) {
			inputData['lurExp'] = link;
		}
		
		$.ajax ({
			type    : 'POST',
			data    : diy_array_to_object(inputData),
			dataType: 'JSON',
			url     : url,
			success : function(n) {
				window.location.href = n.diyExportStreamPath;
			},
			complete : function() {
				$('#exportFilterButton' + modalID).removeAttr('style');
				$('#loader_'+ modalID).remove();
				$('#' + filterID).modal('hide');
			}
		});
	});
}