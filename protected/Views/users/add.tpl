<script>
	$(document).ready(function() {
		$('#phone').mask("(999) 999-9999");

		$("#additional-locations").hide();

		$("#module-row").hide();

		$("#add-user").validate({
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

		$("#default_location").change(function() {
			$("#additional-locations").show();
			var location = $(this).val();
			$("#" + location).prop("checked", true);

		});


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
			//	Get the modules available for the selected group type
			$.post(SITE_URL, { page: "users", action: "fetchModulesByGroup", group: $("option:selected", this).val() }, function (e) {
				var count = Object.keys(e).length;
				if (count > 1) {
					$(".module-option").remove();
				} else {
					$("#user-module option").remove();
				}
				
				$.each(e, function (i, d) {
					$("#user-module").append("<option class=\"module-option\" value=\"" + d.id + "\">" + d.name + "</option>"); 
				});
				$("#module-row").show();
			
			},
			"json"
			);

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


<h1>Add a new User</h1>
<br>
<form name="add_user" id="add-user" method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="users" />
	<input type="hidden" name="action" value="submit_add" />
	<input type="hidden" name="submit" value="true" />
	<input type="hidden" name="path" value="{$current_url}" />
	<input type="hidden" name="location_public_id" value="{$location_id}" />

	<table class="form">
		
		<tr>
			<td>First Name:</td>
			<td><input type="text" name="first_name" id="first-name"></td>
			
		</tr>
		<tr>
			<td>Last Name:</td>
			<td><input type="text" name="last_name" id="last-name" style="width: 200px"></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><input type="text" name="email" id="email" style="width: 200px"></td>	
		</tr>
		<tr>
			<td>Phone:</td>
			<td><input type="text" name="phone" id="phone" style="width: 95px"></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type="password" name="password" id="password"></td>
		</tr>
		<tr>
			<td>Verify Password:</td>
			<td><input type="password" name="verify_password" id="verify-password"></td>
		</tr>
		<tr>
			<td colspan="2" class="text-right"><input type="checkbox" name="temp_password" value="true"> Temporary Password</td>
		</tr>
		<tr>
			<td>Default Location:</td>
			<td>
				<select name="default_location" id="default_location">
					<option value="">Select a location...</option>
					{foreach $available_locations as $loc}
					<option value="{$loc->id}" {if $location_id == $loc->public_id} selected{/if}>{$loc->name}</option>
					{/foreach}
				</select>

			</td>
		</tr>
		<tr id="additional-locations">
			<td style="vertical-align:top">Additional Locations:</td>
			<td>
			{foreach $available_locations as $k => $loc}
				<input type="checkbox" name="user_location[{$k}]" id="{$loc->id}" value="{$loc->id}" /> {$loc->name}<br>
			{/foreach}
			</td>
		</tr>
		<tr>
			<td>Group:</td>
			<td>
				<select name="group" id="group">
					<option value="">Select a group role...</option>
					{foreach $groups as $group}
					<option value="{$group->id}">{$group->description}</option>
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
		<tr id="module-row">
			<td>Default Module:</td>
			<td>
				<select name="default_module" id="user-module">
					<option value="">Select a module...</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><strong>Additional Modules:</strong></td>
			<td>
				{foreach from=$available_modules item=module}
					<input type="checkbox" name="modules[]" value="{$module->id}">{$module->name}<br>
				{/foreach}
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
