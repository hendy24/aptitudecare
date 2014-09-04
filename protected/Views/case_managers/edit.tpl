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


<h1>Edit Case Manager</h1>
	
	{include file="data/edit.tpl"}


	<tr>
		<td>Healthcare Facility:</td>	
		<td>
			<input type="text" name="healthcare_facility" id="healthcare-facility-search" style="width: 200px" value="{$healthcare_facility}" />
			<input type="hidden" name="healthcare_facility_id" id="healthcare-facility-id" value="{$healthcare_facility_id}" />
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