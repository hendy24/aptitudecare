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

	});
</script>

<form name="edit" id="edit" method="post" action="{$siteUrl}">
	<input type="hidden" name="page" value="{$page}" />
	<input type="hidden" name="action" value="submitAdd" />
	<input type="hidden" name="id" value="{$id}" />
	<input type="hidden" name="submit" value="true" />
	<input type="hidden" name="path" value="{$current_url}" />

	<table class="form">
	{foreach $dataArray as $key => $data}
	<tr>
		<td >{stringify($key)}:</td>
		<td><input {if $key == "password"} type="password" {else} type="text" {/if} name="{$key}" id="{$column}" value="{$data}" style="width:200px" /></td>
	
		{if $key == "password"}
			<td><a href="{$siteUrl}/?page=users&amp;action=reset_password&amp;id={$public_id}" class="button">Reset Password</a></td>
		{/if}
	</tr>
	{/foreach}
