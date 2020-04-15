<li class="nav-item">
	<a href="{$SITE_URL}?module=Dietary&amp;page=dietary&amp;action=index&amp;location={$location->public_id}" class="nav-link">Diet Info</a>
</li>
{if $auth->hasPermission('manage_menu')}
<li class="nav-item ml-4">
	<a href="#infoSection" class="nav-link dropdown-toggle" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="infoSection">Menu Info</a>
	<div class="collapse" id="infoSection" data-parent="#sidebar-nav">
		<ul class="nav flex-column ml-4">
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=current&amp;location={$location->public_id}">Current Menu</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=menu_start_date&amp;location={$location->public_id}">Menu Start Date</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=public_page_items&amp;location={$location->public_id}">Public Page Items</a>
			</li>
			<!-- <li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=beverages&amp;location={$location->public_id}">Beverage List</a>
			</li> -->
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?page=public-display&amp;location={$location->public_id}" target="_blank">Preview Public Page</a>
			</li>


			{if $auth->hasPermission("create_menu")}
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=create">Create Menu</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}/?module={$this->getModule()}&amp;page=info&amp;action=manage">Manage Menus</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=facility_menus">Facility Menus</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=corporate_menus">Corporate Menus</a>
			</li>
			{/if}
		</ul>
	</div>
</li>
{/if}


<li class="nav-item ml-4">
	<a href="#reportsSection" class="nav-link dropdown-toggle" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="reportsSection">Reports</a>
	<div class="collapse" id="reportsSection" data-parent="#sidebar-nav">
		<ul class="nav flex-column ml-4">
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=adaptive_equipment&amp;location={$location->public_id}">Adaptive Equipment</a>
			</li>
			<!-- <li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=allergies&amp;location={$location->public_id}">Allergies</a>
			</li> -->
			<!-- <li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=beverages&amp;location={$location->public_id}">Beverages</a>
			</li> -->
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=diet_census&amp;location={$location->public_id}">Diet Census</a>
			</li>
			<!-- <li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=snack_report&amp;location={$location->public_id}">Snack Report</a>
			</li> -->
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=menu&amp;action=print_menu&amp;location={$location->public_id}&amp;pdf=true" target="_blank">Weekly Menu</a>
			</li>
			{if $auth->hasPermission("create_menu")}
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=reports&amp;action=menu_changes">Menu Changes</a>
			</li>
			{/if}
		</ul>
	</div>
</li>
<!-- {if $auth->hasPermission('view_photos')}
<li class="nav-item ml-4">
	<a href="#photosSection" class="nav-link dropdown-toggle" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="photosSection">Photos</a>
	<div class="collapse" id="photosSection" data-parent="#sidebar-nav">
		<ul class="nav flex-column ml-4">
			{if $auth->hasPermission('manage_dietary_photos')}
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=photos&amp;action=upload_photos&amp;location={$location->public_id}">Upload</a>
			</li>
			{/if}
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}?module={$this->getModule()}&amp;page=photos&amp;action=photos">View Photos</a>
			</li>
		</ul>
	</div>
</li>
{/if}
 -->
