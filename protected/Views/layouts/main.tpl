<!DOCTYPE html>
<html lang="en">
<head>
	<title>{$title} &nbsp;|&nbsp; {$this->module}</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<<<<<<< HEAD
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
			integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="{$CSS}/custom.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
=======
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/jQuery-Autocomplete-master/content/styles.css" />
	<!-- <link rel="stylesheet" href="{$FRAMEWORK_CSS}/styles.css"> -->
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/jquery-ui-1.11.4.custom/jquery-ui.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/jquery-ui-1.11.4.custom/jquery-ui.theme.min.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/dropzone/dropzone.css" />
	<link rel="stylesheet" href="{$CSS}/site_styles.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/tagit/css/jquery.tagit.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/fancybox/jquery.fancybox.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/shadowbox-3.0.3/shadowbox.css" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="{$CSS}/bootstrap_styles.css">
>>>>>>> aspencreek-temp

	<script type="text/javascript" src="{$SITE_URL}/js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="{$SITE_URL}/bootstrap/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
		integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
		crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
		integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
		crossorigin="anonymous"></script>


	<script>var SITE_URL = '{$SITE_URL}';</script>

</head>
<body>
<<<<<<< HEAD
	<nav class="navbar navbar-expand-md nav-custom">
		<a class="navbar-brand" href="{$SITE_URL}?module=Dietary&amp;page=dietary&amp;action=index&amp;location={$location->public_id}"><img src="{$logo}" alt="logo"></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggler-icon"></span></button>

		{if $auth->valid()}
		<div id="user-info">
			Welcome, <a href="{$SITE_URL}/?page=users&amp;action=my_info&amp;id={$auth->getRecord()->public_id}">{$auth->fullName()}</a> &nbsp;|&nbsp; <a href="{$SITE_URL}/login/logout">Logout</a>
		</div>
		{/if}

		<div class="collapse navbar-collapse" id="navbarContent">
=======
	<div id="header-container">
		<div id="header">
			{if $auth->valid()}
			<div id="user-info">
				Welcome, <a href="{$SITE_URL}/?page=users&amp;action=my_info&amp;id={$auth->getRecord()->public_id}">{$auth->fullName()}</a> &nbsp;|&nbsp; <a href="{$SITE_URL}/login/logout">Logout</a>
			</div>
			{/if}
			<img width="200px" src="{$logo}" alt="Logo" class="logo"/>
>>>>>>> aspencreek-temp
			{if $auth->valid()}
				{$this->loadElement("navigation")}
			{/if}
		</div>
	</nav>
		
	<div class="container">
			{if $flashMessages}
			<div id="flash-messages">
				{foreach $flashMessages as $class => $message}
				<div class="{$class}">
					<ul>
					{foreach $message as $m}
						<li>{$m}</li>
					{/foreach}
					</ul>
				</div>
				<div class="clear"></div>
				{/foreach}
			</div>
			{/if}

			<div id="page-content">
				{include file=$content}
			</div>

	</div>

	</div>

	<!-- <div id="timeout-warning">
	    <p>Your session is about to timeout.  You will be automatically logged out in 1 minute. To remain logged in click the button below.</p>
	</div> -->

	{$this->loadElement("scripts")}
</body>
</html>
