$(function() {
	$('h5.rslt').toggleWithLegend($('p.rslt'),{
		user_pref: 'dcx_rslt',
		legend_click: true
	});

	dotclear.checkboxesHelpers($('p.checkboxes-helpers'), $('.rslt.author input[type="checkbox"]'));
});

