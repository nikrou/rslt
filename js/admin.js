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
		$('.songs ul li input[name^="position"]').hide();
		$('.songs').sortable({
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

	$('.filters-form-control')
		.show()
		.click(function(e) {
			var associated_form = $(this).parent().next();
			$(this).toggleClass('open', associated_form.is(':hidden'));
			associated_form.toggleClass('hide');
			if (associated_form.is(':hidden')) {
				$(this).text(rslt_filters.show);
			} else {
				$(this).text(rslt_filters.hide);
			}
		});

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

	$('.lockable').each(function() {
		var me = $(this);
		var form_note = me.find('.form-note');
		form_note.hide();
		var img = $('<img src="images/locker.png" alt"'+dotclear.msg.click_to_unlock+'">');
		img.css('cursor', 'pointer').click(function() {
			$(this).prev('input').prop('disabled', false);
			form_note.show();
		});
		me.find('input').each(function() {
			$(this).prop('disabled', true).after(img);
		});
		
	});
});
