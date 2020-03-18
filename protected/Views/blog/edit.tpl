<div class="container">
	<h1 class="text-center">{if $post->id}Edit{else}New{/if} Post</h1>

	<form name="save" method="post" action="{$SITE_URL}/blog/save">
		<input type="hidden" name="id" id="public-id" value="{$post->public_id}">
		<input type="hidden" name="current_url" value="{$current_url}">
		
		<div class="form-group">
			<label for="title">Title</label>
			<input type="text" name="title" id="title" value="{$post->title}" class="form-control">
		</div>
		<div class="form-group">
			<label for="summernote">Content</label>
			<textarea class="form-control" name="content" id="summernote">{$post->content}</textarea>
		</div>
		<div class="form-check text-right m-3">
		    <input class="form-check-input" type="checkbox" value="1" name="published" id="published" {if $post->date_published !== null}checked{/if}> 
		    <label class="form-check-label" for="published">Publish</label>
		</div>	
		
		<button type="submit" class="btn btn-primary button float-right">Save</button>
		<a href="{$SITE_URL}/?page=blog&amp;action=manage" type="button" class="btn btn-secondary button float-right text-white">Cancel</a>
		<button type="button" class="btn btn-danger button" data-toggle="modal" data-target="#deleteModal">Delete</button>	
	</form>


	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">DELETE POST</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to do this? Deleting the post cannot be undone. Please confirm that you want to delete this post.</p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-danger" id="deletePost" type="button">Yes, Delete</button>
					<button class="btn btn-secondary" type="button" data-dismiss="modal">No, Do not Delete</button>
				</div>
			</div>
		</div>	
	</div>
</div>

<script>
	$('#summernote').summernote({
    	height: 350
    });


	$('#deletePost').click(function(e) {
		var id = $('#public-id').val();
		console.log(id);
		$.ajax({
			type: 'post',
			url: SITE_URL + '/?page=blog&action=delete_post&id=' + id,
			success: function(response) {
				window.location.href = SITE_URL + '/?page=blog&action=manage';
			}
		});
	});

</script>
