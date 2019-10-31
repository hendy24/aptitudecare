<!-- /app/View/Layouts/default.ctp -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<!--<meta http-equiv="refresh" content="1800">-->
	<title>Aspen Creek Senior Living Menu &amp; Activities</title>
	<link rel="stylesheet" href="{$CSS}/public_styles.css" type="text/css" />
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" type="text/css" media="screen" title="no title" charset="utf-8">

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery.jclock.js"></script>
	<script type="text/javascript" src="{$JS}/public.js"></script>


</head>

<body>

	<div id="wrapper">
		<div class="overall">
			<input type="hidden" name="location" value="{$location->public_id}" />

			<div id="header">
				<div id="headerTop">
						<img src="{$IMAGES}/header_top.gif" alt="" />
						</div>
						<div id="headerLogo">
							<img src="{$IMAGES}/ahc_header_logo.png" alt="" />
						</div>
						<div id="date"><span id="clock">&nbsp;</span></div>
				</div>
			</div>
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
 	</div>
	</div>
	<!-- div containing the red warning symbol -->
	<div id="error" style="display:none;position:absolute;bottom:10px;left:20px;font-size: 2em;color:yellow;">&#x26A0;</div>
</body>
</html>
