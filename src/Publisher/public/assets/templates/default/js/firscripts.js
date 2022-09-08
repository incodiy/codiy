function ucwords(str, force) {
	str=force ? str.toLowerCase() : str;  
	return str.replace(/(\b)([a-zA-Z])/g, function(firstLetter) {
		return firstLetter.toUpperCase();
	});
}

function updateSelectChosen(target, optstring = 'Select an Option') {
	$(target)
		.find('option').remove().end()
		.append('<option value=\"\">' + optstring + '</option>')
		.trigger('chosen:updated');
}

function loader(target_id, view = 'hide') {
	var _loaderTarget = '#' + target_id;
	var _loaderID     = 'cdyInpLdr' + target_id;
	
	if ('remove' == view) {
		$('span.inputloader').remove();
	} else if ('fadeOut' == view) {
		$('span.inputloader').fadeOut(1800);
	} else {
		$(_loaderTarget).before('<span class=\"inputloader loader ' + view + '\" id=\"'+ _loaderID + '\"></span>');
	}
}