<div class="container">
	<div class="row">
		<div class="col-sm"></div>
		<div class="col-sm">
			<h1 class="text-center">Manage Posts</h1>
		</div>
		<div class="col-sm">
			<a href="{$SITE_URL}/blog/edit" class="button btn-primary text-white float-right">New Post</a>
		</div>
	</div>

	<table class="table">
		<thead class="thead-dark">
			<tr>
				<th scope="col">Title</th>
				<th scope="col">Date Created</th>
				<th scope="col">Published</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$blogPosts item="post"}
			<tr>
				<td>{$post->title}</td>
				<td>{$post->datetime_created|date_format:"%D %I:%M %p"}</td>
				<td>{$post->date_published|date_format:"%D"}</td>
				<td>
					<a href="{$SITE_URL}?page=blog&amp;action=edit&amp;id={$post->public_id}" class="btn btn-secondary"><i class="fas fa-edit"></i></a>
				</td>
			</tr>
			
		</tbody>
		{/foreach}
	</table>
</div>




