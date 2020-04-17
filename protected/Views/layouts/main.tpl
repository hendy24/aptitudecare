<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{$title} &nbsp;|&nbsp; {$this->module}</title>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- CSS Files -->
		<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.css" rel="stylesheet">
		<link rel="stylesheet" href="{$CSS}/custom.css">
		<link rel="stylesheet" href="{$VENDORS}/selectize/dist/css/selectize.default.css">
		<link rel="stylesheet" href="{$VENDORS}/datepicker/lib/themes/default.css">
		<link rel="stylesheet" href="{$VENDORS}/datepicker/lib/themes/default.date.css">
		<link rel="stylesheet" href="{$VENDORS}/datepicker/lib/themes/default.time.css">
	
		<script src="{$JS}/jquery-3.4.1.min.js" type="text/javascript"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script type="text/javascript" src="{$VENDORS}/datepicker/lib/picker.js"></script>
		<script type="text/javascript" src="{$VENDORS}/datepicker/lib/picker.date.js"></script>
		<script type="text/javascript" src="{$VENDORS}/datepicker/lib/picker.time.js"></script>
		<script type="text/javascript" src="{$VENDORS}/microplugin/src/microplugin.js"></script>
		<script type="text/javascript" src="{$VENDORS}/sifter/sifter.min.js"></script>
		<script type="text/javascript" src="{$VENDORS}/selectize/dist/js/selectize.min.js"></script>
		

		<!-- WYSIWYG script -->
		<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js"></script>
		<!-- /WYSIWYG script -->

		<script src="https://kit.fontawesome.com/5df6dcce04.js" crossorigin="anonymous"></script>

		<script>
			var SITE_URL = '{$SITE_URL}';
			$(document).ready(function() {
				$('.alert-success').delay(8000).fadeOut();
				$('.alert-warning').delay(8000).fadeOut();

				// show collapsible content when page is selected
				var pathname = window.location.pathname;
				console.log(pathname);
			});


			
		</script>

	</head>
	<body>

		<!-- top navigation bar -->	
		<nav class="navbar navbar-dark bg-dark flex-md-nowrap p-0 shadow sticky-top">
			<a href="/" class="navbar-brand col-sm-6 col-md-2 mr-0"><img class="img-fluid" src="{$IMAGES}/aspencreek-logo_white.png" alt="Aspen Creek Black Logo"></a>
			<ul class="navbar-nav px-3">
				<li class="nav-item text-nowrap text-white">
					{if $auth->isLoggedIn()}Hello, {$auth->fullName()} &nbsp;|&nbsp; <a href="{$SITE_URL}" target="_blank" class="text-white">Public Page</a> &nbsp;|&nbsp; <a href="{$SITE_URL}/login/logout" class="text-white">Logout</a>{/if}
				</li>
			</ul>
		</nav>
		<!-- / top navigation bar -->

		<!-- side navigation panel -->
		{if $auth->valid()}
			{$this->loadElement("navigation")}
		{/if}
		<!-- /side navigation panel -->

		<!-- main page content -->
		<main class="col-md-9 ml-sm-auto col-lg-10">
			<div class="d-flex-justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-2">	
				
				<!-- flash messages ------>
				{if $flashMessages}
				<div class="row mx-4 p-4 flash-message">
					{foreach $flashMessages as $class => $message}
					<div class="col-12 text-center alert-{$class}" role="alert">
						<ul>
						{foreach $message as $m}
							<li>{$m}</li>
						{/foreach}
						</ul>
					</div>
					{/foreach}
				</div>
				{/if}
				<!-- /flash messages ------>

				<!-- individual page content -->
				{include file=$content}
				<!-- /individual page content -->

			</div>
		</main>
		<!-- /main page content -->
				


		<!-- <div id="timeout-warning">
		    <p>Your session is about to timeout.  You will be automatically logged out in 1 minute. To remain logged in click the button below.</p>
		</div> -->
	</body>
	<script>
		$(document).ready(function() {
			{include file=$jsfile}
		});
	</script>
</html>
