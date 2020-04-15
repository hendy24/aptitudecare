<div class="container">
	<h1>Edit Menu</h1>

	<form action="{$SITE_URL}" method="post">
		<input type="hidden" name="page" value="info">
		<input type="hidden" name="action" value="edit_menu">
		<input type="hidden" name="menu" value="{$menu->public_id}">
		<input type="hidden" name="current_url" value="{$current_url}">

		<div class="form-group">
			<label for="menu-name">Menu Name:</label>
			<input id="menu-name" class="form-control" type="text" name="name" value="{$menu->name}">
		</div>
		<div class="row text-right">
			<div class="col-12">
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</div>
	</form>
</div>