<li class="nav-item active">
	<a class="nav-link" href="{$SITE_URL}?module=Dietary&amp;page=dietary&amp;action=index&amp;location={$location->public_id}">Home</a>
</li>
{if $auth->hasPermission('manage_menu')}
<li class="nav-item dropdown">
	<a href="#" class="nav-link dropdown-toggle" id="infoDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Info</a>
	<div class="dropdown-menu" aria-labelledby="infoDropdown">
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=current&amp;location={$location->public_id}">Current Menu</a>
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=menu_start_date&amp;location={$location->public_id}">Menu Start Date</a>
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=public_page_items&amp;location={$location->public_id}">Public Page Items</a>
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=beverages&amp;location={$location->public_id}">Beverage List</a>
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=public&amp;location={$location->public_id}" target="_blank">Preview Public Page</a>
		{if $auth->hasPermission("create_menu")}
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=create">Create Menu</a>
		<a class="dropdown-item" href="{$SITE_URL}/?module={$this->getModule()}&amp;page=info&amp;action=manage">Manage Menus</a>
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=facility_menus">Facility Menus</a>
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=corporate_menus">Corporate Menus</a>
		{/if}
	</div>
</li>
{/if}
<li class="nav-item dropdown">
	<a href="#" class="nav-link dropdown-toggle" id="reportsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reports</a>
	<div class="dropdown-menu" aria-labelledby="reportsDropdown">
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=adaptive_equipment&amp;location={$location->public_id}">Adaptive Equipment</a>
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=allergies&amp;location={$location->public_id}">Allergies</a>
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=beverages&amp;location={$location->public_id}">Beverages</a>
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=diet_census&amp;location={$location->public_id}">Diet Census</a>
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=snack_report&amp;location={$location->public_id}">Snack Report</a>
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=menu&amp;action=print_menu&amp;location={$location->public_id}&amp;pdf=true" target="_blank">Weekly Menu</a>
		{if $auth->hasPermission("create_menu")}
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=menu_changes">Menu Changes</a>
		{/if}
	</div>
</li>
{if $auth->hasPermission('view_photos')}
<li class="nav-item dropdown">
	<a href="#" class="nav-link dropdown-toggle" id="photosDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Photos</a>
	<div class="dropdown-menu" arialabelledby="photosDropdown">
		{if $auth->hasPermission('manage_dietary_photos')}
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=photos&amp;action=upload_photos&amp;location={$location->public_id}">Upload</a>
		{/if}
		<a class="dropdown-item" href="{$SITE_URL}?module={$this->getModule()}&amp;page=photos&amp;action=photos">View Photos</a>
	</div>
</li>
{/if}

