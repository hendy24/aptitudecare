<h1>Edit Menu</h1>

<form action="{$SITE_URL}" method="post">
	<input type="hidden" name="page" value="info">
	<input type="hidden" name="action" value="edit_menu">
	<input type="hidden" name="menu" value="{$menu->public_id}">
	<input type="hidden" name="current_url" value="{$current_url}">

	<table class="form">
		<tr>
			<td>Menu Name:</td>
			<td><input type="text" name="name" value="{$menu->name}"></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" class="text-right"><input type="submit" value="Save"></td>
		</tr>
	</table>
</form>