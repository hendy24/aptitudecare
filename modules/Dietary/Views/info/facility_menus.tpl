<script>
	$(document).ready(function() {
		$("#location").change(function() {
			var location = $("#location option:selected").val();
			window.location.href = SITE_URL + "/?module=Dietary&page=info&action=facility_menus&location=" + location;
		});
		$("#menus").change(function() {
			var menu = $("#menus option:selected").val();
			window.location.href = SITE_URL + "/?module=Dietary&page=info&action=facility_menus&location=" + {$location->public_id} + "menu=" + menu;
		});
	});
</script>

<div id="page-header">
	<div id="action-left">
		{$this->loadElement("module")}
	</div>
	<div id="center-title">
		{$this->loadElement("selectLocation")}
	</div>
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
						<li>{$menu|strip_tags:true}</li>
					{/foreach}
					</ul>
				</div>
				<div class="menu-edit-button">
					<a href="{$SITE_URL}/?module=Dietary&amp;page=menu&amp;action=edit_corporate_menu&amp;location={$location->public_id}&amp;type={$menuItem->type}&amp;id={$menuItem->public_id}&amp;menu={$currentMenu->menu_id}&amp;page_count={$pagination->current_page}" class="button">Edit</a>
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
		{$var = "{$SITE_URL}?module=Dietary&amp;page=info&amp;action=facility_menus&amp;menu={$currentMenu->menu_id}"}
		{include file="elements/pagination.tpl"}
	{/if}
