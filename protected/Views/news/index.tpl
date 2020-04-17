<div class="container-fluid">
	<img src="{$IMAGES}/denali-sunset.jpeg" alt="" class="img-fluid">
</div>
<div class="container news">

	<h1 class="text-center">Recent News &amp; Announcements</h1>

	<div class="row">
		<div class="col-sm-8">
			{foreach from=$posts item='post'}
			<div class="row news-title-row">
				<div class="col-9">
					<h2><a class="text-dark" href="{$SITE_URL}/news/post/{$post->public_id}">{$post->title}</a></h2>
				</div>
				<div class="col-3">
					<p class="text-right">{$post->date_published|date_format:"%D"}</p>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					{$post->content|truncate:300 nofilter}
					<a class="text-dark" href="{$SITE_URL}/news/post/{$post->public_id}"> Read more...</a>
				</div>
			</div>
			{/foreach}	
		</div>
		<div class="col-sm-1"></div>
		<div class="col-sm-3 recent-news">
			Recent News
			<ul class="nav">			
				{foreach from=$posts item="post"}
				<li class="nav-item">
					<a class="nav-link" href="{$SITE_URL}/news/post/{$post->public_id}">{$post->title}</a>
				</li>
				{/foreach}
			</ul>
		</div>
	</div>

	
</div>
