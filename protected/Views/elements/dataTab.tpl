<li>Data
	<ul>
		{if $this->module == "Admissions" || $this->module == "HomeHealth"}
		<li><a href="{$SITE_URL}/?page=data&amp;action=manage&amp;type=case_managers">Case Managers</a></li>
		<li><a href="{$SITE_URL}/?module=HomeHealth&amp;page=clinicians&amp;action=manage">Home Health Clinicians</a></li>
		<li><a href="{$SITE_URL}/?page=data&amp;action=manage&amp;type=healthcare_facilities">Healthcare Facilities</a></li>
		<li><a href="{$SITE_URL}/?page=physicians&amp;action=manage">Physicians</a></li>
		{/if}
		{if $auth->is_admin()}
		<li><a href="{$SITE_URL}/?page=users&amp;action=manage">Users</a></li>
		{/if}
	</ul>
</li>