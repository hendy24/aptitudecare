<h1>Add New Patient</h1>

<form action="{$SITE_URL}" method="post">
	<input type="hidden" name="page" value="patientInfo">
	<input type="hidden" name="action" value="saveAddPatient">
	<input type="hidden" name="location" value="{$location->id}">
	<input type="hidden" name="number" value="{$number}">
	<input type="hidden" name="currentUrl" value="{$currentUrl}">
	<table class="form">
		<tr>
			<td class="text-strong">Room</td>
			<td class="text-strong">Admit Date</td>
			<td class="text-strong">Discharge Date</td>
		</tr>
		<tr>
			<td>{$number}</td>
			<td><input type="text" class="datepicker" name="admit_date" value="" required /></td>
			<td><input type="text" class="datepicker" name="discharge_date" value="" /></td>
		</tr>

		<tr>
			<td class="text-strong">Last Name</td>
			<td class="text-strong">First Name</td>
		</tr>
		<tr>
			<td><input type="text" name="last_name" size="40"></td>
			<td><input type="text" name="first_name" size="30"></td>
		</tr>
		<tr>
			<td colspan="3" class="text-right"><input type="button" id="cancel" value="Cancel" onclick="history.go(-1)"> <input type="submit" value="Save"></td>
		</tr>
	</table>
</form>