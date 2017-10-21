{jQueryReady}	

	$(".phone").mask("(999) 999-9999");

{/jQueryReady}


<h1 class="text-center">Edit Pharmacy</h1>

<form id="edit-hospital" action="{$SITE_URL}" method="post">
	<input type="hidden" name="page" value="pharmacy" />
	{if $isMicro}
		<input type="hidden" name="action" value="submitShadowboxEdit" />
	{else}
		<input type="hidden" name="action" value="submitEdit" />
	{/if}
	<input type="hidden" name="pharmacy" value="{$pharmacy->pubid}" />
	
	<table id="edit-data" cellpadding="5" cellspacing="5">
		<tr>
			<td><strong>Name:</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="name" value="{$pharmacy->name}" size="40" /></td>
		</tr>
		<tr>
			<td colspan="2"><strong>Address</strong></td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" name="address" value="{$pharmacy->address}" size="50" /></td>
		</tr>
		<tr>
			<td><strong>City</strong></td>
			<td><strong>State</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="city" value="{$pharmacy->city}" /></td>
			<td><input type="text" id="search-states" name="state" value="{$pharmacy->state}" /></td>
		</tr>
		<tr>
			<td><strong>Zip</strong></td>
			<td><strong>Phone</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="zip" value="{$pharmacy->zip}" /></td>
			<td><input type="text" name="phone" class="phone" value="{$pharmacy->phone}" /></td>
		</tr>
		<tr>
			<td><strong>Fax</strong></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><input type="text" name="fax" class="phone" value="{$pharmacy->fax}" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><a href="{$SITE_URL}/?page=pharmacy&action=delete&pharmacy={$pharmacy->pubid}" id="deletePharmacy" class="button">Delete</a></td>
			<td><input type="submit" value="Save" class="right" /></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><a href="{$SITE_URL}/?page=pharmacy&amp;action=manage" style="margin-right: 5px;">Cancel</a></td>
		</tr>
	</table>
</form>
