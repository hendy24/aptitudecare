<!DOCTYPE html>
<html>
<head>
{include file="$cms_template_dir/_head.tpl"}
{include file="$cms_template_dir/_javascript_auto.tpl"}

<script type="text/javascript" src="{$ENGINE_URL}/js/jquery/jquery-validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="{$ENGINE_URL}/js/jquery/jquery.hoverintent.r5.js"></script>
<script type="text/javascript" src="{$ENGINE_URL}/js/jquery/jquery-validate/additional-methods.js"></script>
<script type="text/javascript" src="{$ENGINE_URL}/js/jquery/jquery.alerts-1.1/jquery.alerts.js"></script>
<script type="text/javascript" src="{$ENGINE_URL}/js/jquery/jquery.maskedinput-011748c/src/jquery.maskedinput.js"></script>
<script type="text/javascript" src="{$SITE_URL}js/jquery-ui-1.8.12.advancedhc/js/jquery-ui-1.8.12.custom.min.js"></script>
<script type="text/javascript" src="{$SITE_URL}js/jquery-ui-1.8.12.advancedhc/development-bundle/external/jquery.cookie.js"></script>
<script type="text/javascript" src="{$SITE_URL}js/jquery-ui-1.8.12.advancedhc/js/jquery.qtip.min.js"></script>
<link rel="stylesheet" href="{$SITE_URL}js/jquery-ui-1.8.12.advancedhc/css/jquery.qtip.min.css" type="text/css" />
<script type="text/javascript" src="{$ENGINE_URL}/js/jquery/jquery-ui-timepicker-addon.js"></script>

<script type="text/javascript" src="{$SITE_URL}js/timeout.js"></script>
<link rel="stylesheet" type="text/css" href="{$SITE_URL}js/jquery-ui-1.8.12.advancedhc/css/custom-theme/jquery-ui-1.8.12.custom.css" />
<link rel="stylesheet" type="text/css" href="{$ENGINE_URL}/js/jquery/jquery.alerts-1.1/jquery.alerts.css" />
<script type="text/javascript" src="{$SITE_URL}js/shadowbox-3.0.3/shadowbox.js"></script>
<link rel="stylesheet" href="{$SITE_URL}js/shadowbox-3.0.3/shadowbox.css" type="text/css">
<!-- <script type="text/javascript" src="{$SITE_URL}js/modernizr.js"></script>
 -->
 <script type="text/javascript" src="{$SITE_URL}js/general.js"></script>

<!-- Highcharts js framework -->
<script src="{$SITE_URL}js/highcharts/js/highcharts.js"></script>

<!-- Leaflet map framework -->
<script src="{$SITE_URL}js/leaflet-0.7.2/leaflet.js"></script>
<link rel="stylesheet" href="{$SITE_URL}js/leaflet-0.7.2/leaflet.css" />

<script>
	Shadowbox.init({
		height: 425,
		width: 450,
		handleOversize: "resize",
		overlayColor: "#666",
		overlayOpacity: "0.25"
	});
</script>

{javascript}
	var SITE_EMAIL = '{$SITE_EMAIL}';
{/javascript}

{if $auth->getRecord()->timeout}
{jQueryReady}
	startTimer();
{/jQueryReady}
{/if}

{if ($page == "patient" && $action == "printInquiry") || ($page == "patient" && $action == "printNursing")}
</head>
<body>
{include file="$content_tpl"}
</body>
</html>
{else}
<link rel="stylesheet" type="text/css" href="{$SITE_URL}css/modal.css" media="screen" />
{if $isPrint != 1}<link type="text/css" rel="stylesheet" href="{$SITE_URL}css/styles.css" media="all" />{/if}
{if $isTV == 1}<link rel="stylesheet" href="{$SITE_URL}css/tv.css" type="text/css" media="all" />{/if}
{if $siteCss}<link rel="stylesheet" href="{$SITE_URL}css/site_css/{$siteCss}" type="text/css" media="all" />{/if}
{if $isPrint == 1}<link rel="stylesheet" href="{$SITE_URL}css/print.css" type="text/css" media="all" />{/if}

<body {if $auth->getRecord()->timeout && $isTV == 0}onmousemove="resetTimer(){/if}">
{if $isMicro == 1}
	{include file="_feedback.tpl"}
	<br />
	{include file="$content_tpl"}
{else}
	<div id="header-container">
		{if $auth->valid()}
		{$defaultFacility = $auth->getRecord()->getDefaultFacility()}
		<div id="header">
		
			<div id="user-info">
			
				Welcome, {$auth->getRecord()->getFullName()}{if $isTV == 1} | <a href="{setURLVar(currentURL(), 'resOverride', 'desktop')}">Desktop Mode</a>{else} | <a href="{setURLVar(currentURL(), 'resOverride', 'TV')}">TV Mode</a>{/if} | <a href="{$SITE_URL}?page=login&amp;action=logout">Logout</a>
			
			</div>
			
			<img src="{$SITE_URL}images/{$logo}" alt="AptitudeCare Logo" class="logo" />
						
			<div id="nav">
			
				<ul>
					
						<li><a href="#">Data</a>
							<ul>
								<li><a href="{$SITE_URL}?page=caseManager&amp;action=manage">Case Managers</a></li>
								<li><a href="{$SITE_URL}?page=hospital&amp;action=manage">Healthcare Facilities</a></li>
								<li><a href="{$SITE_URL}?page=pharmacy&amp;action=manage">Pharmacies</a></li>
								<li><a href="{$SITE_URL}?page=physician&amp;action=manage">Physicians/Surgeons</a></li>
								{if $auth->getRecord()->isAdmissionsCoordinator() == 1}
									<li><a href="{$HOMEHEALTH_URL}?page=users&amp;action=manage">Users</a></li>
								{/if}
								<li><a href="{$SITE_URL}?page=patient&amp;action=upload">Upload Patients</a></li>
								<li><a href="{$HOMEHEALTH_URL}/?page=users&amp;action=my_info">My Account</a></li>
							</ul>
						</li>	
					<li><a href="#">Facility Info</a>
						<ul>
							<li><a href="{$SITE_URL}?page=facility&amp;action=census&amp;facility={$facility->pubid}">Census</a></li>
							<li><a href="{$SITE_URL}?page=report&amp;action=index&amp;facility={$facility->pubid}">Reports</a></li>
						</ul>
					</li>
					<li><a href="#">Discharges</a>
						<ul>
							<li><a href="{$SITE_URL}?page=facility&amp;action=schedule_discharges">Schedule Discharge(s)</a></li>
							<li><a href="{$SITE_URL}?page=facility&amp;action=manage_discharges">Manage Discharges</a></li>
							<li><a href="{$SITE_URL}?page=coord&amp;action=trackHospitalVisits&amp;facility={$facility->pubid}">Return to Hospital</a></li>
						</ul>
					</li>		
					<li><a href="#">Admissions</a>
						<ul>
							<li><a href="{$SITE_URL}?page=coord&amp;action=admit">New Admit Request</a></li>
							<li><a href="{$SITE_URL}?page=coord&amp;action=pending_admissions&amp;facility={$facility->pubid}">Pending Admissions</a></li>
							{if $auth->getRecord()->id == 8 || $auth->getRecord()->id == 9}
								<li><a href="{$SITE_URL}?page=coord&amp;action=pending_transfers">Pending Transfers</a></li>
							{/if}
				
						</ul>
					</li>	
					<li id="facility-dashboard"><a href="{$SITE_URL}?page=facility&amp;id={$defaultFacility->pubid}">{$defaultFacility->name} Dashboard</a>
						{if !empty ($myFacilities)}
						<ul id="facility-dashboard-dropdown">
						{foreach $myFacilities as $f}
							<li><a href="{$SITE_URL}?page=facility&amp;id={$f->pubid}">{$f->name} Dashboard</a></li>
						{/foreach}
						</ul>
						{/if}
					</li>

					{if $auth->getRecord()->isAdmissionsCoordinator() == 1}<li><a href="{$SITE_URL}?page=coord">Home</a></li>{/if}
				</ul>
			
			</div>
		
		</div>
		
	</div>
	{else}
		<div id="header">
		
			<div id="user-info">
			
				<a href="{$SITE_URL}?page=login">Login</a>			
			</div>
	
			<img src="{$SITE_URL}images/{$logo}" alt="AptitudeCare Logo" class="logo" />
					
		</div>
		
	</div>
	{/if}

	<div id="content">
		<div class="right" style="margin-top: -12px;">{if $isTV == 1}<a href="{setURLVar(currentURL(), 'resOverride', 'desktop')}">Desktop Mode</a>{/if}</div>
	
		{include file="_feedback.tpl"}
		
		{include file="$content_tpl"}

		<div style="display:  none;">
			<div id="remote-dialog">
				<div id="remote-dialog-frame"></div>
				{*<iframe id="remote-dialog-frame" width="100%" height="100%" marginwidth=0 marginheight=0 scrolling=yes frameborder=0></iframe>*}
			</div>
		</div>
	</div>
	
	<div id="timeout-warning">
	    <p>Your session is about to timeout.  You will be automatically logged out in 1 minute. To remain logged in click the button below.</p>
	</div>


{/if}
<JAVASCRIPT_BOTTOM>
</body>
</html>
{/if}