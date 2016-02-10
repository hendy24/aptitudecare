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
<h2>{$startDate|date_format: "%A, %B %e, %Y"} - {$endDate|date_format: "%A, %B %e, %Y"}<div style="margin: 15px"><input type="hidden" id="datepicker"></div></h2>

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
						{$activity->date_start|date_format:"%b, %e"} {$activity->time_start|date_format: "%I:%M %p"}
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