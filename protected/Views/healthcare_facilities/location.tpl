<script>
	$(document).ready(function() {
		$('#phone').mask("(999) 999-9999");
		$('#fax').mask("(999) 999-9999");
		$("#add").validate({
			rules: {
				name: "required",
				city: "required",
				state: "required",
				zip: "required",
				location_type: "required"
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


		$("#state").autocomplete({
			lookup: states,
			onSelect: function (suggestion) {
				$("#state").val(suggestion.data);
			}
		});


	});
	
</script>
<h1>Add a new Healthcare Facility</h1>
<br>
<form name="add" id="add" method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="HealthcareFacilities">
	<input type="hidden" name="action" value="submitAdd">
	<input type="hidden" name="id" value="{$l->public_id}">
	<input type="hidden" name="isMicro" value="{$isMicro}">
	<input type="hidden" name="submit" value="true">
	<input type="hidden" name="path" value="{$current_url}">

	<table class="form">
		<tr class="title-row">
			<td>Location Name</td>
			<td colspan="2">Location Type:</td>
		</tr>
		<tr>
			<td><input type="text" name="name" value="{$l->name}" size="30"></td>
			<td colspan="2">
				<select name="location_type" id="location-type">
					<option value="">Select a location type...</option>
					{foreach $facilityTypes as $type}
					<option value="{$type->id}" {if $type->id == $l->location_type_id} selected{/if}>{$type->description}</option>
					{/foreach}
				</select>	
			</td>
		</tr>
		<tr class="title-row">
			<td colspan="3">Address</td>
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="address" value="{$l->address}" size="50"></td>
		</tr>
		<tr class="title-row">
			<td>City</td>
			<td>State</td>
			<td>Zip</td>
		</tr>
		<tr>
			<td><input type="text" name="city" value="{$l->city}"></td>
			<td><input type="text" name="state" id="state" value="{$l->state}" size="20"></td>
			<td><input type="text" name="zip" value="{$l->zip}" size="12"></td>
		</tr>
		<tr class="title-row">
			<td>Phone</td>
			<td>Fax</td>
		</tr>
		<tr>
			<td><input type="text" id="phone" name="phone" value="{$l->phone}"></td>
			<td><input type="text" id="fax" name="fax" value="{$l->fax}"></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td><input type="button" value="Cancel" onClick="history.go(-1);return true;"></td>
			<td><input class="right" type="submit" value="Save" /></td>
		</tr>
		
	</table>
</form>
