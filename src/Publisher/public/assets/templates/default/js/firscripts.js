function ucwords(str, force) {
	str=force ? str.toLowerCase() : str;  
	return str.replace(/(\b)([a-zA-Z])/g, function(firstLetter) {
		return firstLetter.toUpperCase();
	});
}

function diy_random(length = 8) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
	for ( var i = 0; i < length; i++ ) result += characters.charAt(Math.floor(Math.random() * charactersLength));
	
	return result;
}

function diy_array_to_object(array) {
	return Object.assign({}, array);
}

function updateSelectChosen(target, reset = true, optstring = 'Select an Option') {
	var chosenTarget = $(target);
	if (true === reset) chosenTarget.find('option').remove().end();
	if (false !== optstring) {
		chosenTarget.append('<option value="">' + optstring + '</option>').trigger('chosen:updated');
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
		$(_loaderTarget).before('<span class="inputloader loader ' + view + '" id="'+ _loaderID + '"></span>');
	}
}