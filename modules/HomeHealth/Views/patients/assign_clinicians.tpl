
<h1>Assign Clinicians<br>
<span class="text-14">for</span> <br><span class="text-20">{$patient->first_name} {$patient->last_name}</span></h1>
<br>

<form action="{$siteUrl}" method="post" id="assign-clinicians">
	<input type="hidden" name="page" value="patients" />
	<input type="hidden" name="action" value="assign_clinicians" />
	<input type="hidden" name="patient" value="{$patient->public_id}" />
	<input type="hidden" name="currentUrl", value="{$currentUrl}" />
	<table class="form">
		<tr>
			{foreach $clinicianTypes as $type}
			{foreach $clinicianByType as $key => $clinician name=count} 
			{if $type->name == $key}
			<td><strong>{$type->description}:</strong></td>
			<td>	
				<select name="clinician_id[{$key}]" id="">
					<option value="">Select...</option>
				{foreach $clinician as $c}
					{$id = $c->name|cat:"_id"}
					<option value="{$c->user_id}"{if $c->user_id == $schedule->$id} selected{/if}>{$c->fullName()}</option>
				{/foreach}
				</select>
			</td>
			{if $smarty.foreach.count.iteration % 2 == 0}
				</tr>
				<tr>
			{/if}
			{/if}
			{/foreach}
			{/foreach}
		</tr>
		<tr>
			<td colspan="4" class="text-right">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" class="text-right">
				<input type="submit" name="submit" value="Save" />
			</td>
		</tr>
	</table>
</form>
