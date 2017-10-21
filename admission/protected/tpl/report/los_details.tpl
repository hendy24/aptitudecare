{setTitle title="Length of Stay Details"}

<h1 class="text-center">Length of Stay Details<br /><span class="text-16">for {$facility->name}</span></h1>
<br />
<br />
<form><input type="button" value="Back" onClick="history.go(-1);return true;"></form>
<br />
<table id="report-table" cellpadding="5" cellspacing="5">
	<tr>
		<th>Patient Name</th>
		<th>Admission Date</th>
		<th>Discharge Date</th>
		<th>Length Of Stay</th>
		<th width="400px">Discharge Comment</th>
	</tr>
	{foreach $patients as $patient}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td valign="top" align="left">{$patient->name}</td>
			<td valign="top">{$patient->datetime_admit|date_format: "%m/%d/%Y"}</td>
			<td valign="top">{$patient->datetime_discharge|date_format: "%m/%d/%Y"}</td>
			<td valign="top">{$patient->length_of_stay}</td>
			<td>{$patient->discharge_comment}</td>
		</tr>
	{/foreach}
	<tr style="border-top: 1px solid #333; line-height: 20px;">
		<td>{count($patients)} total discharges</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>{$avgLoS}</td>
		<td>&nbsp;</td>
	</tr>
</table>
