{setTitle title="Add a new User"}
<script src="{$SITE_URL}/js/jquery-validation-1.12.0/dist/jquery.validate.min.js"></script>
<script src="{$SITE_URL}/js/form-validation.js"></script>

{jQueryReady}
	$('.phone').mask('(999) 999-9999');
	
{/jQueryReady}
<h1 class="text-center">Add a New User</h1>

<form action="{$SITE_URL}" method="post" id="newUser">
	<input type="hidden" name="page" value="siteUser" />
	<input type="hidden" name="action" value="submitAddUser" />
	
	<table id="edit-data" cellpadding="5" cellspacing="5">
		<tr>
			<td><label for="first_name">First Name:</label></td>
			<td><input type="text" name="first" id="first_name" size="30" /></td>
		</tr>
		<tr>
			<td><label for="last_name">Last Name:</label></td>
			<td><input type="text" name="last" id="last_name" size="50" /></td>
		</tr>
		<tr>
			<td><label for="username">Username (Email Address):</label></td>
			<td><input type="text" name="email" id="username" size="50" /></td>
		</tr>
		<tr>
			<td><label for="new-password">New Password:</label></td>
			<td><input type="password" value="{$user->password}" id="password" name="password" /></td>
		</tr>
		<tr>
			<td><label for="verify-password">Verify Password:</label></td>
			<td><input type="password" value="{$user->password}" id="confirm_password-password" name="confirm_password" /></td>
		</tr>
		<tr>
			<td><label for="phone">Phone:</label></td>
			<td><input type="text" name="phone" size="10" id="phone" class="phone" /></td>
		</tr>
		<tr>	
			<td><label for="facility">Facility:</label></td>
			<td>
				<select name="facility" id="facility">
					<option value="">Select a facility...</option>
					{foreach $facilities as $f}
						<option value="{$f->pubid}">{$f->name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input id="is-coordinator" type="checkbox" name="is_coordinator" value="1"> Is an Admissions Coordinator</td>
		</tr>
		<tr>
			<td colspan="2" align="right"><input type="submit" value="Add User" /></td>
		</tr>


	</table>
	
</form>