<div class="container">
	<h1>Edit Menu Item</h1>

	<form name="edit" id="edit" method="post" action="{$SITE_URL}">
		<input type="hidden" name="page" value="menu" />
		<input type="hidden" name="action" value="edit_corporate_menu" />
		<input type="hidden" name="path" value="{$current_url}" />	
		<input type="hidden" name="type" value="{$menuType}" />
		<input type="hidden" name="id" id="public-id" value="{$menuItem->public_id}" />
		<input type="hidden" name="location" value="{$location->public_id}">
		<input type="hidden" name="menu" value="{$menu->public_id}" />
		<input type="hidden" name="page_count" value="{$page_count}" />
		
		
		<div class="form-group">
			<label for="menu"><strong>Menu:</strong></label>
			<textarea id="summernote" name="menu_content" id="menu-content" class="form-control">{foreach $menuItem->content as $m}{$m}{/foreach}</textarea>
		</div>

		<div class="form-group">
			<strong>Make the change to:</strong>
			<div class="form-check">
				<input type="radio" id="corp-menu" class="form-check-input" name="edit_type" value="corp_menu" checked>
				<label class="form-check-label" for="corp-menu">Corporate Menu</label>
			</div>
			<div class="form-check">
				<input type="radio" id="ind-location" class="form-check-input" name="edit_type" id="individualLocations" value="select_locations">
				<label class="form-check-label" for="ind-location">Individual Locations </label>
			</div>

			<div class="form-check facilities-list ml-5">
				{foreach from=$allLocations item=facility key=key name=facilities}
					<input type="checkbox" class="form-check-input" name="facility{$key}" value="{$facility->public_id}" {if $location->public_id == $facility->public_id} checked="checked"{/if}>{$facility->name}
				{/foreach}
			</div>
		</div>

		<div class="row text-right">
			<div class="col-12">
				<button type="button" class="btn btn-secondary" onClick="history.go(-1)">Cancel</button>
				{if $menuChange}
					<a href="{$SITE_URL}/?module=Dietary&amp;page=menu&amp;action=delete_item&amp;type={$menuType}&amp;id={$menuItem->public_id}&amp;menu={$menu->public_id}&amp;page_count={$page_count}" class="btn btn-primary">Reset to Original Item</a>
				{/if}
				<button type="submit" class="btn btn-primary">Change Menu</button>
			</div>
		</div>
	</form>
</div>