var rslt_menu_settings = {
	img_on_src: dotclear.img_menu_off,
	img_off_src: dotclear.img_menu_on,
	legend_click: true,
	speed: 100
};

$(function() {
	$('#rslt-menu h3:first')
		.toggleWithLegend($('#rslt-menu ul:first'),
				  $.extend({user_pref:'dc_rslt_menu'}, rslt_menu_settings)
				 );
});
