<h1>Add a New Activity</h1>

<form action="{$SITE_URL}" method="post">
	<input type="hidden" name="page" value="activities">
	<input type="hidden" name="action" value="save_activity">
	<input type="hidden" name="location" value="{$location->public_id}">
	<input type="hidden" name="current_url" value="{$current_url}">
	<table class="form">
		<tr>
			<td class="text-strong">Date &amp; Time:</td>
			<td><input type="text" class="datepicker" name="datetime_start"></td>
		</tr>
		<tr>
			<td class="text-strong">Activity:</td>
			<td><input type="text" name="description" size="60"></td>
		</tr>
		<tr>
			<td class="text-strong">Repeat</td>
			<td>
				<select name="repeat_type" id="">
					<option value="">None</option>
					<option value="daily">Daily</option>
					<option value="weekly">Weekly</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td><input type="button" value="Cancel" onclick="history.go(-1)"></td>
			<td class="text-right"><input type="submit" value="Submit"></td>
		</tr>
	</table>
</form>

<p class="note text-grey"><strong>NOTE:</strong>  The text counter for the activity is only a guide and will not actually prevent you from exceeding the 25 character limit.  Activities which exceed 25 characters will take more than one line on the TV. If there are multiple activities in a day it may prevent all the activities from appearing.</p>
