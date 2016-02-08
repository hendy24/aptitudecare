<li><a href="" onClick="return true">Data</a>
	<ul>
		{if $this->getModule() == "Admissions" || $this->getModule() == "HomeHealth"}
			<li>
				<a href="{$SITE_URL}/?page=case_managers&amp;action=manage">Case Managers</a>
			</li>
			{if $auth->hasPermission("manage_home_health_clinicians")}
			<li><a href="{$SITE_URL}/?module=HomeHealth&amp;page=clinicians&amp;action=manage">Home Health Clinicians</a></li>
			{/if}
			<li><a href="{$SITE_URL}/?page=healthcare_facilities&amp;action=manage">Healthcare Facilities</a></li>
			<li><a href="{$SITE_URL}/?page=physicians&amp;action=manage">Physicians</a></li>
		{/if}
		{if $auth->hasPermission("manage_users")}
			<li><a href="{$SITE_URL}/?page=users&amp;action=manage">Users</a></li>
		{/if}
		{if $auth->isLoggedIn()}
			<li><a href="{$SITE_URL}/?page=users&amp;action=my_info">My Account</a></li>
		{/if}
	</ul>
</li>
