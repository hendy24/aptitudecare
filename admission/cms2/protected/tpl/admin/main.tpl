<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
{include file="$cms_template_dir/_head.tpl"}
		{set_title title="Content Administration"}
		<link rel="stylesheet" type="text/css" href="{$ENGINE_URL}/js/jquery/jquery-ui-1.8.11.custom/css/redmond/jquery-ui-1.8.11.custom.css" />
		<link rel="stylesheet" type="text/css" href="{$ENGINE_URL}/js/jquery/farbtastic/farbtastic.css" />
		<link rel="stylesheet" type="text/css" href="{$ENGINE_URL}/css/admin.css" />
		<link rel="stylesheet" type="text/css" href="{$ENGINE_URL}/js/jquery/Jcrop/css/jquery.Jcrop.css" />
		<link rel="stylesheet" type="text/css" href="{$ENGINE_URL}/js/jquery/anytime/anytime.css" />
		<script type="text/javascript" src="{$ENGINE_URL}/js/jquery/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
		<script type="text/javascript" src="{$ENGINE_URL}/js/jquery/jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript" src="{$ENGINE_URL}/js/jquery/farbtastic/farbtastic.js"></script>
		<script type="text/javascript" src="{$ENGINE_URL}/js/jquery/Jcrop/js/jquery.Jcrop.min.js"></script>
		<script type="text/javascript" src="{$ENGINE_URL}/js/jquery/jquery.maskedinput-1.2.2.min.js"></script>
		<script type="text/javascript" src="{$ENGINE_URL}/js/jquery/jquery.alerts-1.1/jquery.alerts.js"></script>
		<link rel="stylesheet" type="text/css" href="{$ENGINE_URL}/js/jquery/jquery.alerts-1.1/jquery.alerts.css" />
		{include file="$cms_template_dir/_javascript_auto.tpl"}
		<script type="text/javascript">
		</script>
		{jQueryReady}
		$("#select-nav").change(function() {
			if ($("option:selected", this).val() != '') {
				location.href = $("option:selected", this).val();
			}
		});
		{/jQueryReady}
	</head>
	<body>
	<div id="wrap">
		<div id="header">
			<span style="float: left; width: 500px;"><h2>Web Content Administration</h2></span>
			<div id="welcome-back">
				{if $admin_auth->valid()}{$admin_auth->fullname} &nbsp;&nbsp; | &nbsp;&nbsp;
				<a href="{$SITE_URL}/?page=admin&amp;action=profile">Profile</a> &nbsp;&nbsp; | &nbsp;&nbsp;
				<a href="{$SITE_URL}" target="_{constant('APP_NAME')}_site">View Website</a> &nbsp;&nbsp; | &nbsp;&nbsp;
				<a href="{$SITE_URL}/?page=admin&amp;action=logout">Logout</a>{/if}
			</div>
			<br style="clear: both;" />
			{if $admin_auth->valid()}
			<select id="select-nav">
			<option value="">Select content to modify...</option>
			{foreach from=$tables_nav key="m" item="title"}
				<option value="{$SITE_URL}/?page=admin&amp;action=record_index&amp;m={$m}"{if $model == $m} selected{/if}>{$title}</option>
			{/foreach}
			{foreach from=$actions_nav key="path" item="title"}
				<option value="{$SITE_URL}{$path}"{if strpos($CURRENT_URL, $path) != false} selected{/if}>{$title}</option>
			{/foreach}
			</select>
			{/if}
		</div>
		<div id="main">
		<br /><br />
		{include file="$cms_template_dir/admin/_feedback.tpl"}
		<br />
		{if $admin_auth->valid()}
			{if count($similarModels) > 0}
			<div id="related-models">
			<strong>You may also be interested in:</strong><br />
			{foreach $similarModels as $sm => $smName}
				&raquo; <a href="{$SITE_URL}/?page=admin&amp;action=record_index&amp;m={$sm}">{$smName}</a><br />
			{/foreach}
			</div>
			{/if}
		{/if}
		{include file=$content_tpl}
		</div>
		<div id="footer">
			Intercarve CMSv2{if $revtime != ''} rev. {$revtime|date_format:"%Y/%m/%d %H:%M:%S"}{/if}<br />&copy; 2002 - {$smarty.now|date_format:"%Y"} Intercarve Networks LLC. All Rights Reserved.
		</div>
	</div>

	</body>
</html>