<!-- /app/View/Layouts/default.ctp -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<!--<meta http-equiv="refresh" content="1800">-->
	<title>Advanced Health Care Menu &amp; Activities</title>
	<link rel="stylesheet" href="{$CSS}/public_styles.css" type="text/css" />
	<link rel="stylesheet" href="/js/jquery-ui/css/cupertino/jquery-ui-1.8.21.custom.css" type="text/css" media="screen" title="no title" charset="utf-8">

	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery.jclock.js"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/public.js"></script>


</head>

<body>
	<div id="wrapper">
		<div class="overall">

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

{* 			{if $isAdmin && !$isTV}
				<div id="tv-fold">
				</div>
				<div id="tv-fold-note">
				Anything outside the dotted line will not showup on the TV and will cause a scroll bar to appear on the right side of the page.
				</div>
				<div id="public-preview">
					<a href="{$SITE_URL}/?module=Dietary&amp;location={$location->public_id}">Return to Admin Page</a>
				</div>
				{if $user->group->id == 1}
					<div id="debug">
						<div class="panelCount"></div>
						<div class="currentPanel"></div>
						<div class="nextPanel"></div>
						<div class="firstRun"></div>
					</div>
				{/if}
			{/if}
 *}		</div>
	</div>
	<!-- div containing the red warning symbol -->
			<div id="error" style="display:none;position:absolute;bottom:10px;left:20px;font-size: 2em;"><img src="{$IMAGES}/warning.png" alt="No internet connection"></div>
</body>
</html>
