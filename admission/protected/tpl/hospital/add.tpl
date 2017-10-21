{setTitle title="Add a Healthcare Location"}
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

$("#search-states").autocomplete(
	{
		minLength: 0,
		source: states,
		focus: function( event, ui ) {
			$( "#search-states" ).val( ui.item.label );
			$( "#state" ).val( ui.item.value );
			return false;
		},
		select: function( event, ui ) {
			$( "#search-states" ).val( ui.item.label );
			$( "#state" ).val( ui.item.value );
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.label + "</a>" )
		.appendTo( ul );
		console.log($("#search-states").val());
	};
	
	
{/jQueryReady}
<div class="lightbox">
	<h1>Add a new Location</h1>
	<form name="location" method="post" action="{$SITE_URL}" id="addItem">
		<input type="hidden" name="page" value="hospital" />
		<input type="hidden" name="action" value="addLocation" />
		<input type="hidden" name="_path" value="{urlencode(currentURL())}" />
		{if $isMicro}
			<input type="hidden" name="isMicro" value="1" />
		{/if}
		<table id="info-data" cellpadding="5" cellspacing="5">
			<tr>
				<td colspan="2"><label for="location_name">Location Name:</label></td>
				<td><label for="location_type">Location Type:</label<label></td>
			</tr>
			<tr>
				<td colspan="2"><input type="text" name="location_name" size="50" /></td>
				<td>
					<select id="location_type" name="type">
						<option value="">Select type...</option>
						{foreach $locationTypes as $type}
							<option value="{$type}" {if $type == $inputType} selected{/if}>{$type}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="">Address</label<label></td>
			</tr>
			<tr>
				<td colspan="3"><input type="text" name="address" size="50" /></td>
			</tr>
			<tr>
				<td><label for="city">City</label<label></td>
				<td><label for="state_name">State</label<label></td>
				<td><label for="zip">Zip</label<label></td>
			</tr>
			<tr>
				<td><input type="text" name="city" size="20" /></td>
				<td><input type="text" id="search-states" name="state_name" size="15" /></td>
				<input type="hidden" name="state" id="state" />
				<td><input type="text" name="zip" id="zip" size="8" /></td>
			</tr>
			<tr>
				<td><label for="phone">Phone</label<label></td>
				<td><label for="fax">Fax</label<label></td>
			</tr>
			<tr>
				<td><input type="text" id="phone" name="phone" /></td>
				<td><input type="text" id="fax" name="fax" /></td>
			</tr>
			<tr>
				<td colspan="3"><input type="submit" value="Save" class="right" /></td>
			</tr>
		</table>
	</form>
</div>