<script>
	$(document).ready(function() {
		$("#location").change(function() {
			var location = $("#location option:selected").val();
			window.location.href = SITE_URL + "/?module=Dietary&page=dietary&action=facility_menus&location=" + location;
		});
		$("#menus").change(function() {
			var menu = $("#menus option:selected").val();
			window.location.href = SITE_URL + "/?module=Dietary&page=dietary&action=facility_menus&location=" + {$location->public_id} + "menu=" + menu;
		});
	});
</script>

{include file="$VIEWS/elements/{$searchBar}-search.tpl"}

<h1>{$currentMenu->name} Facility Menu</h1>

<div id="available-menus" class="select-dropdown right">
	<select name="menus" id="menus">
		{foreach $availableMenus as $menus}	
			<option value="{$menus->id}" {if $menus->id == $selectedMenu->id} selected{/if}>{$menus->name}</option>
		{/foreach}
	</select>
</div>

<h2>Week {$menuWeek} of the {$menu->name} Menu</h2>
<br>
<table id="menu-table">
	<tr>
	{foreach from=$menuItems item="menuItem" name="menuItems"}	
		{if $smarty.foreach.menuItems.first}
		<tr>
			<th colspan="3" class="text-center">Day {$menuItem->day}</th>
		</tr>
		{/if}
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
					<a href="{$SITE_URL}/?module=Dietary&amp;page=menu&amp;action=edit&amp;location={$location->public_id}&amp;type={$menuItem->type}&amp;id={$menuItem->public_id}" class="button">Edit</a>
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
			<th colspan="3" class="text-center">Day {$menuItem->day + 1}</th>
		</tr>
		<tr>
		{else}
		</tr>
		{/if}
	{/if}
			
	{/foreach}
</table>

	{if isset ($pagination)}
		{$url = "{$SITE_URL}?module=Dietary&page=dietary&action=facility_menus&menu={$currentMenu->id}"}
		{include file="elements/pagination.tpl"}	
	{/if}


