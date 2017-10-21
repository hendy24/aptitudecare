<h1 class="text-center">{if $schedule->transfer_request}Edit the{else}Enter a new{/if} Pending Transfer Request</h1>
<h2 class="text-center">for {$patient->first_name} {$patient->last_name}</h2>

<form action="{$SITE_URL}" method="post">
	<input type="hidden" name="page" value="patient" />
	<input type="hidden" name="action" value="submitTransferRequest" />
	<input type="hidden" name="schedule" value="{$schedule->pubid}" />
		
	<table id="info-form">
		<tr>
			
			<td><strong>Admission Facility:</strong></td>
			{$facility = CMS_Facility::generate()}
			{$facility->load($schedule->facility)}
			<input type="hidden" name="transfer_from_facility" value="{$facility->pubid}" />
			<td>{$facility->name}</td>
		</tr>
		<tr>
			<td><strong>Requested Facility:</strong></td>
			<td>
				<select name="transfer_to_facility">
					<option value="">Select facility...</option>
					{foreach $facilities as $f}
					<option value="{$f->pubid}" {if $schedule->transfer_to == $f->id} selected{/if}>{$f->name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2"><strong>Comments:</strong></td>
		</tr>
		<tr>
			<td colspan="2"><textarea name="transfer_comment" rows="5" cols="87" placeholder="">{$schedule->transfer_comment}</textarea></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><input type="submit" value="Save" /></td>
		</tr>
	</table>
</form>