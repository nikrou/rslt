$(function() {
	$('.author-filter').on('click', 'input', function(e) {
		if ($(this).is(':checked')) {
			var author = $(this).val();
			$('.songs li').each(function() {
				if ($(this).attr('data-song').indexOf(author)==-1) {
					if ($(this).attr('class')===undefined || !$(this).attr('class').match(/show-/)) {
						$(this).hide();
					}
				} else {
					$(this).addClass('show-'+author).show();
				}
			});
		} else {
			var author = $(this).val();
			$('.songs li').each(function() {
				if ($(this).attr('data-song').indexOf(author)!=-1) {
					$(this).removeClass('show-'+author);
				}
				if ($(this).attr('class')===undefined || !$(this).attr('class') 
				    || !$(this).attr('class').match(/show-/)) {
					$(this).hide();
				}
			});			
			if ($('input:checked').length==0) {
				$('.songs li').show();
			}
		}
	});
});

