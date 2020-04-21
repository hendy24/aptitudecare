{if $post->filename}
<div class="container-fluid">	
	<img src="{$SITE_URL}/uploads/{$post->filename}" alt="" class="img-fluid">
</div>
{/if}

<div class="container">
	<h1 class="text-center">{$post->title}</h1>
	<p>Published: {$post->date_published|date_format:"%A, %B %e, %Y"}</p>
	{$post->content nofilter}
	<button class="btn btn-secondary float-right" onclick="history.go(-1)">Go Back</button>
</div>
