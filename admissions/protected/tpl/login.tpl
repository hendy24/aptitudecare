{setTitle title="Census Dashboard"}

<div id="login-box">
	<h2>Login</h2>
	<br /><br />

	<form method="post" action="{$SITE_URL}">
		<input type="hidden" name="page" value="login" />
		<input type="hidden" name="action" value="login" />
		<input type="hidden" name="path" value="{$path|urlencode}" />
		{formhistory_on name="login"}
		<table>
			<tr>
				<td>Username:</td>
				<td><input type="text" name="email" value="" id="login_username" /></td>
			</tr>
			{if $site_email}
			<tr>
				<td>&nbsp;</td>
				<td style="text-align: right">{$site_email}</td>
			</tr>
			{/if}
			
			<tr>
				<td>Password:</td>
				<td><input type="password" name="password" /></td>
			</tr>
			<tr>
				<td colspan="2" align="right"><input type="submit" value="Login" style="margin-top: 10px;" /></td>
			</tr>

		</table>
	</form>
</div>
