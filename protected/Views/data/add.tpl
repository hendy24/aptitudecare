<script>
	$(document).ready(function() {
		$("#phone").mask("(999) 999-9999");
		$("#fax").mask("(999) 999-9999");
		$("#zip").mask("99999");

		{$states = getUSAStates()}
		var states = [
		{foreach $states as $abbr => $state}
		{if $state != ''}
			{
				value: "{$state} ({$abbr})",
				data: "{$abbr}"
			}
			{if $state@last != true},{/if}
		{/if}
		{/foreach}
		];


		$("#state").autocomplete({
			lookup: states,
			onSelect: function (suggestion) {
				$("#state").val(suggestion.data);
			}
		});

		$("#healthcare-facility-search").autocomplete({
			serviceUrl: SITE_URL,
			params: { 
				module: 'HomeHealth',
				page: 'HealthcareFacilities',
				action: 'searchFacilityName'
				//location: $("#admit-request-location option:selected").val() 
			}, minChars: 3,
			onSelect: function (suggestion) {
				$("#healthcare-facility-id").val(suggestion.data);
			}

		});

		$("#add").validate({
			rules: {
				first_name: "required",
				last_name: "required",
				phone: "required",
				email: "email",
				city: "required",
				state: "required",
				zip: "required",
				healthcare_facility: "required"
			}
		}); 

	});
</script>

<h1>Add a new {$headerTitle}</h1>
<br>
<form name="add" id="add" method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="{$page}" />
	<input type="hidden" name="action" value="submitAdd" />
	<input type="hidden" name="isMicro" value="{$isMicro}" />
	<input type="hidden" name="submit" value="true" />
	<input type="hidden" name="path" value="{$current_url}" />

	<table class="form">
	{foreach $columns as $k => $column}
	<tr>
		<td >{stringify($k)}:</td>
		<td><input {if $k == "password" || $k == "verify_password"} type="password" {else} type="text" {/if} name="{$k}" id="{$k}" style="width:200px" /></td>
	</tr>
	{/foreach}


		
