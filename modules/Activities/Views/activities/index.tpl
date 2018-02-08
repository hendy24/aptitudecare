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

<div id="page-header">
	<div id="action-left">
		{$this->loadElement("module")}
	</div>
	<div id="center-title">
		{$this->loadElement("selectLocation")}
	</div>
	<div id="action-right">
		<a href="{$SITE_URL}/?page=activities&amp;action=activity&amp;type=new&amp;location={$location->public_id}" class="button">New Activity</a>
	</div>
</div>

<h1>Activities</h1>
<div class="date-header">
	<h2>{$startDate|date_format: "%A, %B %e, %Y"} - {$endDate|date_format: "%A, %B %e, %Y"} &nbsp;<input type="hidden" id="datepicker"></h2>
</div>
<div class="text-center"><a href="{$SITE_URL}/?module=Activities&amp;date={$previousWeek}&amp;location={$location->public_id}">&laquo; Previous Week</a> &nbsp;&nbsp; <a href="{$SITE_URL}/?module=Activities&amp;date={$nextWeek}&amp;location={$location->public_id}">Next Week &raquo;</a></div>

<div id="activities">
	<table class="activities">
		{foreach from=$activitiesArray key="date" item="activities"}
		<tr>
			<th colspan="3">{$date|date_format: "%A, %B %e"}</th>
		</tr>
		{if is_array($activities)}
			{foreach from=$activities item="activity"}
			<tr>
				<td style="width: 125px">
					{if $activity->all_day == 1}
						All Day
					{else}
						{$activity->time_start|date_format: "%I:%M %p"}
					{/if}
				</td>
				<td>{$activity->description}</td>
				<td class="text-right"><a href="{$SITE_URL}/?page=activities&amp;action=activity&amp;type=edit&amp;id={$activity->public_id}" class="button">Edit</a></td>
			</tr>
			{/foreach}
		{else}
			<tr>
				<td colspan="3" class="text-center">No Scheduled Activity</td>
			</tr>
		{/if}
		<tr>
			<td colspan="3" class="text-right background-white"><a href="{$SITE_URL}/?page=activities&amp;action=activity&amp;type=new&amp;date={$date}&amp;location={$location->public_id}" class="button">Add New Activity</a></td>
		</tr>
		<tr>
			<td colspan="3" style="background-color:#ffffff">&nbsp;</td>
		</tr>
		{/foreach}
	</table>
</div>