<h1 class="text-center">Approve Facility Transfer Request</h1>
<h2 class="text-center">for {$patient->first_name} {$patient->last_name}</h2>

<form name="edit_transfer" method="post" action="{$SITE_URL}" id="inquiry-form"> 
	<input type="hidden" name="page" value="coord" />
	<input type="hidden" name="action" value="submitTransferApproval" />
	<input type="hidden" name="id" value="{$patient->pubid}" />
	<input type="hidden" name="schedule" value="{$schedule->pubid}" />
		
	<table id="info-form" cellpadding="5" cellspacing="5">
		<tr>
			<th colspan="2">Patient Info</th>
		</tr>
		<tr>
			<td><strong>Patient Name:</strong></td>
			<td>{$patient->first_name} {$patient->last_name}</td>
		</tr>
		<tr>
			<td><strong>Phone:</strong></td>
			<td>{$patient->phone}</td>
		</tr>
		<tr>
			<td><strong>Emergency Contact:</strong></td>
			<td>{$patient->emergency_contact_name1|default: 'None'}</td>
		</tr>
		<tr>
			<td><strong>Emergency Phone:</strong></td>
			<td>{$patient->emergency_contact_phone1|default: 'None'}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		
		<tr>
			<th colspan="2">Transfer From Facility Info</th>
		</tr>
		<tr>
			<td><strong>Admission Date:</strong></td>
			<td>{$schedule->datetime_admit|date_format: 'm/d/Y'}</td>
		</tr>
		<tr>
			<td><strong>Transfering From:</strong></td>
			{$currentFacility = CMS_Facility::generate()}
			{$currentFacility->load($schedule->facility)}
			<input type="hidden" name="transfer_from" value="{$currentFacility->pubid}" />
			<td>{$currentFacility->name}</td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong>Time of Discharge</strong>:</td>
			<td><input type="text" size="20" name="datetime_discharge" value="{$schedule->datetime_discharge|date_format:"%m/%d/%Y %I:%M %P"}" class="datetime-picker" /></td>
</td>
		</tr>
		<tr>
			<td colspan="2"><strong>Discharge Comment:</strong></td>
		</tr>
		<tr>
			<td colspan="2"><textarea name="discharge_comment" cols="90" rows="5"></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		
		<tr>
			<th colspan="2">Transfer To Facility Info</th>
		</tr>
		<tr>
			<td><strong>Transferring To:</strong></td>			
			{$transferFacility = CMS_Facility::generate()}
			{$transferFacility->load($schedule->transfer_to_facility)}
			<td>
				<select name="transfer_to">
					{foreach $facilities as $facility}
						<option value="{$facility->pubid}" {if $transferFacility->id == $facility->id} selected{/if}>{$facility->name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 150px;"><strong>Time of Admission</strong>:</td>
			<td><input type="text" size="20" name="datetime_admit" value="{$datetime|date_format:"%m/%d/%Y %I:%M %P"}" class="datetime-picker" /></td>
</td>
		</tr>
		<tr>
			<td colspan="2"><strong>Transfer Comment:</strong></td>
		</tr>
		<tr>
			<td colspan="2"><textarea name="transfer_comment" cols="90" rows="5">{$schedule->transfer_comment|default: 'None'}</textarea></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><input type="submit" value="Save" /></td>
		</tr>
	</table>
</form>