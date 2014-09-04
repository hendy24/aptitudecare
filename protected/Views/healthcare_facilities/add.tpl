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

	});
	
</script>


	{include file="data/add.tpl"}
	<tr>
		<td>Location Type:</td>
		<td>
			<select name="location_type" id="location-type">
				<option value="">Select a location type...</option>
				{foreach $facilityTypes as $type}
				<option value="{$type->id}">{$type->description}</option>
				{/foreach}
			</select>
			
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
