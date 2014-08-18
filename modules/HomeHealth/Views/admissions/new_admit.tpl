<script>
	$(document).ready(function() {
		// $('#new_admission').validate();
		$('#admit-request-phone').mask("(999) 999-9999");
		$('#admit-request-zip').mask("99999");


		$("#admit-from-search").autocomplete({
			serviceUrl: SiteUrl,
			params: { 
				module: 'HomeHealth',
				page: 'HealthcareFacilities',
				action: 'searchFacilityName',
				location: $("#admit-request-location option:selected").val() 
			}, minChars: 3,
			onSelect: function (suggestion) {
				$("#admit-from").val(suggestion.data);
			}

		});

		$("#referral-source-search").autocomplete({
			serviceUrl: SiteUrl,
			params: {
				page: 'MainPage',
				action: 'searchReferralSources',
				location: $("#admit-request-location option:selected").val()
			}, minChars: 3,
			onSelect: function (suggestion) {
				$("#referral-source").val(suggestion.data['id']);
				$("#referral-source-type").val(suggestion.data['type']);
			}
		});


		{$states = getUSAStates()}
		var states = [
		{foreach $states as $abbr => $state}
		{if $state != ''}
			{
				value: "{$state} ({$abbr})",
				data: "{$abbr}"
			}
			{if $state@last != true},{/if}
		{/if}
		{/foreach}
		];


		$("#admit-request-state").autocomplete({
			lookup: states,
			onSelect: function (suggestion) {
				$("#state").val(suggestion.data);
			}
		});


	});
</script>





<h1>New Admission Request</h1>

<form name="new_admission" method="post" action="{$siteUrl}">
	<input type="hidden" name="module" value="HomeHealth" />
	<input type="hidden" name="page" value="admissions" />
	<input type="hidden" name="action" value="new_admit" />
	<input type="hidden" name="submit" value="true">
	<input type="hidden" name="path" value="{$current_url}">
	<input type="hidden" name="submit" value="true" />
	<table class="form-table">
		<tr>
			<td><strong>Admit Date:</strong></td>
			<td colspan="2"><strong>Location:</strong></td>
		</tr>
		<tr>
			<td><input type="text" class="schedule-datetime" id="datepicker" name="admit_date" value="" required /></td>
			<td colspan="2">
				<select name="location" id="admit-request-location">
					{foreach $locations as $location}
					<option value="{$location->public_id}"{if $location->id == $auth->getRecord()->default_location} selected{/if}>{$location->name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td><strong>Admit From:</strong></td>
			<td colspan="2"><strong>Referral Source:</strong></td>
		</tr>
		<tr>
			<td>
				<input type="text" id="admit-from-search" style="width: 250px" required />
				<input type="hidden" name="admit_from" id="admit-from" />
			</td>
			<td colspan="2">
				<input type="text" id="referral-source-search" style="width: 250px" />
				<input type="hidden" id="referral-source" name="referred_by_id" />
				<input type="hidden" id="referral-source-type" name="referred_by_type" />
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3"><strong>Patient Info:</strong></td>
		</tr>
		<tr>
			<td>Last Name</td>
			<td>First Name</td>
			<td>Middle Name</td>
		</tr>
		<tr>
			<td><input type="text" id="admit-request-last-name" name="last_name" style="width:250px;" required  /></td>
			<td><input type="text" id="admit-request-first-name" name="first_name" style="width:150px;" required /></td>
			<td><input type="text" id="admit-request-middle-name" name="middle_name" /></td>
		</tr>
		<tr>
			<td>Phone</td>
			<td>Zip</td>
		</tr>
		<tr>
			<td><input type="text" id="admit-request-phone" name="phone" required /></td>
			<td><input type="text" id="admit-request-zip" name="zip" /></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align: right;"><input type="submit" value="Search" id="admit-request-search" /></td>
		</tr>
	</table>
</form>


