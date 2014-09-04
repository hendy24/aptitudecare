{include file="$views/elements/search_bar.tpl"}
<h2>Pending Admissions</h2>

<table class="view">
	<tr>
		<th>Patient Name</th>
		<th></th>
		<th>Admission Date</th>
		<th>Admission Location</th>
		<th>Primary Care Physician</th>
	</tr>
	{foreach $admits as $a}
	<tr>
		<td>{$a->fullName()}</td>
		<td>{$patientTools->menu($a)}</td>
		<td>{display_date($a->datetime_admit)}</td>
		<td>{$a->location_name}</td>
		<td>{$a->physician_name|default: "Not Entered"}</td>
	</tr>
	{/foreach}
</table>