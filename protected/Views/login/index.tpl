<div class="container-sm">
	<h1>Login</h2>
	 <form method="post" class="form-signin" action="{$SITE_URL}/login">
		<div class="form-group">
			<input type="hidden" name="path" value="{$current_url}">
		 	<input type="hidden" name="submit" value="1">
			<label for="email">Email Address</label>
			<input type="email" class="form-control" name="email" required="true">
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" class="form-control" name="password" required="true">
		</div>

		<button type="submit" class="btn btn-primary float-right">Submit</button>
	 </form>

</div>

