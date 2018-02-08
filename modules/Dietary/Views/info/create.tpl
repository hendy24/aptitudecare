<h1>Create a New Menu</h1>

<form action="{$SITE_URL}">
	<input type="hidden" name="page" value="info">
	<input type="hidden" name="action" value="save_create">
	<input type="hidden" name="current_url" value="{$current_url}">

	<table class="form">
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>

		<tr>
			<td><strong>Menu Name:</strong></td>
			<td><input type="text" name="menu_name" size="50"></td>
		</tr>
		<tr>
			<td><strong>Number of weeks in menu:</strong></td>
			<td>
				<select name="num_weeks" id="num-weeks">
					<option value="">Select...</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td class="text-right" colspan="2"><input type="submit" value="Create Menu"></td>
		</tr>
	</table>
</form>