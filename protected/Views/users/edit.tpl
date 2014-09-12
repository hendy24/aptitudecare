<script>
	$(document).ready(function () {
		$("#phone").mask("(999) 999-9999");

		var $clinician = $("#clinician");
		var $clinicianRow = $(".clinician-type-cell");
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
	
<form name="edit" id="edit" method="post" action="{$siteUrl}">
	<input type="hidden" name="page" value="users" />
	<input type="hidden" name="action" value="submitAdd" />
	<input type="hidden" name="id" value="{$user->public_id}" />
	<input type="hidden" name="path" value="{$current_url}" />

	<table class="form">
		<tr>
			<td><strong>First Name:</strong></td>
			<td><input type="text" name="first_name" id="first-name" value="{$user->first_name}"></td>
			<td><strong>Last Name:</strong></td>
			<td><input type="text" name="last_name" id="last-name" value="{$user->last_name}"></td>
			<td><strong>Phone:</strong></td>
			<td><input type="text" name="phone" id="phone" value="{$user->phone}"></td>
		</tr>
		<tr>
			<td><strong>Email:</strong></td>
			<td colspan="2"><input type="text" name="email" id="email" value="{$user->email}" size="35px" /></td>
			<td><a href="{$siteUrl}/?page=users&amp;action=reset_password&amp;id={$user->public_id}" class="button">Reset Password</a></td>
		</tr>
		<tr>
			<td><strong>Default Location:</strong></td>
			<td colspan="2">
				<select name="default_location" id="user-location">
					<option value="">Select a location...</option>
					{foreach $available_locations as $location}
					<option value="{$location->id}" {if $default_location == $location->id} selected{/if}>{$location->name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr id="additional-locations">
			<td colspan="4" style="vertical-align:top"><strong>Additional Locations:</strong></td>
		</tr>
		<tr>
			<td colspan="2" style="vertical-align: top">
			{foreach $available_locations as $k => $loc name=count}
				
				<input type="checkbox" name="user_location[{$k}]" id="{$loc->id}" value="{$loc->id}" {foreach $additional_locations as $location} {if $location->id == $loc->id} checked{/if}{/foreach} /> {$loc->name}<br>
				{if $smarty.foreach.count.iteration % 8 == 0}
					</td>
					<td colspan="2" style="vertical-align:top">
				{/if}
			{/foreach}
			</td>
		</tr>
		<tr>
			<td><strong>Group:</strong></td>
			<td colspan="2">
				<select name="group" id="group">
					<option value="">Select a group role...</option>
					{foreach $groups as $group}
					<option value="{$group->id}" {if $group_id == $group->id} selected {/if}>{$group->description}</option>
					{/foreach}
				</select>
			</td>
			
			<td class="clinician-type-cell"><strong>Clinician Type:</strong></td>
			<td class="clinician-type-cell" colspan="2">
				<select name="clinician" id="clinician">
					<option value="">Select the clinician type...</option>
					{foreach $clinicianTypes as $type}
					<option value="{$type->id}">{$type->description}</option>
					{/foreach}
				</select>
			</td>
		</tr>
<!-- 		<tr>
			<td><strong>Default Module:</strong></td>
			<td colspan="2">
				<select name="default_module" id="user-module">
					<option value="">Select a module...</option>
					{foreach $available_modules as $mod}
					<option value="{$location->id}" {if $default_mod== $mod->id} selected{/if}>{$mod->name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
 -->		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td><input type="button" value="Cancel" onClick="history.go(-1);return true;"></td>
			<td colspan="5"><input class="right" type="submit" value="Save" /></td>
		</tr>
	</table>
</form>`