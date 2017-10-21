
<script>
	$(document).ready(function() {
		$("#phone").mask("(999) 999-9999");
		$("#fax").mask("(999) 999-9999");

		$("#healthcare-facility-search").autocomplete({
			serviceUrl: SITE_URL,
			params: { 
				page: 'HealthcareFacilities',
				action: 'searchFacilityName',
				location: $("#location").val() 
			}, minChars: 3,
			onSelect: function (suggestion) {
				$("#healthcare-facility-id").val(suggestion.data);
			}

		});

		$("#add").validate({
			rules: {
				first_name: "required",
				last_name: "required",
				phone: "required",
				email: "email",
				healthcare_facility: "required"
			}
		}); 


	});
</script>
<h1>{$pageHeader}</h1>

<form name="add" method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="case_managers">
	<input type="hidden" name="action" value="submit_add">
	<input type="hidden" name="id" value="{$cm->public_id}">
	<input type="hidden" name="location" id="location" value="{$location->public_id}">
	<input type="hidden" name="isMicro" value="{$isMicro}">
	<input type="hidden" name="submit" value="true">
	<input type="hidden" name="path" value="{$current_url}">

	<table class="form">
	<tr class="title-row">
		<td>First Name</td>
		<td>Last Name</td>
	</tr>
	<tr>
		<td><input type="text" name="first_name" value="{$cm->first_name}"></td>
		<td><input type="text" name="last_name" value="{$cm->last_name}" size="30"></td>
	</tr>
	<tr class="title-row">
		<td>Phone</td>
		<td>Fax</td>
	</tr>
	<tr>
		<td><input type="text" name="phone" id="phone" value="{$cm->phone}"></td>
		<td><input type="text" name="fax" id="fax" value="{$cm->fax}"></td>
	</tr>

	<tr class="title-row">
		<td colspan="2">Email Address</td>
	</tr>
	<tr>
		<td colspan="2"><input type="text" name="email" value="{$cm->email}" size="40"></td>
	</tr>

	<tr class="title-row">
		<td>Healthcare Facility</td>	
	</tr>
	<tr>
		<td colspan="2">
			<input type="text" name="healthcare_facility" id="healthcare-facility-search" value="{$healthcareFacility->name}" style="width: 300px" />
			<input type="hidden" name="healthcare_facility_id" id="healthcare-facility-id" value="{$healthcareFacility->id}" />
		</td>

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