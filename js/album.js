$(function() {
	$('#album_singer').select2({
		width: '50%',
		placeholder: "Singer ?",
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
						text: item.name
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
			for (var n=0,l=singers.length;n<l;n++) {
				data.push({id: '~~'+singers[n]['id']+'~~', text: singers[n]['name']});
			}
			callback(data);
		},
		tokenSeparators: [',']
	});

	if ($.fn.sortable) {
		$('ul.select2-choices').sortable({
			containment: 'parent',
			start: function() { $("#album_singer").select2("onSortStart"); },
			update: function() { $("#album_singer").select2("onSortEnd"); }
		});
	}
});
