<script>
	$(document).ready(function () {
		$("#edit").validate({
			rules: {
				first_name: "required",
				last_name: "required",
				phone: "required",
				email: "email",
				healthcare_facility: "required"
			}
		}); 

		$("#phone").mask("(999) 999-9999");
		$("#fax").mask("(999) 999-9999");
		$("#zip").mask("99999");

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


<h1>{$pageHeader}</h1>
	
<form name="add" id="add" method="post" action="{$siteUrl}">
	<input type="hidden" name="page" value="physicians" />
	<input type="hidden" name="action" value="submitAdd" />
	<input type="hidden" name="id" value="{$physician->public_id}">
	<input type="hidden" name="isMicro" value="{$isMicro}" />
	<input type="hidden" name="submit" value="true" />
	<input type="hidden" name="path" value="{$current_url}" />

	<table class="form">
		<tr>
			<td colspan="2"><strong>First Name:</strong></td>
			<td><strong>Last Name:</strong></td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" name="first_name" id="first-name" value="{$physician->first_name}"></td>
			<td><input type="text" name="last_name" id="last-name" value="{$physician->last_name}" style="width: 150px"></td>
		</tr>
		<tr>
			<td colspan="3"><strong>Address</strong></td>
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="address" id="address" value="{$physician->address}" style="width: 325px"></td>
		</tr>
		<tr>
			<td colspan="2"><strong>City</strong></td>
			<td><strong>State</strong></td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" name="city" id="city" value="{$physician->city}"></td>
			<td><input type="text" name="state" id="state" value="{$physician->state}" style="width: 170px"></td>
		</tr>
		<tr>
			<td><strong>Zip</strong></td>
			<td colspan="2"><strong>Email</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="zip" id="zip" value="{$physician->zip}" style="width: 80px"></td>
			<td colspan="2"><input type="text" name="email" id="email" value="{$physician->email}" style="width:250px"></td>
		</tr>
		<tr>
			<td colspan="2"><strong>Phone</strong></td>
			<td><strong>Fax</strong></td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" name="phone" id="phone" value="{$physician->phone}"></td>
			<td><input type="text" name="fax" id="fax" value="{$physician->fax}"></td>
		</tr>
		
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td><input type="button" value="Cancel" onClick="history.go(-1);return true;"></td>
			<td colspan="2"><input class="right" type="submit" value="Save" /></td>
		</tr>
	</table>
</form>