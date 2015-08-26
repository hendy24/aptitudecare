<div id="page-header">
	<div id="action-left">
		{$this->loadElement("module")}
	</div>
	<div id="center-title">
		{$this->loadElement("selectLocation")}
	</div>
	<div id="action-right">
		<a href="{$SITE_URL}/?page=activities&amp;action=add_new&amp;location={$location->public_id}" class="button">New Activity</a>
	</div>
</div>

<h1>Activities</h1>