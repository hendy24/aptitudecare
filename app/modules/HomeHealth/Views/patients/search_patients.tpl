<h1>Search Results</h1>
<input type="button" class="right" value="Previous Page" onclick="history.go(-1)"> 
<br>
<table class="view">
	{if $results}
	<tr>
		<th><a href="" id="patient-name">Patient Name</a></th>
		<th>&nbsp;</th>
		<th><a href="" id="referral-date">Referral Date<br>Start of Care</a></th>
		<th><a href="" id="discharge-date">Discharge Date</a></th>
		<th><a href="" id="referral-source">Referral Source</a></th>
		<th>Address</th>
		<th><a href="" id="pcp">Following Physician</a></th>
	</tr>
	{foreach $results as $result}
	<tr>
		<td>{$result->fullName()}</td>
		<td>{$patientMenu->menu($result)}</td>
		<td>{$result->start_of_care|date_format}</td>
		<td>{$result->datetime_discharge|date_format}</td>
		<td>{$result->name}</td>
		<td>{if !empty($result->address)}{$result->address}<br>{$result->city}, {$result->state} {$result->zip}{/if}</td>
		<td>{if !empty ($result->physician_last)}{$result->physician_last}, {$result->physician_first}{/if}</td>
	</tr>
	{/foreach}
	{else}
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="text-center"> There are no results that match the searched name. Please try again.</td>
	</tr>
	{/if}
</table>