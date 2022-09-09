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
	chosenTarget.append('<option value=\"\">' + optstring + '</option>').trigger('chosen:updated');
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