<script>
	$(document).ready(function() {
		// set max length
		var max_length = 25;
		$message = $("#activity-description").val();
		var messageLength = max_length - $message.length;

		// load in current characters when page loads
		$("#counter").html(messageLength);

		// run listen when key press
		whenkeydown(max_length);
		
		if ($("input.all-day-box").is(":checked")) {
			$("#time").hide();
		} else {
			$("#time").show();
		}
		
		$("#all-day").change(function() {
			if ($('input.all-day-box').is(':checked')) {
				$("#time").hide()	
			} else {
				$("#time").show();
			}
		});	



		$("#delete").click(function(e) {
			e.preventDefault();
			var activityId = $("#activity-id").val();

			$("#dialog").dialog({
				buttons: {
					"Confirm": function() {
						$.ajax({
							type: 'post',
							url: SITE_URL,
							data: {
								page: "activities",
								action: 'deleteId',
								id: activityId,
							},
							success: function() {
								window.location = SITE_URL + "/?module=Activities&page=activities";
							}
						});
						
					},
					"Cancel": function() {
						$(this).dialog("close");
					}
				}
			});
		});

	});

	$(function() {
		$(document).tooltip();
	});


	whenkeydown = function(max_length) {
		$("#activity-description").unbind().keypress(function() {

			// check if appropriate text area is being used
			if (document.activeElement.id == "activity-description") {

				// get the data in the field
				var text = $(this).val();

				//set number of characters
				var numOfChars = text.length;

				// set the chars left
				var charsLeft = max_length - numOfChars;

				// check if we are still within our max
				if (numOfChars <= max_length) {
					// set the length of text into the counter
					$("#counter").html(charsLeft);
				} else {
					// trim the string to the max chars
					$(this).val(text.substring(0, max_length));
				}
			}
		});
	}


</script>
<style type="text/css">
	.ml-10{
		margin-left: 10px;
	}
</style>

<h1>{$headerTitle}</h1>

<form action="{$SITE_URL}" method="post">
	<input type="hidden" name="page" value="activities">
	<input type="hidden" name="action" value="save_activity">
	<input type="hidden" name="location" value="{$location->public_id}">
	<input type="hidden" name="activity_id" id="activity-id" value="{$activity->public_id}">
	<input type="hidden" name="current_url" value="{$current_url}">
	<table class="form">

		{if $activity->repeat_week != ""}
		<tr>
			<td class="text-center text-strong" colspan="4">This activity is scheduled to recur on week {$activity->repeat_week} of each month.</td>
		</tr>
		{elseif $activity->repeat_weekday != ""}
		<tr>
			<td colspan="4" class="text-center text-strong">This activity is scheduled to recur every {$activity->date_start|date_format: "%A"}{if $activity->time_start != ""} at {$activity->time_start|date_format: "%l:%M %p"}{/if}</td>
		</tr>
		{elseif $activity->daily}
		<tr>
			<td>This is a daily recurring activity{if $activity->time_start != ""} at {$activity->time_start|date_format: "%l:%M %P"}{/if}</td>
		</tr>
		{/if}

		{if $activity->repeat_week != "" OR $activity->repeat_weekday != "" OR $activity->daily}
			<tr>
				<td>&nbsp;</td>
				<td><input type="checkbox" name="change_all" value="1" id="change-all" checked> Change all future occurances of this activity</td>
			</tr>
		{/if}

		<tr>
			<td class="text-strong">Date:<input type="text" class="datepicker" name="date_start" value="{$activity->date_start|date_format: '%D'}" size="10"></td>
			<td class="text-strong" id="all-day">All day?
				{if $activity->all_day == 1}
					<input type="checkbox" name="all_day" value="true" checked class="all-day-box"/>
				{else}
					<input type="checkbox" name="all_day" value="true" class="all-day-box"/>
				{/if}
			</td>
			<td class="text-strong text-right" id="time">Time:<input type="text" class="timepicker" name="time_start" value="{$activity->time_start|date_format: '%H:%M'}" size="6"></td>
		</tr>
		<tr>
			<td class="text-strong">Activity:</td>
			<td colspan="2"><input type="text"  name="description" value="{$activity->description}" id="activity-description" size="40"></td>
		</tr>
		<tr>
			<td class="text-right text-grey" colspan="3">You have <span id="counter"></span> characters left.</td>
		</tr>
		<tr>
			<td class="text-strong">Repeat</td>
			<td>
				<select name="repeat_type" id="">
					<option value="">None</option>
					<option value="daily" {if $activity->daily} selected{/if}>Daily</option>
					<option value="weekly" {if $activity->repeat_weekday != ""} selected{/if}>Weekly</option>
					<option value="monthly" {if $activity->repeat_week != ""} selected{/if}>Monthly</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<input type="button" value="Cancel" onclick="history.go(-1)">
				<input type="button" id="delete" class="delete" value="Delete">
			</td>
			<td colspan="2" class="text-right"><input type="submit" value="Submit"></td>
		</tr>
	</table>
</form>



<div id="dialog" title="Confirmation Required">
	<p>Are you sure you want to delete this item? This cannot be undone.</p>
</div>

