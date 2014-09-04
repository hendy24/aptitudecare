<script>
	$(document).ready(function() {
		$('#phone').mask("(999) 999-9999");
		$("#additional-locations").hide();

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

	});
	
</script>


<h1>Add a new {$headerTitle}</h1>
<br>
<form name="add_user" id="add-user" method="post" action="{$siteUrl}">
	<input type="hidden" name="page" value="users" />
	<input type="hidden" name="action" value="submitAdd" />
	<input type="hidden" name="submit" value="true" />
	<input type="hidden" name="path" value="{$current_url}" />

	<table class="form">
		
	{foreach $columns as $column}
		<tr>
			<td >{stringify($column)}:</td>
			<td><input {if $column == "password" || $column == "verify_password"} type="password" {else} type="text" {/if} name="{$column}" id="{$column}" style="width:250px" /></td>
		</tr>
	{/foreach}
	
	<tr>
		<td>Default Location:</td>
		<td>
			<select name="default_location" id="default_location">
				<option value="">Select a location...</option>
				{foreach $available_locations as $loc}
				<option value="{$loc->id}">{$loc->name}</option>
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
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td><input type="button" value="Cancel" onClick="history.go(-1);return true;"></td>
		<td><input class="right" type="submit" value="Save" /></td>
	</tr>
		
	</table>
</form>
