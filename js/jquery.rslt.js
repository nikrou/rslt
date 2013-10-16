$(function($) {
	$('.author-filter input').click(function(e) {
		if ($(this).is(':checked')) {
			var author = $(this).val();
			$('.songs li:visible').filter(function() {
				return !($(this).attr('data-song').indexOf(author)!=-1);
			}).hide().addClass('show-'+author);
		} else {
			var author = $(this).val();
			var lis = $('.songs li.show-'+author).show().removeClass('show-'+author);
		}
	});
});

