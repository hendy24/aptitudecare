{jQueryReady}
	$('.phone').mask('(999) 999-9999');
	
	$('#deleteUser').click(function() {
		if (confirm('Are you sure you want to delete this user?  This cannot be undone.')) {
			window.location = {$SITE_URL} + '/?page=siteUser&action=delete&user=' + {$user->pubid};
		}
		return false;
	});
{/jQueryReady}

<h1 class="text-center">{$facility->name}</h1>
<h2 class="text-center">Edit User Info for {$user->fullName()}</h2>

<form name="edit_user" action="{$SITE_URL}" method="post" id="edit-user">
	<input type="hidden" name="page" value="siteUser" />
	<input type="hidden" name="action" value="submitEdit" />
	<input type="hidden" name="user" value="{$user->pubid}" />
	<input type="hidden" name="facility" value="{$facility->pubid}" />
<table id="edit-data" cellspacing="5" cellpadding="5">
	<tr>
		<td>First Name:</td>
		<td><input type="text" value="{$user->first}" size="30" name="first" /></td>
	</tr>
	<tr>
		<td>Last Name:</td>
		<td><input type="text" value="{$user->last}" size="50" name="last" /></td>
	</tr>
	<tr>
		<td>Username (Email Address):</td>
		<td><input type="text" value="{$user->email}" size="40" name="email" /></td>
	</tr>
	<tr>
		<td>Phone:</td>
		<td><input type="text" value="{$user->phone}" size="10" class="phone" name="phone" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input id="is-coordinator" type="checkbox" name="is_coordinator" value="1" {if $user->is_coordinator} checked{/if}> Is an Admissions Coordinator</td>
	</tr>
	<tr>
		<td>User Role:</td>
		<td>
			<select name="user_role">
				<option value="">Select a user role...</option>
				{foreach $roles as $role}
					<option value="{$role->id}" {if $user->role == $role->id} selected{/if}>{$role->description}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><a href="{$SITE_URL}/?page=siteUser&action=reset_password&user={$user->pubid}">Reset Password</a></td>
	</tr>
	<tr>
		<td><a href="{$SITE_URL}/?page=siteUser&action=delete&user={$user->pubid}" id="deleteUser" class="button">Delete</a></td>
		<td align="right"><input type="submit" value="Save" /></td>
	</tr>
	<tr>	
		<td colspan="2" align="right"><a href="{$SITE_URL}/?page=siteUser&action=manage&facility={$facility->pubid}" style="margin-right: 5px;">Cancel</a></td>
	</tr>
</form>
</table>