<div class="container">
	<h1>New Prospect</h1>

	<form action="{$SITE_URL}" class="form" method="post">
		<input type="hidden" name="module" value="Admissions">
		<input type="hidden" name="page" value="admissions">
		<input type="hidden" name="action" value="save-prospect">
		<input type="hidden" name="path" value="{$current_url}">

		<div class="form-group">
			<label for="first-name">First Name:</label>
			<input type="text" id="first-name" class="form-control" name="first_name" required>
		</div>
		<div class="form-group">
			<label for="last-name">Last Name:</label>
			<input type="text" id="last-name" class="form-control" name="last_name" required>
		</div>
		<div class="form-group">
			<label for="email-address">Email Address:</label>
			<input type="text" id="email-address" class="form-control" name="email_address">
		</div>
		<div class="form-group">
			<label for="phone">Phone:</label>
			<input type="text" id="phone" class="form-control" name="phone" required>
		</div>

		<div class="form-group">
			<select name="timeframe" id="timeframe" class="form-control">
				<option value="">Select a timeframe for admission...</option>
				{foreach from=$timeframe item='t'}
				<option value="{$t->id}">{$t->name}</option>
				{/foreach}
			</select>
		</div>

		<div class="row float-right my-4">
			<div class="col-md-12">
				<button type="button" class="btn btn-secondary" onClick="history.go(-1)">Cancel</button>
				<button type="submit" class="btn btn-primary">Save</button>			
			</div>
		</div>

	</form>
</div>