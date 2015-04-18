<script>
	$(document).ready(function() {
		$("#menu").change(function() {
			window.location.href = SITE_URL + "/?module=Dietary&page=info&action=corporate_menus&menu=" + $("option:selected", this).val();
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
	<div id="action-right">
		<select name="menu" id="menu">
			<option value="">Select a menu...</option>
			{foreach from=$menus item=menu key=key name=menu}
				<option value="{$menu->public_id}" {if $selectedMenu && $selectedMenu->id == $menu->id} selected{/if}>{$menu->name}</option>
			{/foreach}
		</select>	
	</div>
</div>



<div id="page-header">
	<div id="action-left">
		&nbsp;
	</div>
	<div id="center-title">
		<h1>{$selectedMenu->name} Menu</h1>
	</div>
	<div id="action-right">
		
	</div>
</div>




<div id="menu">
	<table id="menu-table">
		{foreach from=$menuItems item="menuItem" name="menuItems"}	

		{if $smarty.foreach.menuItems.first}
		<tr>
			<th colspan="3" class="text-center">Day {$menuItem->day}</th>
		</tr>
		<tr>
		{/if}
					
			<td class="menu-content">
				<div class="menu">
					<div class="menu-info {if $menuItem->type == "MenuChange"} background-grey{/if}">
						<ul>
						{foreach $menuItem->content as $menu}
							<li>{$menu|unescape:'html'}</li>
						{/foreach}
						</ul>
					</div>
					<div class="menu-edit-button">
						<a href="{$SITE_URL}/?module=Dietary&amp;page=menu&amp;action=edit&amp;type={$menuItem->type}&amp;id={$menuItem->public_id}" class="button">Edit</a>
					</div>
				</div>
			</td>

		{if $smarty.foreach.menuItems.iteration is div by 3}
		</tr>
		{if !$smarty.foreach.menuItems.last}
		<tr>
			<th colspan="3" class="text-center">Day {$menuItem->day + 1}</th>
		</tr>
		<tr>
		{/if}
		{/if}
	{/foreach}
	</table>

	{if isset ($pagination)}
		{$url = "{$SITE_URL}?module=Dietary&page=dietary&action=corporate_menus&menu={$selectedMenu->id}"}
		{include file="elements/pagination.tpl"}	
	{/if}

</div>