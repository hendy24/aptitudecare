<div class="container">

	<!-- Navigation buttons -->
	<div class="row">
		<div class="col-lg-4">
			{$this->loadElement("module")}
		</div>
		<div class="col-lg-4 text-center">
			{$this->loadElement("selectLocation")}
		</div>
		<div class="col-lg-4">
			<a href="{$SITE_URL}/?module=Activities&amp;page=activities&amp;action=activity&amp;type=new&amp;location={$location->public_id}" class="btn btn-primary pull-right">New Activity</a>
			<a href="{$SITE_URL}/?module=Activities&amp;page=activities&amp;action=print_activities&amp;location={$location->public_id}&amp;date={$startDate}&amp;pdf=true" target="_blank" class="btn btn-primary pull-right">Print activities</a>
		</div>
	</div>

	<h1>Activities</h1>
	<div class="row">
		<div class="col-12 text-center">
			<a href="{$SITE_URL}/?module=Activities&amp;date={$previousWeek}&amp;location={$location->public_id}"><i class="fas fa-arrow-alt-circle-left m-2"></i></a>
			<span class="text-14 align-top">{$startDate|date_format: "%A, %B %e, %Y"} - {$endDate|date_format: "%A, %B %e, %Y"}</span>
			<a href="{$SITE_URL}/?module=Activities&amp;date={$nextWeek}&amp;location={$location->public_id}"><i class="fas fa-arrow-alt-circle-right m-2"></i></a>
		</div>
	</div>

	<div class="mt-5">
		<table class="table">
			{foreach from=$activitiesArray key="date" item="activities"}
			<tr>
				<th colspan="3" class="table-dark text-center">{$date|date_format: "%A, %B %e"}</th>
			</tr>
			{if is_array($activities)}
				{foreach from=$activities item="activity"}
				<tr>
					<td>
						{if $activity->all_day == 1}
							All Day
						{else}
							{$activity->time_start|date_format: "%I:%M %p"}
						{/if}
					</td>
					<td>{$activity->description}</td>
					<td class="text-right">
						<a href="{$SITE_URL}/?module=Activities&amp;page=activities&amp;action=activity&amp;type=edit&amp;id={$activity->public_id}" class="btn"><i class="far fa-edit"></i></a>
					</td>
				</tr>
				{/foreach}
			{else}
				<tr>
					<td colspan="3" class="text-center">No Scheduled Activity</td>
				</tr>
			{/if}
			<tr>
				<td colspan="3" class="text-right">
					<a href="{$SITE_URL}/?module=Activities&amp;page=activities&amp;action=activity&amp;type=new&amp;date={$date}&amp;location={$location->public_id}" class="btn"><i class="fas fa-plus"></i></a>
				</td>
			</tr>
			{/foreach}
		</table>
	</div>
