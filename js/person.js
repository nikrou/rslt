$(function() {
	var select2_params = {
		width: '50%',
		placeholder: $(this).attr('data-placeholder'),
		minimumInputLength: 2,
		multiple: true,
		ajax: {
			url: rslt_person_service,
			dataType: 'json',
			quietMillis: 250,
			data: function(term, page) {
				return {
					q: term
				};
			},
			results: function(data, page) {
				var results = [];
				$.each(data, function(index, item){
					results.push({
						id: '~~'+item.id+'~~',
						text: item.title
					});
				});
				return {
					results: results
				};
			},
			cache: true
		},
		createSearchChoice: function(term, data) {
			if ($(data).filter(function() {
				return this.text.localeCompare(term)===0;
			}).length===0) {
				return {id:term, text:term+' (new)'};
			}
		},
		initSelection : function(element, callback) {
			var data = [];
			var elements = all_elements[element.attr('data-elements')];
			for (var n=0,l=elements.length;n<l;n++) {
				data.push({id: '~~'+elements[n]['id']+'~~', text: elements[n]['title']});
			}
			callback(data);
		},
		tokenSeparators: [',']
	};

	$('.select2').select2(select2_params);

	if ($.fn.sortable) {
		$('ul.select2-choices').sortable({
			containment: 'parent',
			start: function() { $('.select2').select2('onSortStart'); },
			update: function() { $('.select2').select2('onSortEnd'); }
		});
	}
});
