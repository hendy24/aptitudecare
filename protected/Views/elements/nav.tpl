<nav>
	<ul>
		<li><a href="{$siteUrl}">Home</a></li>
		<li>Admissions
			<ul>
				<li><a href="{$siteUrl}?module={$module}&amp;page=admissions&amp;action=new_admit">New Admission</a></li>
				<li><a href="{$siteUrl}?module={$module}&amp;page=admissions&amp;action=pending_admits">Pending Admissions</a></li>
			</ul>
		</li>
		<li>Discharges
			<ul>
<!-- 				<li><a href="{$siteUrl}/?module={$module}&amp;page=discharges&amp;action=manage">Manage Discharges</a></li>
 -->				<li><a href="{$siteUrl}/?module={$module}&amp;page=discharges&amp;action=schedule">Schedule Discharges</a></li>			
			</ul>
		</li>
		<li>Info
			<ul>
				<li><a href="{$siteUrl}/?module={$module}&amp;page=locations&amp;action=census">Census</a></li>
			</ul>
		</li>
		<li>Data
			<ul>
				<li><a href="{$siteUrl}/?page=data&amp;action=manage&amp;type=case_managers">Case Managers</a></li>
				<li><a href="{$siteUrl}/?module=HomeHealth&amp;page=clinicians&amp;action=manage">Home Health Clinicians</a></li>
				<li><a href="{$siteUrl}/?page=data&amp;action=manage&amp;type=healthcare_facilities">Healthcare Facilities</a></li>
				<li><a href="{$siteUrl}/?page=data&amp;action=manage&amp;type=physicians">Physicians</a></li>
				{if $auth->is_admin()}
				<li><a href="{$siteUrl}/?page=data&amp;action=manage&amp;type=users">Users</a></li>
				{/if}
			</ul>
		</li>
	</ul>
</nav>
