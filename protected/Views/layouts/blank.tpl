<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{$title}</title>
	<link rel="stylesheet" href="{$frameworkJs}/jQuery-Autocomplete-master/content/styles.css" />
	<link rel="stylesheet" href="{$frameworkCss}/styles.css">
	<link rel="stylesheet" href="{$frameworkJs}/jquery-ui-1.11.0.custom/jquery-ui.css" />
	<link rel="stylesheet" href="{$frameworkJs}/jquery-ui-1.11.0.custom/jquery-ui.theme.min.css" />
	<link rel="stylesheet" href="{$frameworkJs}/shadowbox-3.0.3/shadowbox.css" />
	<link rel="stylesheet" href="{$frameworkCss}/blank.css" />

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
	</script>
	
</head>
<body>
	<div id="wrapper">
		<div id="content">
			{include file=$content}
		</div>
		
	</div>
</body>
</html>