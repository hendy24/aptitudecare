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
