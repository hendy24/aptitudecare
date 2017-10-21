<h1 class="text-center">Pending Facility Transfer Request</h1>
<h2 class="text-center">for {$patient->first_name} {$patient->last_name}</h2>

<table id="info-form" cellpadding="5" cellspacing="5">
	<tr>
		<td><strong>Transfering From:</strong></td>
		<td><strong>Transferring To:</strong></td>
	</tr>
	<tr>
		{$currentFacility = CMS_Facility::generate()}
		{$currentFacility->load($schedule->facility)}
		<td>{$currentFacility->name}</td>
		
		{$transferFacility = CMS_Facility::generate()}
		{$transferFacility->load($schedule->transfer_facility)}
		<td>{$transferFacility->name}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Admission Date:</strong></td>
		<td><strong>Phone:</strong></td>
	</tr>
	<tr>
		<td>{$schedule->datetime_admit|date_format: 'm/d/Y'}</td>
		<td>{$patient->phone}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Emergency Contact:</strong></td>
		<td><strong>Emergency Phone:</strong></td>
	</tr>
	<tr>
		<td>{$patient->emergency_contact_name1|default: 'None'}</td>
		<td>{$patient->emergency_contact_phone1|default: 'None'}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><strong>Comment:</strong></td>
	</tr>
	<tr>
		<td>{$schedule->transfer_comment|default: 'None'}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="right"><a href="{$SITE_URL}/?page=coord&amp;action=pending_transfers" class="button">Back</a></td>
	</tr>
</table>