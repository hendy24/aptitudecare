<div class="container">
	
	<h1>Edit the Menu for {$date|date_format:"%A, %B %e, %Y"}</h1>

	<form name="edit" id="edit" method="post" action="{$SITE_URL}">
		<input type="hidden" name="module" value="Dietary">
		<input type="hidden" name="page" value="menu" />
		<input type="hidden" name="action" value="submit_edit" />
		<input type="hidden" name="path" value="{$current_url}" />	
		<input type="hidden" name="location" id="location" value="{$location->public_id}" />
		<input type="hidden" name="menu_type" value="{$menuType}" />
		<input type="hidden" name="date" value="{$date}" />
		<input type="hidden" name="public_id" id="public-id" value="{$menuItem->public_id}" />

		
		<div class="form-group">
			<label for="menu-content">Menu</label>
			<textarea name="menu_content" id="summernote" cols="30" rows="10" class="form-control">{foreach $menuItem->content as $menu}{$menu}{/foreach}</textarea>
		</div>
		
		{if $corporateEdit}
		<div class="custom-control custom-radio">
			
			<label for="">Make the change to:</label>
			<input type="radio" name="edit_type" value="corp_menu" class="custom-control-inline">Corporate Menu</td>
			<input type="radio" name="edit_type" id="individualLocations" value="select_locations" class="custom-control-inline">Individual Locations <span class="text-10">(recurring only for the selected locations)</span></td>
		</div>
	
		<div class="custom-control custom-checkbox">
			{foreach from=$allLocations item=facility key=key name=facilities}
				<input type="checkbox" class="custom-control-input" name="facility{$key}" value="{$facility->public_id}"> {$facility->name}
			{/foreach}
		</div>
		{else}
		<div class="form-group">
			<label for="reason">Reason for menu change:</label>
			<textarea name="reason" id="reason" cols="80" rows="10" class="form-control">{if $menuType == "MenuMod"}{$menuItem->reason}{/if}</textarea>
		</div>
		{/if}

		<button type="button" class="btn btn-secondary" value="Cancel" onclick="history.go(-1)">Cancel</button>
		{if $menuMod}
			<button type="submit" class="btn btn-primary" name="reset">Reset to Original</button>
		{/if}
		<button type="submit" class="btn btn-primary text-right">Change Menu</button>
	</form>
</div>
