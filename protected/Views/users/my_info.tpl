<h1>Edit My Account</h1>
<script>
	$(document).ready(function() {
		$("#password-change").validate({
			rules: {
				password: "required",
				password2: {
					equalTo: "#password",
					required: true
				}
			}
		});
	});

	});
</script>


<form name="user" id="edit" method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="users" />
	<input type="hidden" name="action" value="save_my_info" />
	<input type="hidden" name="path" value="{$current_url}" />

	<table class="form">
		<tr>
			<td><strong>First Name:</strong></td>			
			<td colspan="2"><strong>Last Name:</strong></td>			
				
		</tr>
		<tr>
			<td><input type="text" name="first_name" id="first-name" value="{$user->first_name}" size="20"></td>
			<td colspan="2"><input type="text" name="last_name" id="last-name" value="{$user->last_name}" size="40"></td>
		</tr>
		<tr>
			<td colspan="2"><strong>Email:</strong></td>
			<td><strong>Phone:</strong></td>	
		</tr>
		<tr>
			<td colspan="2"><input type="text" name="email" id="email" value="{$user->email}" size="50" /></td>
			<td><input type="text" name="phone" id="phone" value=""></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><a href="{$SITE_URL}/?page=users&amp;action=reset_password&amp;id={$user->public_id}&amp;existing=true" class="button">Reset Password</a></td>

		</tr>

		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td><input type="button" value="Cancel" onClick="history.go(-1);return true;"></td>
			<td colspan="5"><input class="right" type="submit" value="Save" /></td>
		</tr>

	</table>
</form>
