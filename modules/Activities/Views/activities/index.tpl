<style>
	h2, input {
		display: inline;
	}
	.cal-icon {
		margin-top: 10px;
		position: absolute;
	}
	div.date-header {
		text-align: center;
		margin: auto;
	}
</style>
<script>
	$(function() {
		$("#datepicker").datepicker({
			showOn: "button",
			buttonImage: "{$IMAGES}/calendar.png",
			buttonImageOnly: true
		});
	});
	
	$(document).ready(function() {
		$("#datepicker").change(function() {
			window.location = SITE_URL + "/?module=Activities&date=" + $(this).val();
		});
	});
</script>

<div class="container my-5">

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
</div>

<div class="container my-5">
	<h1>Activities</h1>
	<div class="row">
		<div class="col-12 text-center">
			<a href="{$SITE_URL}/?module=Activities&amp;date={$previousWeek}&amp;location={$location->public_id}"><i class="fas fa-arrow-alt-circle-left fa-2x mr-2"></i></a>
			<span class="text-24 align-top">{$startDate|date_format: "%A, %B %e, %Y"} - {$endDate|date_format: "%A, %B %e, %Y"}</span>
			<a href="{$SITE_URL}/?module=Activities&amp;date={$nextWeek}&amp;location={$location->public_id}"><i class="fas fa-arrow-alt-circle-right fa-2x ml-2"></i></a>
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
