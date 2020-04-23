<div class="container edit-post">
	<h1 class="text-center">{if $post->id}Edit{else}New{/if} Post</h1>

	<form name="save" method="post" action="{$SITE_URL}" enctype="multipart/form-data">
		<input type="hidden" name="module" value="blog">
		<input type="hidden" name="page" value="blog">
		<input type="hidden" name="action" value="save">
		<input type="hidden" name="id" id="public-id" value="{$post->public_id}">
		<input type="hidden" name="current_url" value="{$current_url}">
		<input type="hidden" name="current_tags" id="current-tags" value="{$tags}">
		
		<!-- title -->
		<div class="form-group">
			<label for="title">Title</label>
			<input type="text" name="title" id="title" value="{$post->title}" class="form-control">
		</div>
		<!-- /title -->

		<!-- blog content -->
		<div class="form-group">
			<label for="summernote">Content</label>
			<textarea class="form-control" name="content" id="summernote">{$post->content}</textarea>
		</div>
		<!-- /blog content -->

		<!-- cover image -->
		<div class="custom-file my-2">
			<input type="file" name="cover_image" class="custom-file-input" id="cover-image">
			<label class="custom-file-label" for="cover-image">Upload a Cover Image</label>
		</div>
		<!-- /cover image -->

		<!-- categories -->
		<div class="form-group">
			<label for="categories">Blog Category</label>
			<select name="category" id="categories" class="form-control">
				<option value="">Select a category...</option>
				{foreach from=$categories item=category}
					<option value="{$category->id}" {if $post->category_id == $category->id} selected{/if}>{$category->name}</option>
				{/foreach}
			</select>
		</div>
		<!-- /categories -->

		<!-- tags -->
		<div class="form-group">
			<label for="blog-tags">Blog Tags</label>
			<select name="blog_tags[]" id="blog-tags" multiple>
				{foreach from=$tags item="tag"}
				<option 
					value="{$tag->id}"
					{foreach from=$blogTags item="bt"}
						{if $tag->id == $bt->blog_tag_id} selected{/if}
					{/foreach}
				>{$tag->name}</option>
				{/foreach}
			</select>
		</div>
		<!-- /tags -->

		<!-- publish checkbox -->
		<div class="form-check text-right m-3">
		    <input class="form-check-input" type="checkbox" value="1" name="published" id="published" {if $post->date_published !== null}checked{/if}> 
		    <label class="form-check-label" for="published">Publish</label>
		</div>	
		<!-- /publish checkbox -->
		

		<!-- buttons -->
		<button type="submit" class="btn btn-primary ml-2 float-right">Save</button>
		<button class="btn btn-secondary float-right" type="button" onclick="history.go(-1)">Cancel</button>
		<button type="button" class="btn btn-danger button" data-toggle="modal" data-target="#deleteModal">Delete</button>	
		<!-- /buttons -->
	</form>

	{$this->loadElement('deleteModal')}
</div>


