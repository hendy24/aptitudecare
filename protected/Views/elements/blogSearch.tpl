	<div class="col-sm-4 recent-news">
		<form action="{$SITE_URL}" class="form-inline my-lg-0">
			<input type="hidden" name="page" value="news">
			<input type="hidden" name="action" value="posts">
			<input type="hidden" name="type" value="tag">

			<input type="search" name="keyword" class="form-control mr-2" placeholder="Search" aria-label="Search">
			<button class="btn btn-primary my-sm-0" type="submit">Search</button>
		</form>
		
		<h3 class="text-14">News Categories</h3>
		<ul class="nav">			
			{foreach from=$categories item="category"}
			<li class="nav-item mr-5">
				<a class="nav-link" href="{$SITE_URL}/news/posts/{$category->name}">{$category->name} ({$category->post_count})</a>
			</li>
			{/foreach}
		</ul>
	</div>
