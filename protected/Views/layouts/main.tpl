<!DOCTYPE html>
<html lang="en">
<head>
	<title>{$title} &nbsp;|&nbsp; {$this->module}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="{$FRAMEWORK_JS}/jQuery-Autocomplete-master/content/styles.css" />
	<!-- <link rel="stylesheet" href="{$FRAMEWORK_CSS}/styles.css"> -->
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/jquery-ui-1.11.4.custom/jquery-ui.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/jquery-ui-1.11.4.custom/jquery-ui.theme.min.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/dropzone/dropzone.css" />
	<link rel="stylesheet" href="{$CSS}/site_styles.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/tagit/css/jquery.tagit.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/fancybox/jquery.fancybox.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/shadowbox-3.0.3/shadowbox.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="{$CSS}/bootstrap_styles.css">

	<!-- Bootstrap scripts -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
<!-- Older JS scripts -->
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
	{if $this->module == "HomeHealth"}
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jQuery-Autocomplete-master/dist/jquery.autocomplete.min.js"></script>
	{/if}
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery-validation-1.13.0/dist/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery.maskedinput.min.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/datepicker.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery-ui-timepicker-0.3.3/jquery.ui.timepicker.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery.row-grid.min.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/dropzone/dropzone.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/fancybox/jquery.fancybox.pack.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/gridify/gridify-min.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/gridify/require.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/shadowbox-3.0.3/shadowbox.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/fancybox/helpers/jquery.fancybox-buttons.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/tagit/js/tag-it.min.js"></script>


	<link rel="stylesheet" href="{$FRAMEWORK_JS}/fancybox/helpers/jquery.fancybox-buttons.css" />

	<script>
		var SITE_URL = '{$SITE_URL}';
		Shadowbox.init({
			height: 425,
			width: 450,
			handleOversize: "resize",
			overlayColor: "#666",
			overlayOpacity: "0.25"
		});
	</script>

	<script type="text/javascript" src="{$JS}/general.js"></script>

	{if $auth->valid() && $auth->getRecord()->timeout}
		<script type="text/javascript" src="{$JS}/timeout.js"></script>

		<script>
			$(document).ready(function() {
				startTimer();
			});
		</script>
	{/if}
</head>
<body>
	<nav class="navbar navbar-expand-sm bg-light">
		{if $auth->valid()}
			{$this->loadElement("navigation")}
		{/if}
	</nav>
	
	<!--
	<div id="header-container">
		<div id="header">
			{if $auth->valid()}
			<div id="user-info">
				Welcome, <a href="{$SITE_URL}/?page=users&amp;action=my_info&amp;id={$auth->getRecord()->public_id}">{$auth->fullName()}</a> &nbsp;|&nbsp; <a href="{$SITE_URL}/login/logout">Logout</a>
			</div>
			{/if}
			<img src="{$logo}" alt="Logo" class="logo"/>
			{if $auth->valid()}
				{$this->loadElement("navigation")}
			{/if}
		</div>
	</div>
	-->
	<div class="clear"></div>
	
	<div class="container">
		<div id="content">
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
<!-- 		<div id="copyright">
			<p>All content &copy; {$smarty.now|date_format:"%Y"} AptitudeCare.  All rights reserved. Powered by <a href="http://www.aptitudeit.net" target="_blank">aptITude</a></p>
		</div>
	</div>-->

	</div>

	<div id="timeout-warning">
	    <p>Your session is about to timeout.  You will be automatically logged out in 1 minute. To remain logged in click the button below.</p>
	</div>

</body>
</html>
