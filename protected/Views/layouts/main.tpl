<!DOCTYPE html>
<html lang="en">
<head>
	<title>{$title} &nbsp;|&nbsp; {$this->module}</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- CSS Files -->
	<link rel="stylesheet" href="{$CSS}/custom.css">

	<script type="text/javascript" src="{$JS}/jquery-3.4.1.min.js"></script>
 	<script type="text/javascript" src="{$SITE_URL}/bootstrap/js/bootstrap.min.js"></script>
 	<script type="text/javascript" src="{$SITE_URL}/bootstrap/js/bootstrap.bundle.min.js"></script>

	<script>var SITE_URL = '{$SITE_URL}';</script>

</head>
<body>

		
	<div class="container-fluid">
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<div class="col-sm">
				<a href="/" class="navbar-brand"><img src="{$IMAGES}/aspencreek-logo_white.png" alt="Aspen Creek Black Logo"></a>
			</div>
			<div class="col-md text-justify-end">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle Navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						{if $auth->valid()}
							{$this->loadElement("navigation")}
						{/if}
					</ul>
				</div>
			</div>
			<div class="col-sm text-right text-white">
				{if $auth->valid()}
	        	Welcome, <a href="{$SITE_URL}/?page=users&amp;action=my_info&amp;id={$auth->getRecord()->public_id}">{$auth->fullName()}</a> &nbsp;|&nbsp; <a href="{$SITE_URL}/login/logout">Logout</a>
		      	{/if}
			</div>
		</nav>		
		<div class="container-xl">
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

			{include file=$content}
		</div>

	</div>

	<!-- <div id="timeout-warning">
	    <p>Your session is about to timeout.  You will be automatically logged out in 1 minute. To remain logged in click the button below.</p>
	</div> -->

	{$this->loadElement("scripts")}
</body>
</html>
