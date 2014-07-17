<div id="login-box">
	<h2>Login</h2>
	<br /><br />

	<form method="post" action="<?php echo SITE_URL; ?>/user/login">
		<input type="hidden" name="page" value="user" />
		<input type="hidden" name="action" value="login" />
		<input type="hidden" name="path" value="" />
		<input type="hidden" name="submit" value="1" />
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
