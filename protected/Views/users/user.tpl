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
		
		$("#user").validate({
			rules: {
				first_name: "required",
				last_name: "required",
				email: {
					required: true,
					email: true
				},
				password: "required",
				verify_password: {
					equalTo: "#password",
					required: true
				},
				group: "required",
				default_location: "required"
			}
		}); 

		$("#group").change(function() {
			//	Get the modules available for the selected group type
			$.post(SITE_URL, { page: "users", action: "fetchModulesByGroup", group: $("option:selected", this).val() }, function (e) {
				var count = Object.keys(e).length;
				if (count > 1) {
					$.each(e, function (i, d) {
						$("#user-module").append("<option value=\"" + d.id + "\">" + d.name + "</option>"); 
					});
					$("#module-row").show();
				}
				
			},
			"json"
			);

			//  If group is Home Health Clinician show the clinician row
			if ($(this).val() == 6) {
				$clinicianRow.show();
			} else {
				$clinicianRow.hide();
			}
		});


		$("#email").blur(function() {
			var email = $(this).val();
			$.post(SITE_URL, { page: "users", action: "verify_user", term: email }, function (e) {
				if (e == true) {
					console.log('hello');
					$("<p class=\"error\">This user already exists</p>").appendTo($("#email").parent().parent());
				}
			},
			"json"
			);
		});

	});
</script>


<h1>{$page_header}</h1>
	
<form name="user" id="edit" method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="users" />
	<input type="hidden" name="action" value="save_user" />
	<input type="hidden" name="id" value="{$user->public_id}" />
	<input type="hidden" name="location_public_id" value="{$current_location}">
	<input type="hidden" name="path" value="{$current_url}" />

	<table class="form">
		<tr>
			<td><strong>First Name:</strong></td>			
			<td colspan="2"><strong>Last Name:</strong></td>			
				
		</tr>
		<tr>
			<td><input type="text" name="first_name" id="first-name" value="{$user->first_name}" size="20"></td>
			<td colspan="2"><input type="text" name="last_name" id="last-name" value="{$user->last_name}" size="40"></td>
		</tr>
		<tr>
			<td colspan="2"><strong>Email:</strong></td>
			<td><strong>Phone:</strong></td>	
		</tr>
		<tr>
			<td colspan="2"><input type="text" name="email" id="email" value="{$user->email}" size="50" /></td>
			<td><input type="text" name="phone" id="phone" value="{$user->phone}"></td>
		</tr>
		<tr>
		{if $existing}
			<td>&nbsp;</td>
			<td><a href="{$SITE_URL}/?page=users&amp;action=reset_password&amp;id={$user->public_id}&amp;existing=true" class="button">Reset Password</a></td>
		{else}
			<td><strong>Password:</strong></td>
			<td colspan="2"><strong>Verify Password:</strong></td>
		</tr>
		<tr>
			<td><input type="password" name="password" id="password"></td>
			<td colspan="2"><input type="password" name="verify_password" id="verify-password"></td>
		</tr>

		{/if}


		{if $auth->is_admin()}
		<tr>
			<td colspan="3">&nbsp;</td>
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
			<td style="vertical-align: top">
			{foreach $available_locations as $k => $loc name=count}
				
				<input type="checkbox" name="additional_locations[{$k}]" id="{$loc->id}" value="{$loc->id}" {foreach $assigned_locations as $location} {if $location->id == $loc->id} checked{/if}{/foreach} /> {$loc->name}<br>
				{if $smarty.foreach.count.iteration % 10 == 0}
					</td>
					<td colspan="2" style="vertical-align:top">
				{/if}
			{/foreach}
			</td>
		</tr>

		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>

		<tr>
			<td><strong>Default Group:</strong></td>
			<td colspan="2">
				<select name="group" id="group">
					<option value="">Select a group role...</option>
					{foreach $groups as $group}
					<option value="{$group->id}" {if $group_id == $group->id} selected {/if}>{$group->description}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
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
		<tr>
			<td colspan="3"><strong>Additional Groups:</strong></td>
		</tr>
		<tr>
			<td>
				{foreach from=$groups item=group name=count}

					<input type="checkbox" name="additional_groups[{$k}]" id="{$group->id}" value="{$group->id}" {foreach $user_groups as $ug} {if $group->id == $ug->group_id} checked{/if}{/foreach} /> {$group->description}<br>
					{if $smarty.foreach.count.iteration % 6 == 0}
						</td>
						<td colspan="2" style="vertical-align:top">
					{/if}
				{/foreach}
			</td>
		</tr>

		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>		
		<tr id="module-row">
			<td><strong>Default Module:</strong></td>
			<td>
				<select name="default_module" id="user-module">
					<option value="">Select a module...</option>

					{foreach $available_modules as $mod}
					<option value="{$mod->id}" {if $default_mod== $mod->id} selected{/if}>{$mod->name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="3"><strong>Additional Modules:</strong></td>
		</tr>
		<tr>
			<td>
				{foreach from=$available_modules item=module}
					<input type="checkbox" name="additional_modules[]" value="{$module->id}" {foreach $assigned_modules as $assigned}{if $module->id == $assigned->module_id} checked{/if}{/foreach}>{$module->name}<br>
				{/foreach}
			</td>
		</tr>


		{/if}


		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td><input type="button" value="Cancel" onClick="history.go(-1);return true;"></td>
			<td colspan="5"><input class="right" type="submit" value="Save" /></td>
		</tr>
	</table>
</form>`