<!-- /app/View/Layouts/default.ctp -->
<!DOCTYPE html>
<html lang="en">
<head>
	<!--<meta http-equiv="refresh" content="1800">-->
	<title>Aspen Creek Senior Living Menu &amp; Activities</title>
	<link rel="stylesheet" href="{$CSS}/tv-display.css">

	<script src="{$JS}/jquery-3.4.1.min.js" type="text/javascript"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script type="text/javascript" src="{$FRAMEWORK_JS}/jquery.jclock.js"></script>
<!-- 	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
 -->

	<script type="text/javascript" src="{$JS}/public.js"></script>


</head>

<body>

	<div class="containter-fluid text-center">
		<div class="row position-absolute" style="left: 2rem; top: 2rem;">
			<div class="col-5">
				<img src="{$IMAGES}/aspencreek-logo-white.png" class="img-fluid"  alt="">
			</div>	
		</div>
		<div class="row position-absolute" style="top: 2rem; right: 0;">
			<div class="col">
				<div id="date"><span id="clock">&nbsp;</span></div>
			</div>	
		</div>
		{include file=$content}


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
			
	</div>

	<!-- div containing the red warning symbol -->
	<div id="error" style="display:none;position:absolute;bottom:10px;left:20px;font-size: 2em;color:yellow;">&#x26A0;</div>
</body>
</html>
