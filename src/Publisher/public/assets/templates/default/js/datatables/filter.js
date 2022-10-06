function dttb_selectbox_next_target(identity, next_target, _nxvar, _reidentvar, nest, _spldtvar, _splvar, _reidentity) {
	var _nxvar      = next_target;
	var _reidentvar = _nxvar.replace('_', ' ');
	$('#' + next_target).empty()
		.append('<option value=\"\">No Data ' + ucwords(_reidentvar) + ' Found</option>')
		.prop('disabled', true)
		.trigger('chosen:updated');
		
	if (null != nest && '' != nest) {
		var _spldtvar = nest;
		var _splvar = _spldtvar.split('|');
		$.each(_splvar, function(i,obj) {
			if (null != obj && identity != obj) {
				var _reidentity = obj.replace('_', ' ');
				$('#' + obj).empty()
					.append('<option value=\"\">No Data ' + ucwords(_reidentity) + ' Found</option>')
					.prop('disabled', true).trigger('chosen:updated');
			}
		});
	}
}