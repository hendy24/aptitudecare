{setTitle title="Account Information"}
<h2 class="text-center">Account Information</h2>

<form method="post" action="{$SITE_URL}">
<input type="hidden" name="page" value="userInfo" />
<input type="hidden" name="action" value="submitUserInfo" />
	<table>
		<tr>
			<td align="right">First Name: </td>
			<td><input type="text" name="first" value="{$auth->getRecord()->first}" size="30" /></td>
		</tr>
		<tr>
			<td align="right">Last Name: </td>
			<td><input type="text" name="last" value="{$auth->getRecord()->last}" size="30" /></td>
		</tr>
		<tr>
			<td align="right">Phone: </td>
			<td><input type="text" name="phone" value="{$auth->getRecord()->phone}" size="30" /></td>
		</tr>
		<tr>
			<td align="right">Default Facility: </td>
			<td>
				<select name="facility">
				{foreach $facilities as $facility}
					<option value="{$facility->pubid}" {if $defaultFacility->name == $facility->name}selected="selected"{/if} value="">{$facility->name}</option>
				{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>To change your password please enter it twice below. <br />
				Leave blank if you wish for your password to remain <br />
				the same.</td>
		</tr>
		<tr>
			<td align="right">Password: </td>
			<td><input type="password" name="password1" size="30" /></td>
		</tr>
		<tr>
			<td align="right">Re-enter Password:</td>
			<td><input type="password" name="password2" size="30" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Save" /></td>
		</tr>

	</table>
</form>
