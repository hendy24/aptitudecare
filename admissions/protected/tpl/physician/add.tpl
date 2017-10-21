{setTitle title="Add new Physician"}
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
	<h1>Add a new {$physicianType|capitalize|default: 'Physician'}</h1>
	<form name="physician" method="post" action="{$SITE_URL}" id="addItem" class="ui-widget ui-corner-all">
		<input type="hidden" name="page" value="physician" />
		{if $isMicro}
			<input type="hidden" name="action" value="addShadowboxPhysician" />
		{else}
			<input type="hidden" name="action" value="addPhysician" />
		{/if}
		<table id="edit-data" cellspacing="5" cellpadding="5">
			<tr>
				<td><label for="first-name">First Name</label></td>
				<td colspan="2"><label for="last-name">Last Name</label></td>
			</tr>
			
			<tr>
				<td><input type="text" name="first_name" id="first-name" title="Enter a first name" /></td>
				<td colspan="2"><input type="text" name="last_name" id="last-name" title="Enter a last name" size="50px" /></td>
			</tr>
			<tr>
				<td colspan="3"><label for="address">Address</label></td>
			</tr> 
			<tr>
				<td colspan="3"><input type="text" name="address" id="address" size="80" /></td>
			</tr>
			<tr>
				<td><label for="city">City</label></td>
				<td><label for="state">State</label></td>
				<td><label for="zip">Zip</label></td>
			</tr>
			<tr>
				<td><input type="text" name="city" id="city" size="20" /></td>
				<td><input type="text" id="state_name" name="state_name" size="16" /></td>
				<input type="hidden" name="state_id" id="state_id" />
				<td><input type="text" name="zip" id="zip" size="8"  /></td>
			</tr>
			<tr>
				<td><label for="phone">Phone</label></td>
				<td><label for="fax">Fax</label></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><input type="text" name="phone" id="phone" class="phone" /></td>
				<td><input type="text" name="fax" id="fax" class="fax" /></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3"><input type="submit" name="submit" value="Save" class="right" /></td>
			</tr>

		</table>
	</form>
</div>
