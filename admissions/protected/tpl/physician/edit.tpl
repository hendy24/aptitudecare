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
			return false;
		},
		select: function( event, ui ) {
			$( "#search-states" ).val( ui.item.label );
			$( "#state_id" ).val( ui.item.value );
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.label + "</a>" )
		.appendTo( ul );
	};
	
$(".phone").mask("(999) 999-9999");
$(".fax").mask("(999)-999-9999");

{/jQueryReady}


<h1 class="text-center">Edit Physician/Surgeon</h1>

<form id="edit-physician" action="{$SITE_URL}" method="post">
	<input type="hidden" name="page" value="physician" />
	{if $isMicro}
		<input type="hidden" name="action" value="submitShadowboxEdit" />
	{else}
		<input type="hidden" name="action" value="submitEdit" />
	{/if}
	<input type="hidden" name="physician" value="{$p->pubid}" />
	
	<table id="edit-data" cellpadding="5" cellspacing="5">
		<tr>
			<td><strong>First Name:</strong></td>
			<td><strong>Last Name:</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="first_name" id="first_name" value="{$p->first_name}" /></td>
			<td><input type="text" name="last_name" id="last_name" value="{$p->last_name}" /></td>
		</tr>
		<tr>
			<td colspan="2"><strong>Address</strong></td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" size="50" name="address" value="{$p->address}" /></td>
		</tr>
		<tr>
			<td><strong>City</strong></td>
			<td><strong>State</strong></td>
		<tr>
			<td><input type="text" name="city" value="{$p->city}" /></td>
			<td><input type="text" name="state" id="search-states" value="{$p->state}" /></td>
		</tr>
		<tr>
			<td><strong>Zip</strong></td>
			<td><strong>Phone</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="zip" value="{$p->zip}" /></td>
			<td><input type="text" name="phone" class="phone" value="{$p->phone}" /></td>
		</tr>
		<tr>
			<td><strong>Fax</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="fax" value="{$p->fax}" class="phone" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><a href="{$SITE_URL}/?page=physician&action=delete&physician={$p->pubid}" id="deleteCM" class="button">Delete</a></td>
			<td><input type="submit" value="Save" class="right" /></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><a href="{$SITE_URL}/?page=physician&amp;action=manage" style="margin-right: 5px;">Cancel</a></td>
		</tr>
	</table>
</form>
