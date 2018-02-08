<h1>Enter Patient Visits</h1>

<form action="{$SITE_URL}" method="post">
	<input type="hidden" name="page" value="patients">
	<input type="hidden" name="action" value="submit_patient_visits">
	<input type="hidden" name="patient" value="{$patient->public_id}">
	<input type="hidden" name="current_url" value="{$current_url}">
	<table class="form">
		<tr>
			<td>&nbsp;</td>
			<td class="text-strong text-center">Date</td>
			<td class="text-strong text-center">Time</td>
		</tr>
		<tr>
			<td><strong>Doctor Visit:</strong></td>
			<td><input type="text" class="datepicker" name="physician_visit_date" size="10"></td>
			<td><input type="text" class="timepicker" name="physician_visit_time" size="6"></td>
		</tr>
		<tr>
			<td><strong>Nurse Practitioner Visit:</strong></td>
			<td><input type="text" class="datepicker" name="nurse_practitioner_visit_date" size="10"></td>
			<td><input type="text" class="timepicker" name="nurse_practitioner_visit_time" size="6"></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>

		<tr>
			<td><strong>Nurse Visit:</strong></td>
			<td><input type="text" class="datepicker" name="nurse_visit_date" size="10"></td>
			<td><input type="text" class="timepicker" name="nurse_visit_time" size="6"></td>
		</tr>
		<tr>
			<td><strong>Therapist Visit:</strong></td>
			<td><input type="text" class="datepicker" name="therapist_visit_date" size="10"></td>
			<td><input type="text" class="timepicker" name="therapist_visit_time" size="6"></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>


		<tr>
			<td class="text-right" colspan="3"><input type="submit" value="Save"></td>
		</tr>
	</table>
</form>

<br>
<br>

{if !empty ($patientVisits)}
<h2>Previous Visits</h2>
<table class="form">
	<tr>
		<th style="width: 250px">Visit By</th>
		<th style="width: 200px">Visit Date &amp; Time</th>
	</tr>
	{foreach from=$patientVisits item="visit"}
	<tr>
		<td>{stringify($visit->visit_type)}</td>
		<td>{$visit->datetime_visit|date_format: "%D %R"}</td>
	</tr>
	{/foreach}
</table>
{/if}