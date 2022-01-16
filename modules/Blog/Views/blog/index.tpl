<div class="container">
	<div class="row">
		<div class="col-lg-2">{$this->loadElement("module")}</div>
		<div class="col-lg-8">
			<h1 class="text-center">Manage Posts</h1>
		</div>
		<div class="col-lg-2">
			<a href="{$SITE_URL}/?module=Blog&amp;page=blog&amp;action=edit" class="button btn-primary text-white float-right">New Post</a>
		</div>
	</div>

	<div class="row manage-header">
		<div class="col-lg-5 col-md-6 col-sm-12">
			Title
		</div>
		<div class="col-lg-3 col-md-6 col-sm-4">
			Created
		</div>
		<div class="col-lg-3 col-md-6 col-sm-4">
			Published
		</div>
		<div class="col-lg-1 col-md-6 col-sm-2"></div>
	</div>
	{foreach from=$blogPosts item="post"}
		<div class="row manage-body">
			<div class="col-lg-5 col-md-6 col-sm-12">{$post->title}</div>
			<div class="col-lg-3 col-md-6 col-sm-4">{$post->datetime_created|date_format:"%D"}</div>
			<div class="col-lg-3 col-md-6 col-sm-4">{$post->date_published|date_format:"%D"}</div>
			<div class="col-lg-1 col-md-6 col-sm-2">
				<a href="{$SITE_URL}?module=Blog&amp;page=blog&amp;action=edit&amp;id={$post->public_id}" class="btn btn-secondary float-lg-right mt-1"><i class="fas fa-edit"></i></a>
			</div>
		</div>
	{/foreach}

	{if isset ($pagination)}
	<div class="row text-center">
		<div class="col-12">
			{$url = "{$SITE_URL}"}
			{include file="elements/pagination.tpl"}
		</div>
	</div>			
	{/if}


</div>




