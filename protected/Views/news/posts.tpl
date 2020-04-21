<div class="container-fluid">
	<img src="{$IMAGES}/denali-sunset.jpeg" alt="" class="img-fluid">
</div>
<div class="container news">
	<h1 class="text-center">Posts about {$keyword}</h1>

	<div class="row">
		<div class="col-sm-7">
			{if !empty ($posts)}
			{foreach from=$posts item='post'}
			{if $post->filename !== null}
			<div class="row">
				<div class="col-12">
					<img src="{$SITE_URL}/uploads/{$post->filename}" alt="" class="img-fluid">
				</div>
			</div>
			<div class="row">
			{else}
			<div class="row news-title-row">
			{/if}
				<div class="col-9 mt-5">
					<h2><a class="text-dark" href="{$SITE_URL}/news/post/{$post->public_id}">{$post->title}</a></h2>
				</div>
				<div class="col-3 mt-5">
					<p class="text-right">{$post->date_published|date_format:"%D"}</p>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					{$post->content|truncate:200 nofilter} <br><br>
					<a class="text-dark float-right" href="{$SITE_URL}/news/post/{$post->public_id}"> Read more...</a>
				</div>
			</div>
			<hr width="100%">
			{/foreach}
			{else}
			<p>We are sorry!</p>
			<p>There are no results for this search term.</p>
			<p>Please try something different.</p>
			{/if}
		</div>
		<div class="col-sm-1"></div>
		
		{$this->loadElement('blogSearch')}

	</div>
</div>
