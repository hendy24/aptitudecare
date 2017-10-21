<table>
	<h2>Profile: {$admin_auth->getRecord()->fullname}</h2>
	<br />
	<form method="post" action="{$SITE_URL}">
		<input type="hidden" name="page" value="admin" />
		<input type="hidden" name="action" value="submitProfile" />
		<input type="hidden" name="id" value="{$admin_auth->getRecord()->id}" />
		
		
		<tr>
			<td>Email/Login:</td>
			<td><input type="text" size=30 autocomplete=off name="email" value="{$admin_auth->getRecord()->email}" /> (this is also your username for logging into this Web Content Administration system)</td>			
		</tr>
		<tr>
			<td>Full Name:</td>
			<td><input type="text" size=30 autocomplete=off name="fullname" value="{$admin_auth->getRecord()->fullname}" /></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input size=30 type="password" name="password" /> (only if you want to change your password)</td>			
		</tr>
		<tr>
			<td colspan=2 align=right>
				<input type=submit value="Save" />
			</td>
		</tr>
		
	</form>
	
	
</table>