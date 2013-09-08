(function($) {
    'use strict';
    $.fn.rsltTabs = function(opts) {
	var options = $.extend({contentClass: 'multi-part', activeClass: 'part-tabs-active'}, opts);

	return this.each(function() {
	    var me = $(this);

	    $('ul li', me).click(function(e) {
		e.preventDefault();
		$(this).parent().find('li.'+options.activeClass).removeClass(options.activeClass);
		$(this).addClass(options.activeClass);
		$('.'+options.contentClass+'.active').removeClass('active').hide();
		$('#rslt_'+getId($(this).find('a').attr('href'))).addClass('active').show();
	    });

	    var active_tab = $('ul li.'+options.activeClass, me);
	    var hash = document.location.hash.split('#').join('');

	    if (hash !== undefined && hash) {
		$('ul li a[href$="#'+hash+'"]').parent().trigger('click');
	    } else if (active_tab.length>0) {
		$('#rslt_'+getId(active_tab.find('a').attr('href'))).addClass('active').show();
	    }

	    $('.multi-part.'+options.contentClass+":not('.active')").hide();
	});

	function getId(href) {
	    return href.split('#').join('');
	};
    };
})(jQuery);
