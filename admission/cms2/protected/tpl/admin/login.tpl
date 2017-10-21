{jQueryReady}
$("#email").focus();
{/jQueryReady}
<div class="login">
	<form method="post" action="{$SITE_URL}">
		<input type="hidden" name="page" value="admin" />
		<input type="hidden" name="action" value="login_post" />
		<input type="hidden" name="path" value="{$path|urlencode}" />
	<table>

		<tr>
			<td><strong>Email address</strong></td>
			<td><input type="text" name="email" id="email" size="25" /></td>
		</tr>
		<tr>
			<td><strong>Password</strong></td>
			<td><input type="password" name="password" size="25" /></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><input type="submit" value="Login" /></td>
		</tr>
	</table>
	</form>
</div>