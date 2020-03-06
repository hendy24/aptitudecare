<div class="container text-center">
	<h1 class="info-title">Current Menu</h1>
	<div class="row">
		<div class="col-lg">
			<p class="info-date">{"$startDate"|date_format:"%A, %B %e, %Y"}</p>
		</div>
	</div>
	<div class="row">			
	{foreach from=$menuItems item="menuItem" name="menuItems"}
			<div class="col-md info-item">
				{foreach $menuItem->content as $menu}
					{if !empty($menu)}<p>{$menu|strip_tags:true}</p>{/if}
				{/foreach}		
			</div>

		{if $smarty.foreach.menuItems.iteration is div by 3}
		{$count++|truncate:0:""}
		</div>
			{if !$smarty.foreach.menuItems.last}
			<div class="row">
				<div class="col-lg">
					<p class="info-date">{"$startDate + $count day"|date_format:"%A, %B %e, %Y"}</p>	
				</div>
			</div>
			<div class="row">
			{/if}
		{/if}
	{/foreach}	
	<div class="row new-section">
		<div class="col-lg">
			<h2>Always Available Menu</h2>
			<p>{$alternates->content}</p>
		</div>
	</div>
	<div class="row new-section">
		<div class="col-lg">
			<p class="font-weight-bold">Guests are always welcome! Guest meals for the daily special are $10.</p>
		</div>
	</div>
</div>
