<h1 class="text-center">Reset Password</h1>

<form action="{$SITE_URL}" method="post">
	<input type="hidden" name="page" value="siteUser" />
	<input type="hidden" name="action" value="submitResetPassword" />
	<input type="hidden" name="user" value="{$user->pubid}" />
	
	<table id="edit-data" cellpadding="5" cellspacing="5">
		<tr>
			<td>New Password:</td>
			<td><input type="password" value="{$user->password}" name="password1" /></td>
		</tr>
		<tr>
			<td>Verify Password:</td>
			<td><input type="password" value="{$user->password}" name="password2" /></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><input type="submit" value="Save" /></td>
		</tr>
	</table>

</form>