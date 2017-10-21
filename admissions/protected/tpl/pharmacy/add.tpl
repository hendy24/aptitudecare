{setTitle title="Add a Pharmacy"}
<script src="{$SITE_URL}/js/jquery-validation-1.12.0/dist/jquery.validate.min.js"></script>
<script src="{$SITE_URL}/js/form-validation.js"></script>

{jQueryReady}

	{$states = getUSAStates()}
	var states = [
	{foreach $states as $abbr => $state}
	{if $state != ''}
		{
			value: "{$abbr}",
			label: "({$abbr}) {$state}"
		}
		{if $state@last != true},{/if}
	{/if}
	{/foreach}
	];

	$("#state_name").autocomplete(
		{
			minLength: 0,
			source: states,
			focus: function( event, ui ) {
				$( "#state_name" ).val( ui.item.label );
				return false;
			},
			select: function( event, ui ) {
				$( "#state_name" ).val( ui.item.label );
				$( "#state_id" ).val( ui.item.value );
				return false;
			}
		}).data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.label + "</a>" )
			.appendTo( ul );
		};
		
	$(".phone").mask("(999)-999-9999");
	$(".fax").mask("(999)-999-9999");

{/jQueryReady}
<div class="lightbox">
	<h1>Add a new Pharmacy Location</h1>
	<form name="location" method="post" action="{$SITE_URL}" id="addItem">
		<input type="hidden" name="page" value="pharmacy" />
		<input type="hidden" name="state" value="{$state}" />
		{if $isMicro}
			<input type="hidden" name="action" value="addShadowboxLocation" />
		{else}
			<input type="hidden" name="action" value="addLocation" />
		{/if}
		<table id="edit-data" cellpadding="5" cellspacing="5">
			<tr>
				<td colspan="3"><label for="pharmacy">Pharmacy Name:</label></td>
			</tr>
			<tr>
				<td colspan="2"><input type="text" name="location_name" id="pharmacy" size="60" /></td>
			</tr>
			<tr>
				<td><label for="address">Address</strong></td>
			</tr>
			<tr>
				<td colspan="3"><input type="text" name="address" size="60" /></td>
			</tr>
			<tr>
				<td><label for="city">City</label></td>
				<td><label for="state">State</label></td>
				<td><label for="zip">Zip</label></td>
			</tr>
			<tr>
				<td><input type="text" name="city" name="city" size="20" /></td>
				<td><input type="text" id="state_name" name="state_name" size="15" /></td>
				<input type="hidden" name="state_id" id="state_id" />
				<td><input type="text" name="zip" id="zip" size="8" /></td>
			</tr>
			<tr>
				<td><label for="phone">Phone</label></td>
				<td><label for="fax">Fax</label></td>
			</tr>
			<tr>
				<td><input type="text" name="phone" id="phone" class="phone" /></td>
				<td><input type="text" name="fax" id="fax" class="phone" /></td>
			</tr>
			<tr>
				<td colspan="3"><input type="submit" value="Save" class="right" /></td>
			</tr>
		</table>
	</form>
</div>