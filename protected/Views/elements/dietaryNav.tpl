<li><a href="{$SITE_URL}?module=Dietary">Home</a></li>
{if $auth->hasPermission('manage_menu')}
<li>Info
	<ul>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=current">Current Menu</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=menu_start_date">Menu Start Date</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=public_page_items">Public Page Items</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=public&amp;location={$location->public_id}" target="_blank">Preview Public Page</a></li>
		{if $auth->hasPermission("create_menu")}
			<li class="permission-access"><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=create">Create Menu</a></li>
			<li class="permission-access"><a href="{$SITE_URL}/?module={$this->getModule()}&amp;page=info&amp;action=manage">Manage Menus</a></li>
			<li class="permission-access"><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=facility_menus">Facility Menus</a></li>
			<li class="permission-access"><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=corporate_menus">Corporate Menus</a></li>
		{/if}

	</ul>
</li>
{/if}
<li>Reports
	<ul>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=adaptive_equipment&amp;location={$location->public_id}">Adaptive Equipment</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=allergies&amp;location={$location->public_id}">Allergies</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=beverages&amp;location={$location->public_id}">Beverages</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=diet_census&amp;location={$location->public_id}">Diet Census</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=snack_report&amp;location={$location->public_id}">Snack Report</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=menu&amp;action=print_menu&amp;location={$location->public_id}&amp;pdf=true" target="_blank">Weekly Menu</a></li>
		{if $auth->hasPermission("create_menu")}
			<li class="permission-access"><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=menu_changes">Menu Changes</a></li>
		{/if}

	</ul>
</li>
{if $auth->hasPermission('view_photos')}
<li>Photos
	<ul>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=photos&amp;action=upload_photos&amp;location={$location->public_id}">Upload</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=photos&amp;action=view_photos">View</a></li>
		{if $auth->hasPermission('manage_dietary_photos')}
		<li class="permission-access"><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=photos&amp;action=approve_photos">Approve Photos</a></li>
		<li class="permission-access"><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=photos&amp;action=manage_photos">Manage Photos</a></li>
		{/if}
	</ul>
</li>
{/if}


