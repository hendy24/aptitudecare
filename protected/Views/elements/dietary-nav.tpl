<nav>
	<ul>
		<li><a href="{$SITE_URL}?module=Dietary">Home</a></li>
		<li>Menu
			<ul>
				<li><a href="{$SITE_URL}?module=Dietary&amp;page=dietary&amp;action=current">Current Menu</a></li>
				<li><a href="{$SITE_URL}?module=Dietary&amp;page=dietary&amp;action=facility_menus">Facility Menus</a></li>
				<li><a href="{$SITE_URL}?module=Dietary&amp;page=dietary&amp;action=corporate_menus">Corporate Menus</a></li>
				<li><a href="{$SITE_URL}?module=Dietary&amp;page=dietary&amp;action=alt_menu_items">Alternate Menu Items</a></li>
				<li><a href="{$SITE_URL}?module=Dietary&amp;page=dietary&amp;action=menu_changes">Menu Changes</a></li>
				<li><a href="{$SITE_URL}?module=Dietary&amp;page=dietary&amp;action=meal_times">Meal Times</a></li>
				<li><a href="{$SITE_URL}?module=Dietary&amp;page=dietary&amp;action=welcome_info">Welcome Info</a></li>
				<li><a href="{$SITE_URL}?module=Dietary&amp;page=dietary&amp;action=menu_start_date">Menu Start Date</a></li>
				<li><a href="{$SITE_URL}?module=Dietary&amp;page=dietary&amp;action=preview_public_page">Preview Public Page</a></li>
			</ul>
		</li>
		<li>Other
			<ul>
				<li><a href="{$SITE_URL}/?module=Other&amp;page=activities&amp;action=index">Activities</a></li>	
				<li><a href="{$SITE_URL}/?module=Other&amp;page=additional&amp;action=index">Add Page</a></li>			
			</ul>
		</li>
		<li>Data
			<ul>
				{if $auth->is_admin()}
				<li><a href="{$SITE_URL}/?page=users&amp;action=manage">Users</a></li>
				{/if}
			</ul>
		</li>
	</ul>
</nav>
