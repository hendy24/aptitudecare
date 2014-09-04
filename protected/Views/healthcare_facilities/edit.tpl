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
	});
</script>


<h1>Edit Healthcare Facility</h1>
	
	{include file="data/edit.tpl"}
	<tr>
		<td>Location Type:</td>
		<td>
			<select name="location_type" id="location-type">
				<option value="">Select a location type...</option>
				{foreach $facilityTypes as $type}
				<option value="{$type->id}" {if $location_type_id == $type->id} selected {/if}>{$type->description}</option>
				{/foreach}
			</select>
		</td>
	</tr>

	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td><input type="button" value="Cancel" onClick="history.go(-1);return true;"></td>
		<td colspan="2"><input class="right" type="submit" value="Save" /></td>
	</tr>
	</table>
</form>