$(function() {
	$('.checkboxes-helpers').each(function() {
		dotclear.checkboxesHelpers(this);
	});

	$('#filters-songs')
		.show()
		.click(function(e) {
			$(this).toggleClass('open', $('#filters-songs-form').is(':hidden'));
			$('#filters-songs-form').toggleClass('hide');
			if ($('#filters-songs-form').is(':hidden')) {
				$(this).text(rslt_filters.show);
			} else {
				$(this).text(rslt_filters.hide);
			}
			e.preventDefault();
		});

	if ($('#songs_action').val()=='' && $('#songs_action').val()!='associate_to_album') {
		$('#album-input').addClass('hide');
	}

	$('#form-songs').submit(function() {
		var action = $(this).find('select[name="action"]').val();
		var count_checked = $('input[name="songs[]"]:checked', $(this)).length;
		if (count_checked==0) {
			return false;
		}
		if (action=='delete') {
			return window.confirm(rslt_confirm_delete_songs.replace('%s',count_checked));
		}
		
		return true;
	});

	$('#songs_action').change(function() {
		var action = $(this).val();
		if (action=='associate_to_album') {
			$('#album-input')
				.removeClass('hide')
				.autocomplete({
					source: rslt_albums_service,
					delay: 1000,
					minLength: 3,
					select: function(e,ui) {
						$('#album-id').val(ui.item.id);
					},
					appendTo: '#albums_selection'
				});
		}
	});

	$('#form-albums').submit(function() {
		var action = $(this).find('select[name="action"]').val();
		console.log('action=',action);
		if (action=='delete') {
			var count_checked = $('input[name="albums[]"]:checked', $(this)).length;
			if (count_checked==0) {
				return false;
			}

			return window.confirm(rslt_confirm_delete_albums.replace('%s',count_checked));
		}
		
		return true;
	});
});
