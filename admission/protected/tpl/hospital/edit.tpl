{jQueryReady}	

	$(".phone").mask("(999) 999-9999");

{/jQueryReady}


<h1 class="text-center">Edit Facility</h1>

<form id="edit-hospital" action="{$SITE_URL}" method="post">
	<input type="hidden" name="page" value="hospital" />
	<input type="hidden" name="action" value="submitEdit" />
	<input type="hidden" name="hospital" value="{$hospital->pubid}" />
	
	<table id="edit-data" cellpadding="5" cellspacing="5">
		<tr>
			<td><strong>Name:</strong></td>
			<td><strong>Type:</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="name" value="{$hospital->name}" size="40" /></td>
			<td>
				<select name="type">
					<option value="">Select type...</option>
					{foreach $locationTypes as $type}
						<option value="{$type}" {if $hospital->type == $type} selected{/if}>{$type}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2"><strong>Address</strong></td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" name="address" value="{$hospital->address}" size="50" /></td>
		</tr>
		<tr>
			<td><strong>City</strong></td>
			<td><strong>State</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="city" value="{$hospital->city}" /></td>
			<td><input type="text" id="search-states" name="state" value="{$hospital->state}" /></td>
		</tr>
		<tr>
			<td><strong>Zip</strong></td>
			<td><strong>Phone</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="zip" value="{$hospital->zip}" /></td>
			<td><input type="text" name="phone" class="phone" value="{$hospital->phone}" /></td>
		</tr>
		<tr>
			<td><strong>Fax</strong></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><input type="text" name="fax" class="phone" value="{$hospital->fax}" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><a href="{$SITE_URL}/?page=hospital&action=delete&hospital={$hospital->pubid}" id="deleteHospital" class="button">Delete</a></td>
			<td><input type="submit" value="Save" class="right" /></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><a href="{$SITE_URL}/?page=hospital&amp;action=manage" style="margin-right: 5px;">Cancel</a></td>
		</tr>
	</table>
</form>
