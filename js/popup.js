$(function() {
	$('#media-insert-cancel').click(function() {
		window.close();
	});

	$('.add-media').click(function(e) {
		e.preventDefault();
		window.opener.$('#album_media_id').prop('value',  $(this).attr('href').replace(/.*media_id=(\d+).*/, '$1'));
		window.close();
	});
});
