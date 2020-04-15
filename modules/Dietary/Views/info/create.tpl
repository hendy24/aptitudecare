<h1>Create a New Menu</h1>

<form action="{$SITE_URL}" class="form">
	<input type="hidden" name="page" value="info">
	<input type="hidden" name="action" value="save_create">
	<input type="hidden" name="current_url" value="{$current_url}">

	
	<div class="form-group">
		<div class="col-12">
			<strong>Menu Name:</strong>
			<input type="text" class="form-control" name="menu_name" size="50">
		</div>
	</div>

	<div class="form-group">
		<div class="col-12">
			<strong>Number of weeks in menu:</strong>
			<select name="num_weeks" id="num-weeks" class="form-control">
				<option value="">Select...</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
			</select>
		</div>
	</div>

	<div class="row mt-4 text-right">
		<div class="col-12"><button class="btn btn-primary" type="submit">Create Menu</button></div>
	</div>
</form>