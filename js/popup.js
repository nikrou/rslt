$(function() {
	$('#media-insert-cancel').click(function() {
		window.close();
	});

	$('#media-insert-ok')
		.unbind('click')
		.click(function(e) {
			e.preventDefault();
			window.opener.$('#album_media_id').prop('value', $('input[name="id"]').attr('value'));
			window.close();
		});
});
