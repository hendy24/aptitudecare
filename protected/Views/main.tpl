<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>{$title} &nbsp;|&nbsp; AptitudeCare</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="">
	<meta name="robots" content="">

	<link rel="stylesheet" href="{$css}/styles.css">
	    
</head>
<body>
	<div id="header-container">
		<div id="header">
			{if $auth->valid()}
			<div id="user-info">
				Welcome, {$auth->fullName()} &nbsp;|&nbsp; <a href="{$siteUrl}/user/logout">Logout</a>
			</div>
			{/if}
			<img src="{$images}/aptitudecare.png" alt="Logo" class="logo"/>
			{if $auth->valid()}
				{include file="$views/elements/nav.tpl"}
			{/if}
		</div>
	</div>
	<div class="clear"></div>
	<div id="wrapper">
		<div id="content">	
				{if $auth->valid()}
					{include file="$views/elements/search_bar.tpl"}
				{/if}
			<div id="page-content">
				{include file=$content}
			</div>
		</div>
	</div>
	<div id="copyright">
		All content &copy; {$smarty.now|date_format:"%Y"} AptitudeCare.  All rights reserved. <br>Powered by <a href="http://www.aptitudeit.net" target="_blank" alt="Application design and development by AptitudeIT, LLC">aptITude</a>
	</div>

	

	
</body>
</html>