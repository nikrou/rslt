(function($) {
    'use strict';
    
    $.simpleTabs = function(start_tab, opts) {
	var defaults = {
	    containerClass: 'part-tabs',
	    hashPrefix: 'tab_',
	    contentClass: 'multi-part',
	    activeClass: 'part-tabs-active'
	};

	var options = $.extend({}, defaults, opts);
	var active_tab = start_tab || getId($('.'+options.contentClass).get(0).attr('href'));
	var hash = document.location.hash.split('#').join('');
	
	if (hash !== undefined && hash) {
	    $('ul li a[href$="#'+options.hashPrefix+hash+'"]').parent().trigger('click');
	    active_tab = hash;
	} else if (active_tab.length>0) {
	    $('#'+options.hashPrefix+active_tab).addClass('active').show();
	}    
    
	createTabs(active_tab, options);

	$('ul li', '.'+options.containerClass).click(function(e) {
	    $(this).parent().find('li.'+options.activeClass).removeClass(options.activeClass);
	    $(this).addClass(options.activeClass);
	    $('.'+options.contentClass+'.active').removeClass('active').hide();
	    $('#'+options.hashPrefix+getId($(this).find('a').attr('href'))).addClass('active').show();
	});

	return this;
    };

    var createTabs = function createTabs(start_tab, options) {
    	var lis = [], li_class = '';

    	$('.'+options.contentClass).each(function() {
	    if ((options.hashPrefix + start_tab) != $(this).attr('id')) {
	    	$(this).hide();
		li_class = '';
	    } else {
		$(this).addClass('active');
		li_class = ' class="'+options.activeClass+'"';
	    }
   	    lis.push('<li'+li_class+'><a href="#'+$(this).attr('id').replace(options.hashPrefix,'')+'">'+$(this).attr('title')+'</a></li>');
    	});
	
	$('<div class="'+options.containerClass+'"><ul>'+lis.join('')+'</ul></div>')
	    .insertBefore($('.'+options.contentClass).get(0));	
    };

    var getId = function getId(href) {
    	return href.split('#').join('');
    };
})(jQuery);
