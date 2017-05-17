
<div class="wrapper">
	 <form method="post" class="form-signin" action="{$SITE_URL}/login">
		 <h2 class="form-signin-heading">Login</h2>
			 <input type="hidden" name="path" value="{$current_url}" />
			 <input type="hidden" name="submit" value="1" />
			 <input type="text" class="form-control" name="email" placeholder="Email Address" required="true" autofocus="" />
			 <div class="pull-right">@ahcfacilities.com</div>
			 <input type="password" class="form-control" name="password" placeholder="Password" required="true"/>
			 <br>
			 <input type="submit" class="btn btn-lg btn-primary btn-block" value="Login">
	 </form>
 </div>
