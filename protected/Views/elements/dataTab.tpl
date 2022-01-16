{if $auth->hasPermission('edit_data')}
<li class="nav-item" aria-labelledby="dataDropdown">
	<a href="#dataSection" class="nav-link dropdown-toggle" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="dataSection"><i class="fas fa-database"></i> Data</a>
	<div class="collapse" id="dataSection" data-parent="#sidebar-nav">
		<ul class="nav flex-column ml-4">			
			{if $this->getModule() == "Admissions" || $this->getModule() == "HomeHealth"}
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}/?page=case_managers&amp;action=manage">Care Coordinators</a>
			</li>
			{if $auth->hasPermission("manage_home_health_clinicians")}
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}/?module=HomeHealth&amp;page=clinicians&amp;action=manage">Home Health Clinicians</a>
			</li>
			{/if}
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}/?page=healthcare_facilities&amp;action=manage">Healthcare Facilities</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{$SITE_URL}/?page=physicians&amp;action=manage">Physicians</a>
			</li>
			{/if}
			{if $auth->hasPermission("manage_users")}
			<li id="users" class="nav-item">
				<a class="nav-link" href="{$SITE_URL}/?page=users&amp;action=manage">Users</a>
			</li>
			{/if}
		</ul>
	</div>
</li>
{/if}

