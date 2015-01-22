$(function() {
	$('.checkboxes-helpers').each(function() {
		dotclear.checkboxesHelpers(this);
	});

	$('#remove-songs').click(function(e) {
		var count_checked = $('input[name="songs[]"]:checked', $('#songs-rank-form')).length;
		if (count_checked==0) {
			return false;
		}

		return window.confirm(rslt_confirm_remove_songs_from_album.replace('%s',count_checked));
	});

	if ($.fn['sortable']!==undefined) {
		$('.songs-album ul li input[name^="position"]').hide();
		$('.songs-album').sortable({
			axis: 'y',
			items: 'ul li',
			update: function(event, ui) {
				$('input[name="save_order"]').prop('disabled', false).removeClass('disabled');
				$('ul li input[name^="position"]').each(function(i) {
					$(this).val(i+1);
				});
			}
		});
	}

	if ($('#songs_action').val()=='' && $('#songs_action').val()!='associate_to_album') {
		$('#album-input').addClass('hide');
	}

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

	$('#form-albums, #form-songs').submit(function() {
		var action = $(this).find('select[name="action"]').val();
		var objects = $(this).attr('id').replace(/form-/, '');
		var count_checked = $('input[name="'+objects+'[]"]:checked', $(this)).length;
		if (count_checked==0) {
			return false;
		}
		if (action=='delete') {
			if (count_checked==1) {
				objects = objects.substring(0,objects.length-1);
			}

			return window.confirm(rslt_confirm_delete[objects].replace('%s',count_checked));
		}

		return true;
	});

	$('#album-form, #song-form').submit(function() {
		// enable url field so it can be transmitted
		$('input:disabled').prop('disabled', false);

		return true;
	});

	$('.lockable').each(function() {
		var me = $(this);
		var form_note = me.find('.form-note');
		form_note.hide();
		var img = $('<img class="locker" src="images/locker.png" alt"'+dotclear.msg.click_to_unlock+'">');
		img.click(function() {
			$(this).prev('input').prop('disabled', false);
			form_note.show();
		});
		me.find('input').each(function() {
			$(this).prop('disabled', true).after(img);
		});

	});

	$('#album_media_id').click(function() {
		window.the_toolbar = this; // unused but needed by admin/js/jsToolbar/popup_media.js

		var open_url = 'media.php?p=rslt&popup=1&media_id='+$('#album_id').attr('value');
		var p_win = window.open(open_url,'dc_popup',
					'alwaysRaised=yes,dependent=yes,toolbar=yes,height=500,width=760,'+
					'menubar=no,resizable=yes,scrollbars=yes,status=no');

		return false;
	});

	if ($('.show-media').length>0) {
		var media_icon = $('.media-icon');
		media_icon.hide();
		$('.show-media').click(function() {
			if (media_icon.is(':visible')) {
				media_icon.hide();
			} else {
				media_icon.show();
			}
		});
	}
});
