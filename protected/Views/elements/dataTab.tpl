{if $auth->hasPermission('edit_data')}
<li class="nav-item dropdown" aria-labelledby="dataDropdown">
	<a href="#" class="nav-link dropdown-toggle" id="dataDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Data</a>
	<div class="dropdown-menu" aria-labelledby="reportsDropdown">
		{if $this->getModule() == "Admissions" || $this->getModule() == "HomeHealth"}
		<a class="dropdown-item" href="{$SITE_URL}/?page=case_managers&amp;action=manage">Case Managers</a>
		{if $auth->hasPermission("manage_home_health_clinicians")}
		<a class="dropdown-item" href="{$SITE_URL}/?module=HomeHealth&amp;page=clinicians&amp;action=manage">Home Health Clinicians</a>
		{/if}
		<a class="dropdown-item" href="{$SITE_URL}/?page=healthcare_facilities&amp;action=manage">Healthcare Facilities</a>
		<a class="dropdown-item" href="{$SITE_URL}/?page=physicians&amp;action=manage">Physicians</a>
		{/if}
		{if $auth->hasPermission("manage_users")}
		<a class="dropdown-item" href="{$SITE_URL}/?page=users&amp;action=manage">Users</a>
		{/if}
	</div>
</li>
{/if}

