$(function() {
	$('#album_singer').select2({
		width: '10em',
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
						id: item.id,
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
			return {
				id: term,
				text: term + ' (new)'
			}
		},
		initSelection : function(element, callback) {
			var data = [];
			$(element.val().split(',')).each(function () {
				data.push({id: this, text: this});
			});
			callback(data);
		},
		tokenSeparators: [',']
	});

});
