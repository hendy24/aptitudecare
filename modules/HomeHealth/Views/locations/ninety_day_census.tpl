

{$this->loadElement("homeHealthHeader")}

<h1>90-Day Census</h1>
<div id="sub-header">
	<div id="patient-search">
		<input type="text" placeholder="Type patient name (last, first or first last)" id="name-to-search">
		<input type="button" value="Search" id="search-patient-name">
	</div>
		<div id="download-links">
		<a href="{$current_url}&amp;export=excel"><img src="{$FRAMEWORK_IMAGES}/icons/excel-xls-icon.png" alt=""></a>
	</div>
</div>
<table class="view">
	<tr>
		<th><a href="" id="patient-name">Patient Name</a></th>
		<th></th>
		<th><a href="" id="referral-date">Referral Date<br>Start of Care</a></th>
		<th><a href="" id="discharge-date">Discharge Date</a></th>
		<th><a href="" id="referral-source">Referral Source</a></th>
		<th>Address</th>
		<th><a href="" id="pcp">Following Physician</a></th>
	</tr>
	{foreach $patients as $patient}
	<tr {if $patient->datetime_discharge != ""}class="background-red"{/if}>
		<td style="width:20%">{$patient->fullName()}</td>
		<td>{$patientMenu->menu($patient)}</td>
		<td>
			{display_datetime($patient->referral_date)}<br>
			{display_datetime($patient->start_of_care)}
		</td>
		<td>{display_date($patient->datetime_discharge)|default: ""}</td>
		<td>{$patient->referral_source}</td>
		<td style="width:19%" class="text-center">{$patient->fullAddress() nofilter}</td>
		<td>{$patient->physician_name}</td>
	</tr>
	{/foreach}
</table>