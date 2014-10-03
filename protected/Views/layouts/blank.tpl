<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{$title}</title>
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/jQuery-Autocomplete-master/content/styles.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_CSS}/styles.css">
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/jquery-ui-1.11.0.custom/jquery-ui.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/jquery-ui-1.11.0.custom/jquery-ui.theme.min.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_JS}/shadowbox-3.0.3/shadowbox.css" />
	<link rel="stylesheet" href="{$FRAMEWORK_CSS}/blank.css" />

	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery-ui-1.11.0.custom/jquery-ui.min.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery-validation-1.13.0/dist/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jQuery-Autocomplete-master/dist/jquery.autocomplete.min.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery.maskedinput.min.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/datepicker.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/shadowbox-3.0.3/shadowbox.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/general.js"></script>

	<script>
		var SITE_URL = '{$SITE_URL}';
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