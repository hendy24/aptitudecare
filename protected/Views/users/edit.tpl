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
		<td>
		{foreach $available_locations as $k => $loc}
			
			<input type="checkbox" name="user_location[{$k}]" id="{$loc->id}" value="{$loc->id}" {foreach $additional_locations as $location} {if $location->id == $loc->id} checked{/if}{/foreach} /> {$loc->name}<br>
			
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
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td><input type="button" value="Cancel" onClick="history.go(-1);return true;"></td>
		<td colspan="2"><input class="right" type="submit" value="Save" /></td>
	</tr>
	</table>
</form>`