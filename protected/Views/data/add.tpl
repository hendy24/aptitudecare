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
			serviceUrl: SiteUrl,
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
<form name="add" id="add" method="post" action="{$siteUrl}">
	<input type="hidden" name="page" value="{$page}" />
	<input type="hidden" name="action" value="submitAdd" />
	<input type="hidden" name="isMicro" value="{$isMicro}" />
	<input type="hidden" name="submit" value="true" />
	<input type="hidden" name="path" value="{$current_url}" />

	<table class="form">
	{foreach $columns as $column}
	<tr>
		<td >{stringify($column)}:</td>
		<td><input {if $column == "password" || $column == "verify_password"} type="password" {else} type="text" {/if} name="{$column}" id="{$column}" style="width:200px" /></td>
	</tr>
	{/foreach}


		
