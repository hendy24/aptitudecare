

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>{$title} &nbsp;|&nbsp; AptitudeCare</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<link rel="stylesheet" href="{$frameworkJs}/jQuery-Autocomplete-master/content/styles.css" />
	<link rel="stylesheet" href="{$frameworkCss}/styles.css">
	<link rel="stylesheet" href="{$frameworkJs}/jquery-ui-1.11.0.custom/jquery-ui.css" />
	<link rel="stylesheet" href="{$frameworkJs}/jquery-ui-1.11.0.custom/jquery-ui.theme.min.css" />
	<link rel="stylesheet" href="{$frameworkJs}/shadowbox-3.0.3/shadowbox.css" />

	<script type="text/javascript" src="{$frameworkJs}/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="{$frameworkJs}/jquery-ui-1.11.0.custom/jquery-ui.min.js"></script>
	<script type="text/javascript" src="{$frameworkJs}/jquery-validation-1.13.0/dist/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{$frameworkJs}/jQuery-Autocomplete-master/dist/jquery.autocomplete.min.js"></script>
	<script type="text/javascript" src="{$frameworkJs}/jquery.maskedinput.min.js"></script>
	<script type="text/javascript" src="{$frameworkJs}/datepicker.js"></script>
	<script type="text/javascript" src="{$frameworkJs}/shadowbox-3.0.3/shadowbox.js"></script>
	<script type="text/javascript" src="{$frameworkJs}/general.js"></script>
	<script>
		var SiteUrl = '{$siteUrl}';
		Shadowbox.init({
			handleOversize: "drag",
			modal: true,
		});
	</script>
	   
</head>
<body>
	<div id="header-container">
		<div id="header">
			{if $auth->valid()}
			<div id="user-info">
				Welcome, {$auth->fullName()} &nbsp;|&nbsp; <a href="{$siteUrl}/login/logout">Logout</a>
			</div>
			{/if}
			<img src="{$frameworkImg}/aptitudecare.png" alt="Logo" class="logo"/>
			{if $auth->valid()}
				{include file="$views/elements/nav.tpl"}
			{/if}
		</div>
	</div>
	<div class="clear"></div>
	
	<div id="wrapper">
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
 -->	</div>

	</div>
	
</body>
</html>