<div class="container">
	
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<h1>{$selectedMenu->name} Menu</h1>
		</div>
		<div class="col-md-2">
			<select name="menu" id="menu" class="form-control">
				<option value="">Select a menu...</option>
				{foreach from=$menus item=menu key=key name=menu}
					<option value="{$menu->public_id}" {if $selectedMenu && $selectedMenu->id == $menu->id} selected{/if}>{$menu->name}</option>
				{/foreach}
			</select>	
		</div>
	</div>



	<table class="table">
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
					<div class="text-right">
						<a href="{$SITE_URL}/?module=Dietary&amp;page=menu&amp;action=edit_corporate_menu&amp;menu={$selectedMenu->public_id}&amp;type={$menuItem->type}&amp;id={$menuItem->public_id}&amp;page_count={$pagination->current_page}" class="btn-table">Edit</a>
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
	<div class="row text-center">
		<div class="col-12">
			{$url = "{$SITE_URL}?module=Dietary&page=dietary&action=corporate_menus&menu={$selectedMenu->id}"}
			{include file="elements/pagination.tpl"}
		</div>
	</div>			
	{/if}


</div>
