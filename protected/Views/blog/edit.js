<script>
	
	
	$(function() {
		var postId = $('#public-id').val();
		$.ajax({
			type: 'post',
			url: SITE_URL,
			data: {
				page: 'blog_tag',
				action: 'get_existing_tags',
				post_id: postId
			},
			success: function(e) {
				var result = e.map(function(val) {
					return val.name;
				}).join(',');

				$('#tags').importTags(result);
			}
		});
	});


	$('#summernote').summernote({
    	height: 350
    });

    $('#tags').tagsInput({
    	// autocomplete_url: SITE_URL + '/blogTag/get_tags',
    	'height': '3rem',
    	'width': '100%',
    	// 'interactive': true,
    	'defaultText': 'Add a tag',
    	// 'onAddTag': function() {
    	// 	var tags = $(this).val();
    	// 	var postId = $('#public-id').val();		
    	// 	$.ajax({
    	// 		url: SITE_URL,
    	// 		data: {
    	// 			page: 'blog_tag',
    	// 			action: 'add_tags',
    	// 			post_id: postId,
    	// 			name: tags
    	// 		}, success: function(e) {
    	// 			console.log(e);
    	// 		}
    	// 	});
    	// },
    	'onChange': function() {
    		// delete the tags with the blog id
    		var tags = $(this).val();
    		var postId = $('#public-id').val();
    		$.ajax({
    			url: SITE_URL,
    			data: {
    				type: 'post',
    				page: 'blog_tag',
    				action: 'add_tags',
    				post_id: postId,
    				name: tags
    			}, success: function(e) {
    				console.log(e);
    			}
    		});

    	},
    	// 'delimiter': [',',';'],   // Or a string with a single delimiter. Ex: ';'
   		// 'removeWithBackspace' : true,
    });


	$('#deletePost').click(function(e) {
		var id = $('#public-id').val();
		$.ajax({
			type: 'post',
			url: SITE_URL + '/?page=blog&action=delete_post&id=' + id,
			success: function(response) {
				window.location.href = SITE_URL + '/?page=blog&action=manage';
			}
		});
	});

</script>