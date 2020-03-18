<section class="new-section">
	<div class="container">
		<h1 class="text-center">Recent Posts</h1>
		{foreach from=$posts item='post'}
		<section class="new-section">
			<div class="row">
				<div class="col-8">
					<h2><a class="text-dark" href="{$SITE_URL}/blog/post/{$post->public_id}">{$post->title}</a></h2>
				</div>
				<div class="col-4">
					<p class="text-right">{$post->date_published|date_format:"%A, %B %e, %Y"}</p>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					{$post->content|truncate:2500 nofilter}
					<a class="text-dark" href="{$SITE_URL}/blog/post/{$post->public_id}"> Read more...</a>
				</div>
			</div>
		</section>	
		<section class="new-section">
			<hr width="90%">
		</section>
		{/foreach}
	</div>
</section>