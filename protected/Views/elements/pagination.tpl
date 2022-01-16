<div class="row m-4"></div>
<nav aria-label="">
	{if ceil($pagination->num_pages > 1)}
	<ul class="pagination justify-content-center">
		{for $i=max($pagination->current_page-5, 1); $i<=max(1, min(ceil($pagination->num_pages),$pagination->current_page+5)); $i++}
			{if $i == $pagination->current_page}
				<li class="page-item active">
					{if strstr ($current_url, '?')}
					<a href="{$current_url}&amp;page_count={$i}" class="page-link">{$i}<span class="sr-only">(current)</span></a>
					{else}
					<a href="{$current_url}/?page_count={$i}" class="page-link">{$i}<span class="sr-only">(current)</span></a>
					{/if}
				</li>
			{else}
				<li class="page-item">
					{if strstr ($current_url, '?')}
					<a href="{$current_url}&amp;page_count={$i}" class="page-link">{$i}</a>
					{else}
					<a href="{$current_url}/?page_count={$i}" class="page-link">{$i}</a>
					{/if}
				</li>
			{/if}
		{/for}
		<!-- {if $pagination->current_page != $pagination->num_pages}
			<a href="{$current_url}&amp;page_count={$pagination->current_page + 1}">Next &nbsp;&raquo;</a>
		{/if} -->
	</ul>
	{/if}
</nav>


<div id="page-count">
	Page {$pagination->current_page} of {ceil($pagination->num_pages)}
</div>