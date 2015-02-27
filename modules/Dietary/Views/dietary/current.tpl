<script>
	$(document).ready(function() {
		$("#location").change(function() {
			var location = $("#location option:selected").val();
			window.location.href = SITE_URL + "/?module=Dietary&page=dietary&action=current&location=" + location;
		});
	});
</script>

{include file="$VIEWS/elements/{$searchBar}-search.tpl"}
<div id="action-right">
	<a href="" class="button">Print Menu</a>
</div>
<div id="dietary-date-header">
	<div class="dietary-date-header-img-left">
		<a href="{$SITE_URL}/?module=Dietary&amp;page=dietary&amp;action=current&amp;location={$location->public_id}&amp;weekSeed={$retreatWeekSeed}"><img class="left" src="{$FRAMEWORK_IMAGES}/icons/prev-icon.png" alt="previous week" /></a>
	</div>
	<div class="dietary-date-header-img-right">
		<a href="{$SITE_URL}/?module=Dietary&amp;page=dietary&amp;action=current&amp;location={$location->public_id}&amp;weekSeed={$advanceWeekSeed}"><img class="left" src="{$FRAMEWORK_IMAGES}/icons/next-icon.png" alt="next week" /></a>	
	</div>	
	<div class="dietary-date-header-text">
		<h1 line-space">Week Beginning {$startDate|date_format: "%A, %B %e, %G"}</h1>
		{if $today}
			<a href="{$SITE_URL}/?module=Dietary&amp;page=dietary&amp;action=current&amp;location={$location->public_id}&amp;weekSeed={$today}" class="button">Today</a>
		{/if}
	</div>
	
</div>

<h2><strong>Week {$menuWeek}</strong> of the <strong>{$menu->name}</strong> Menu</h2>

<table id="menu-table">
	<tr>
		<th colspan="3" class="text-center">{$startDate|date_format:"%A, %B %e, %Y"}</th>
	</tr>
	<tr>
	{foreach from=$menuItems item="menuItem" name="menuItems"}	
		<td class="menu-content">
			<div class="menu">
				<div class="menu-info {if $menuItem->type == "MenuMod"}background-blue{elseif $menuItem->type == "MenuChange"} background-grey{/if}">
					<ul>
					{foreach $menuItem->content as $menu}
						<li>{$menu|unescape:'html'}</li>
					{/foreach}
					</ul>
				</div>
				<div class="menu-edit-button">
					<a href="{$SITE_URL}/?module=Dietary&amp;page=menu&amp;action=edit&amp;location={$location->public_id}&amp;type={$menuItem->type}&amp;id={$menuItem->public_id}&amp;date={"$startDate + $count day"|date_format:"%Y-%m-%d"}" class="button">Edit</a>
				</div>
			</div>
			
		</td>
	{if $smarty.foreach.menuItems.iteration is div by 3}
	{$count++|truncate:0:""}
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>

		{if !$smarty.foreach.menuItems.last}
		<tr>
			<th colspan="3" class="text-center">{"$startDate + $count day"|date_format:"%A, %B %e, %Y"}</th>
		</tr>
		<tr>
		{else}
		</tr>
		{/if}
	{/if}
			
	{/foreach}
</table>

<div id="legend">
	<h2>Menu Color Legend</h2>
	<div class="legend-item">
		<div class="legend-box background-grey"></div>
		<div class="legend-desc">Menu Items with this background color have been edited at a corporate level. These changes are <strong>permanent</strong> and will display each time the menu starts over.</div> 
	</div>
	<div class="legend-item">
		<div class="legend-box background-blue"></div>
		<div class="legend-desc">Menu Items with this background color have been edited by the facility. These changes are a <strong>one-time</strong> change and will not recur when the menu starts over.</div> 
	</div>
	
</div>
<div class="clear"></div>

