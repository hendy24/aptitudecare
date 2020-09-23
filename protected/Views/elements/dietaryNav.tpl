<li><a href="{$SITE_URL}?module=Dietary&amp;page=dietary&amp;action=index&amp;location={$location->public_id}">Home</a></li>
{if $auth->hasPermission('manage_menu')}
<li>Info
	<ul>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=current&amp;location={$location->public_id}">Current Menu</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=menu_start_date&amp;location={$location->public_id}">Menu Start Date</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=public_page_items&amp;location={$location->public_id}">Public Page Items</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=beverages&amp;location={$location->public_id}">Beverage List</a></li>
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
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=allergies&amp;location={$location->public_id}">Allergies/Dislikes</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=beverages&amp;location={$location->public_id}">Beverages</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=diet_census&amp;location={$location->public_id}">Diet Census</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=isolation&amp;location={$location->public_id}">Isolation Census</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=special_requests&amp;location={$location->public_id}">Special Requests</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=snack_report&amp;location={$location->public_id}">Snack Report</a></li>
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=menu&amp;action=print_menu&amp;location={$location->public_id}&amp;pdf2=true" target="_blank">Weekly Menu</a></li>
		{if $auth->hasPermission("create_menu")}
			<li class="permission-access"><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=menu_changes">Menu Changes</a></li>
		{/if}

	</ul>
</li>
{if $auth->hasPermission('view_photos')}
<li>Photos
	<ul>
		{if $auth->hasPermission('manage_dietary_photos')}
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=photos&amp;action=upload_photos&amp;location={$location->public_id}">Upload</a></li>
		{/if}
		<li><a href="{$SITE_URL}?module={$this->getModule()}&amp;page=photos&amp;action=photos">View Photos</a></li>
	</ul>
</li>
{/if}
