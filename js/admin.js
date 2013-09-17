$(function() {
	$('.checkboxes-helpers').each(function() {
		dotclear.checkboxesHelpers(this);
	});

	$('#form-songs').submit(function() {
		var action = $(this).find('select[name="action"]').val();
		var checked = false;
		
		$(this).find('input[name="songs[]"]').each(function() {
			if (this.checked) {
				checked = true;
			}
		});
		
		if (!checked) { return false; }
		
		if (action == 'delete') {
			return window.confirm(rslt_confirm_delete_songs.replace('%s',$('input[name="songs[]"]:checked').size()));
		}
		
		return true;
	});

	$('#form-albums').submit(function() {
		var action = $(this).find('select[name="action"]').val();
		var checked = false;
		
		$(this).find('input[name="albums[]"]').each(function() {
			if (this.checked) {
				checked = true;
			}
		});
		
		if (!checked) { return false; }
		
		if (action == 'delete') {
			return window.confirm(rslt_confirm_delete_albums.replace('%s',$('input[name="albums[]"]:checked').size()));
		}
		
		return true;
	});

	$.simpleTabs(default_tab, {hashPrefix:'rslt_'});
});
