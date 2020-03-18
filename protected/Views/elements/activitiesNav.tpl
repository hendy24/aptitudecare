<li class="nav-item">
	<a href="{$SITE_URL}/?module=activities&amp;page=activities&amp;action=index" class="nav-link">Current Activities</a>
</li>
<li class="nav-item ml-4">
	<a href="#otherSection" class="nav-link dropdown-toggle" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="otherSection">Other</a>
	<div class="collapse" id="otherSection" data-parent="#sidebar-nav">
		<ul class="nav flex-column ml-4">	
			<li class="nav-item">
				<a href="{$SITE_URL}/?module=Activities&amp;page=info&amp;action=add_new&amp;location={$location->public_id}" class="nav-link">Add Page</a>
			</li>
			<li class="nav-item">
				<a href="{$SITE_URL}/?module=Dietary&amp;page=public&amp;location={$location->public_id}" class="nav-link" target="_blank">Preview Public Page</a>
			</li>
		</ul>
	</div>
</li>

