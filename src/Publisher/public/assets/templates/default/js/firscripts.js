function ajaxSelectionProcess(object, id, target_id, url, data = [], method = 'POST', onError = 'Error') {
	var dataInfo = JSON.parse(data);
	var lURL     = 'l=' + dataInfo.labels;
	var vURL     = 'v=' + dataInfo.values;
	var sURL     = 's=' + dataInfo.selected;
	var qURL     = diy_random() + '=' + dataInfo.query;
	var selected = null;
	var pinned   = null;
	
	$.ajax({
		type    : method,
		url     : url + '&' + lURL + '&' + vURL + '&' + sURL + '&' + qURL,
		data    : object.serialize(),
		success : function(d) {
			var result = JSON.parse(d);
			selected   = result.selected;
			
			loader(target_id, 'show');
			updateSelectChosen('select#' + target_id, true, '');
			
			$.each(result.data, function(index, item) {
				if (selected === item) {
					pinned = ' selected';
				}
				
				if (item != '') {
					var optValue = null;
					
					if (~item.indexOf('_')) {
						optValue = ucwords(item.replaceAll('_', ' '));
					} else if (~item.indexOf('.')) {
						optValue = ucwords(item.replaceAll('.', ' '));
					} else {
						optValue = ucwords(item);
					}
					
					$('select#' + target_id).append('<option value=\"' + item + '\"' + pinned + '>' + optValue + '</option>');
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