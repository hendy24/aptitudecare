<div class="container blog">
	<h1 class="text-center">Recent Posts</h1>
	{foreach from=$posts item='post'}
	<div class="row">
		<div class="col-8">
			<h2><a class="text-dark" href="{$SITE_URL}/blog/post/{$post->public_id}">{$post->title}</a></h2>
		</div>
		<div class="col-4">
			<p class="text-right">{$post->date_published|date_format:"%D"}</p>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			{$post->content|truncate:600 nofilter}
			<a class="text-dark" href="{$SITE_URL}/blog/post/{$post->public_id}"> Read more...</a>
		</div>
	</div>
	<hr width="100%">
	{/foreach}
</div>
