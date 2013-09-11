$(function() {
    $('.checkboxes-helpers').each(function() {
	dotclear.checkboxesHelpers(this);
    });

    $.simpleTabs(default_tab, {hashPrefix:'rslt_'});
});
