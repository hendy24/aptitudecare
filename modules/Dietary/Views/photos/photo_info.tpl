<!-- /modules/Dietary/Views/photos/photo_info.tpl -->
<script>
	$(document).ready(function() {	

		var rows = $('.photo-info').length;
		if (rows === 0) {
			window.location = SITE_URL + "/?module=Dietary&page=photos&action=photos";
		}

		$('.message').hide();
		// Save the photo
		$('.save-photo-info').click(function(e) {
			e.preventDefault();
			var photoId = $(this).parent().find('.photo_id').val();
			var catId = $(this).parent().find('.categories :selected').val();
			var subCatId = $(this).parent().find('.subcategories :selected').val();
			var currentUrl = $('#currentUrl').val();

			// get the row to make it fade out
			var photoRow = $(this).parent();

			// if there are no rows left then redirect to view_photos

			if (catId === '') {
				$('.message').show();
				$(this).parent().find('.message').append('<p>Please select a category</p>');
			} else {
				$.post(SITE_URL, {
					page: 'photos',
					action: 'save_photo_info',
					photo_id: photoId,
					category: catId,
					subcategory: subCatId,
					current_url: currentUrl
				},
				function(data) {
					photoRow.fadeOut('slow');
					rows = rows - 1;

					if (rows === 0) {
						window.location = SITE_URL + "/?module=Dietary&page=photos&action=photos";
					}
				});
			}
		});


		// $("input#save-photo").on("click", function(e) {
		// 	e.preventDefault();
		// 	table = $(this).parent().parent().parent();
		// 	key = table.parent().children("input:hidden:first").val();
		// 	data = $("#photo-info-" + key).serialize();

		// 	$.ajax({
		// 		type: 'post',
		// 		url: SITE_URL + "/?page=photos&action=save_photo_info",
		// 		data: data,
		// 		success: function() {
		// 			table.parent().parent().fadeOut('slow');
		// 			formCount = formCount - 1;

		// 			if (formCount === 0) {
		// 				window.location = SITE_URL + "/?module=Dietary&page=photos&action=view_photos";
		// 			} 

		// 		}
		// 	});
		
		// });

		$('.categories').on('change', function() {
			var catId = $(this).val();
			var subCat = $(this).parent().next().children('.subcategories');
			var subCatOptions = subCat.find('option');
			console.log(subCat);
			$.get(SITE_URL, {
				page: 'photos',
				action: 'find_subcategories',
				category: catId
			},
			function(data) {
				$.each(data, function(key, value) {
					subCatOptions.remove();
					subCat.append('<option value="' + value.id + '">' + value.name + '</option>');
				});
			}, 'json' );
		});

	});

</script>

<div class="container">
	<h1>Add Photo Info</h1>
	<br>
	<input type="hidden" id="currentUrl" name="current_url" value="{$current_url}">
	<form action="">
	{foreach from=$photos item=photo key=key}
		<div class="row photo-info">
			<div class="col-md-4">
				<input type="hidden" class="photo_id" value="{$photo->public_id}">
				<img src="{$SITE_URL}/files/dietary_photos/thumbnails/{$photo->filename}" alt="">
			</div>
			<div class="col-md-8">
				<div class="form-group">
					<div class="message alert alert-danger" role="alert"></div>
					<label for="categories">Category</label>
					<select name="categories" class="categories">
						<option value="">Select a category</option>
						{foreach from=$categories item=category}
						<option value="{$category->id}">{$category->name}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group">
					<label for="subcategories">Subcategory</label>
					<select name="subcategories" class="subcategories"></select>
				</div>
			</div>
			<a class="btn btn-primary right save-photo-info">Save</a>
		</div>		
	{/foreach}
	
	</form>

</div>
