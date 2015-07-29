<script>
	window.onload = function () { window.print(); }
</script>

<h1>{$patient->fullName()}</h1>

<table class="form">
	<tr>
		<th colspan="4">General Information</th>
	</tr>
	<tr>
		<td class="text-strong">Admit Date:</td>
		<td>{$schedule->datetime_admit|date_format: "%D"}</td>
		<td class="text-strong">Birthdate:</td>
		<td>{$patient->date_of_birth|date_format: "%D"}</td>
	</tr>
	<tr>
		<td class="text-strong">Room #</td>
		<td>{$schedule->room_number}</td>
		<td class="text-strong">Age:</td>
		<td>{$age}</td>
	</tr>
	<tr>
		<th colspan="4">Diet Information</th>
	</tr>
	<tr>
		<td class="text-strong">Texture:</td>
		<td>{$diet->texture}</td>
		<td class="text-strong">Orders:</td>
		<td>{$diet->orders}</td>
	</tr>
	<tr>
		<td class="text-strong">Portion Size:</td>
		<td>{$diet->portion_size}</td>
		<td class="text-strong">AM Snack</td>
		<td>{$diet->am_snack}</td>
	</tr>
	<tr>
		<td class="text-strong">PM Snack</td>
		<td>{$diet->pm_snack}</td>
		<td class="text-strong">Bedtime Snack</td>
		<td>{$diet->bedtime_snack}</td>
	</tr>
	<tr>
		<td class="text-strong">Special Requests:</td>
		<td colspan="3">{$diet->special_requests}</td>
	</tr>
	<tr>
		<td class="text-strong">Date:</td>
		<td>{$smarty.now|date_format: "%D"}</td>
		<td class="text-strong">Date Changed:</td>
		<td>{if !empty($diet->datetime_modified)}{$diet->datetime_modified|date_format: "%D"}{else}{$diet->datetime_created|date_format: "%D"}{/if}</td>
	</tr>
</table>