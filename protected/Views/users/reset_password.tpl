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
</script>


<h1>Reset Password <br><span class="text-16">for</span><br><span class="text-22 font-normal">{$user->fullName()}</span></h1>

<form action="{$siteUrl}" method="post" id="password-change">
	<input type="hidden" name="page" value="users" />
	<input type="hidden" name="action" value="reset_password" />
	<input type="hidden" name="id" value="{$user->public_id}" />
	<input type="hidden" name="current_url" value="{$current_url}" />

	<table class="form">
		<tr>
			<td>Password:</td>
			<td><input type="password" name="password" id="password" required /></td>
		</tr>
		<tr>
			<td>Verify Password:</td>
			<td><input type="password" name="password2" id="password2" required /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" class="text-right"><input type="submit" value="Save" /></td>
		</tr>
	
	</table>
	
</form>

