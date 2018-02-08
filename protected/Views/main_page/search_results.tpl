<h1>Search Results</h1>

<table class="view">
	<tr>
		<th>Patient Name</th>
		<th></th>
		<th>Admission Date</th>
		<th>Discharge Date</th>
		<th>Status</th>
	</tr>
	{foreach $search_results as $patient}
	<tr>
		<td>{$patient->last_name}, {$patient->first_name}</td>
		<td>{$patientTools->menu($patient)}</td>
		<td>{display_date($patient->datetime_admit)}</td>
		<td>{display_date($patient->datetime_discharge)|default: ""}</td>
		<td>{$patient->status}</td>
	</tr>
	{/foreach}
</table>