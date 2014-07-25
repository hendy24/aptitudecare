<nav>
	<ul>
		<li><a href="{$siteUrl}?module={$module}">Home</a></li>
		<li>Locations
			<ul>
				{foreach $locations as $l}
				<li><a href="{$currentUrl}&location={$l->public_id}">{$l->name}</a></li>
				{/foreach}
			</ul>
		</li>
		<li>Admissions
			<ul>
				<li><a href="{$siteUrl}?module={$module}&page=admission&action=new_admit">New Admission</a></li>	
			</ul>
		</li>
		<li>Data
			<ul>
				<li>Case Managers</li>
				<li>Clinicians</li>
				<li>Healthcare Facilities</li>
				<li>Users</li>
			</ul>
		</li>
	</ul>
</nav>
