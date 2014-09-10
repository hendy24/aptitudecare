<script>
	$(document).ready(function () {
		var $clinician = $("#clinician");
		var $clinicianRow = $("#clinician-type-row");
		var $group = $("#group");

		if ($clinician.val() == '') {
			$clinicianRow.hide();
		} 

		if ($group.val() == 6) {
			$clinicianRow.show();
		}
		
		$("#edit").validate({
			rules: {
				first_name: "required",
				last_name: "required",
				phone: "required",
				email: "email",
				healthcare_facility: "required"
			}
		}); 

		$("#group").change(function() {
			if ($(this).val() == 6) {
				$clinicianRow.show();
			} else {
				$clinicianRow.hide();
			}
		});
	});
</script>


<h1>Edit User</h1>
	
	{include file="data/edit.tpl"}

	<tr>
		<td>Default Location:</td>
		<td>
			<select name="default_location" id="user-location">
				<option value="">Select a location...</option>
				{foreach $available_locations as $location}
				<option value="{$location->id}" {if $default_location == $location->id} selected{/if}>{$location->name}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr id="additional-locations">
		<td style="vertical-align:top">Additional Locations:</td>
		<td style="vertical-align: top">
		{foreach $available_locations as $k => $loc name=count}
			
			<input type="checkbox" name="user_location[{$k}]" id="{$loc->id}" value="{$loc->id}" {foreach $additional_locations as $location} {if $location->id == $loc->id} checked{/if}{/foreach} /> {$loc->name}<br>
			{if $smarty.foreach.count.iteration % 8 == 0}
				</td>
				<td style="vertical-align:top">
			{/if}
		{/foreach}
		</td>
	</tr>
	<tr>
		<td>Group:</td>
		<td>
			<select name="group" id="group">
				<option value="">Select a group role...</option>
				{foreach $groups as $group}
				<option value="{$group->id}" {if $group_id == $group->id} selected {/if}>{$group->description}</option>
				{/foreach}
			</select>
		</td>
		
	</tr>
	<tr id="clinician-type-row">
		<td>Clinician Type:</td>
		<td>
			<select name="clinician" id="clinician">
				<option value="">Select the clinician type...</option>
				{foreach $clinicianTypes as $type}
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
		<td colspan="4"><input class="right" type="submit" value="Save" /></td>
	</tr>
	</table>
</form>`