<li class="nav-item">
	<a href="#activitiesSection" class="nav-link dropdown-toggle" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="activitiesSection"><i class="fas fa-hiking"></i> Activities</a>
	<div class="collapse" id="activitiesSection" data-parent="#sidebar-nav">
		<ul>
			<li id="current-activities" class="nav-item">
				<a href="{$SITE_URL}/?module=Activities&amp;page=activities&amp;action=index" class="nav-link">Current Activities</a>
			</li>
			<li class="nav-item">
				<a href="{$SITE_URL}?page=public-display&amp;location={$location->public_id}" class="nav-link" target="_blank">Preview Public Page</a>
			</li>
		</ul>
	</div>
</li>

<!-- <li class="nav-item">
	<a href="#otherSection" class="nav-link dropdown-toggle" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="otherSection">Other</a>
	<div class="collapse" id="otherSection" data-parent="#sidebar-nav">
		<ul class="nav flex-column ml-4">	
			<li class="nav-item">
				<a href="{$SITE_URL}/?module=Activities&amp;page=info&amp;action=add_new&amp;location={$location->public_id}" class="nav-link">Add Page</a>
			</li>
			<li class="nav-item">
				<a href="{$SITE_URL}?page=public-display&amp;location={$location->public_id}" class="nav-link" target="_blank">Preview Public Page</a>
			</li>
		</ul>
	</div>
</li>-->

