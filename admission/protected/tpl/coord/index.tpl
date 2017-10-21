{setTitle title="Coordinator Dashboard"}

<script type="text/javascript" src="{$SITE_URL}/js/draganddrop.js"></script>

{jQueryReady}

	$(".alt-week").datepicker({
		showOn: "button",
		buttonImage: "{$ENGINE_URL}/images/icons/calendar.png",
		buttonImageOnly: true,
		onSelect: function(dateText, inst) { 
			var tab = $(this).attr("rel");
			var href = '{setURLVar($CURRENT_URL, 'weekSeed', '')}&weekSeed=' + dateText + tab;
			location.href = href;
		}
		
	});

	$(".schedule-datetime").datetimepicker({
		showOn: "button",
		buttonImage: "{$ENGINE_URL}/images/icons/calendar.png",
		buttonImageOnly: true,
		timeFormat: "hh:mm tt",
		stepMinute: 15,
		hour: 13,
		onClose: function(dateText, inst) {
			location.href = SITE_URL + '/?page=coord&action=setScheduleDatetimeAdmit&id=' + inst.input.attr("rel") + '&datetime=' + dateText + '&path={urlencode(currentURL())}';
		}
		
	});


	$("#module").change(function(e) {
		e.preventDefault();
		var pathArray = window.location.href.split('/');
		var protocol = pathArray[0];
		var host = pathArray[2];
		var redirectUrl = protocol + '//' + host;

		window.location.href = redirectUrl + "/?page=login&action=admission_login&username=" + $("#username").val() + "&id=" + $("#user-id").val();

	});


	$("#coordinator-dashboard-tabs").tabs({
		cookie: { expires: 30 }
	});
	
	$(".pending-facility").change(function(e) {
		$.getJSON(SITE_URL , { page: 'coord', action: 'setScheduleFacility', schedule: $(this).attr("rel"), facility: $("option:selected", this).val() }, function(json) {
			//
		});
	});
	
	$("#submit-button").click(function(e) {
		e.preventDefault();
		window.location = SITE_URL + "/?page=patient&action=search_results&patient_name=" + $("#patient-search").val();
	});

{/jQueryReady}


{if $auth->getRecord()->module_access}
<div id="change-module">
	Module:
	<select name="module" id="module">
		<option value="admission">Admission</option>
		<option value="home_health">Home Health</option>
	</select>
	<input type="hidden" id="username" value="{$auth->getRecord()->email}" />
	<input type="hidden" id="user-id" value="{$auth->getRecord()->pubid}" />
</div>
{/if}

<div id="facility-admit-section">
	<h1 class="text-center">{$week[0]|date_format:"%B %d, %Y"} <span class="text-16">to</span> {$week[6]|date_format:"%B %d, %Y"}
	<input type="hidden" class="alt-week" rel="#tab-{$facility->pubid}" /></h1>
</div>
<div id="week-nav"><a href="{$SITE_URL}/?page=coord&amp;weekSeed={$prevWeekSeed}#facility-admit-section"><img src="{$SITE_URL}/images/icons/prev-icon.png" /> Previous Week</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="{$SITE_URL}/?page=coord&amp;weekSeed={$nextWeekSeed}#facility-admit-section">Next Week <img src="{$SITE_URL}/images/icons/next-icon.png" /></a></div>

<form name="patient_search" accept="post">
<div id="search-box">
	<input type="text" name="search_patient" id="patient-search" placeholder="Enter the patients' name" size="30" /> <input type="submit" value="Search" id="submit-button" />
</div>
</form>

{$facilities = $auth->getRecord()->related("facility")}
{$allFacilities = CMS_Facility::generate()->fetch()}

<div id="action-menu">
	<a href="{$SITE_URL}/?page=facility&amp;action=census&amp;facility={$facility->pubid}" class="button">Census</a>
</div>

<br />

<!-- Look for duplicates button -->
<!-- <a class="button" style="float: right; margin-right: 10px" href="{*$SITE_URL*}/?page=coord&action=duplicateEntries&facility={*$facility->pubid*}">Duplicate entries</a>
 -->
<br />
<br />
<br />
<div id="coordinator-dashboard-tabs">
	<ul>
	{foreach $facilities as $facility}
	<li><a href="#tab-{$facility->pubid}">{$facility->name}</a></li>
	{/foreach}
	</ul>
{foreach $facilities as $facility}
	<div id="tab-{$facility->pubid}" class="dashboard-week">
		<div class="dashboard-tab-facility-title right">
			<a href="{$SITE_URL}/?page=facility&amp;id={$facility->pubid}">Go to Facility Dashboard &raquo;</a><br />
			<br />
		</div>
		<div class="dashboard-tab-facility-date-range">
			<h1>{$facility->name}</h1>
			<br />
			<i>Today is {$smarty.now|date_format}. There are currently <a href="{$SITE_URL}/?page=facility&action=census&facility={$facility->pubid}&datetime={$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}&type=empty"><strong>{$emptyRoomCountByFacility[$facility->id]}</strong> empty rooms</a> at this facility.</i>
		</div>
		<div class="clear" style="height: 0px;"></div>
		{foreach $week as $day}
		{$admits = $completedAdmitsByFacilityAndDate[$facility->id][$day]}	
		{$pendings = $pendingAdmitsByFacilityAndDate[$facility->id][$day]}
		{$discharges = $dischargesByFacilityAndDate[$facility->id][$day]}
		{$sents = $sentToHospitalByFacilityAndDate[$facility->id][$day]}	

		<div class="coordinator-day-box">
		
			<h3>{$day|date_format:"%A, %B %e"}</h3>
		
			<div class="admits">
			
				<strong>Admit</strong><br />
				
				<div class="clear"></div>
			
				<div class="admits-confirmed">
				
					Confirmed<br />
					<br />
				
					{foreach $admits as $admit}
					{$onsiteVisit = CMS_Onsite_Visit::generate()}
					{$onsite = $onsiteVisit->fetchVisitInfo($admit->id)}
					<div class="patient-box admit-confirmed">
						{if $admit->getPatient->transfer_facility != ''}
							{$transferFacility = CMS_Facility::generate()}
							{$transferFacility->load($admit->getPatient()->transfer_facility)}
						{else}
							{$admitFrom = CMS_Hospital::generate()}
							{$admitFrom->load($admit->getPatient()->admit_from)}
						{/if}
						{conflictAlert schedule=$admit}
						<span class="admit-name">Room {$admit->getRoomNumber()}<br />
							{foreach $onsite as $o}
								{if $o->id != ''}<a href="#" class="tooltip"<img src="{$PUBLIC_URL}/images/icons/check.png" style="height: 14px;" /><span>Patient has had an on-site visit</span></a>{/if}
							{/foreach}
							{if $admit->confirmed == 1}<a href="#" class="tooltip"><img src="{$PUBLIC_URL}/images/icons/star.png" style="height: 10px;" /><span>Elective admit has been confirmed.</span></a>{/if}
							<strong>{$admit->getPatient()->fullName()}</strong><br />Admit From: {if $admit->transfer_facility != ''}{$transferFacility->name}{elseif $admit->getPatient()->admit_from != ''}{$admitFrom->name}{elseif $admit->getPatient()->hospitalName() != ''}{$admit->getPatient()->hospitalName()|default:"Unknown"}{else}{$admit->getPatient()->referral_org_name}{/if}{scheduleMenu schedule=$admit}
						</span>
						<!-- <input type="hidden" class="schedule-datetime" rel="{$admit->pubid}" /> -->
					
					</div>
					{/foreach}
					&nbsp;
				</div>
				<div class="admits-pending">
				
					Pending<br />
					<br />
				
					{foreach $pendings as $pending}
					{$onsiteVisit = CMS_Onsite_Visit::generate()}
					{$onsite = $onsiteVisit->fetchVisitInfo($pending->id)}
					{if $pending->transfer_facility != ''}
						{$pendingTransferFacility = CMS_Facility::generate()}
						{$pendingTransferFacility->load($pending->transfer_facility)}
					{else}
						{$pendingHospital = CMS_Hospital::generate()}
						{$pendingHospital->load($pending->getPatient()->admit_from)}
					{/if}
					
					{if $pending->getPatient()->physician_id != ''}
						{$physician = CMS_Physician::generate()}
						{$physician->load($pending->getPatient()->physician_id)}
					{/if}
					
					<div class="patient-box drag {if $pending->referral}admit-pending{else}admit-pending-no-referral{/if}" draggable="true">
						{conflictAlert schedule=$pending}				
						<span class="admit-name">Room {$pending->getRoomNumber()}<br />
							{foreach $onsite as $o}
								{if $o->id != ''}<a href="#" class="tooltip"><img src="{$PUBLIC_URL}/images/icons/check.png" style="height: 14px;" /><span>Patient has had an on-site visit</span></a>{/if}
							{/foreach}
							
							{if $pending->confirmed == 1}<a href="#" class="tooltip"><img src="{$PUBLIC_URL}/images/icons/star.png" style="height: 10px;" /><span>Elective admit has been confirmed.</span></a>{/if}
							<strong>{$pending->getPatient()->fullName()}</strong><br />Admit From: {if $pending->transfer_facility != ''}{$pendingTransferFacility->name}{elseif $pending->getPatient()->admit_from != ''}{$pendingHospital->name}{else}{$pending->getPatient()->referral_org_name}{/if}
	{if $pending->getPatient()->physician_id != ''}
	<br />Physician: Dr. {$physician->last_name}
	{/if}
	{scheduleMenu schedule=$pending}</span>
						<input type="hidden" class="patient-pubid" value="{$pending->pubid}" />
						<input type="hidden" class="schedule-datetime" rel="{$pending->pubid}" value="{datetimepickerformat($pending->datetime_admit)}" />
					</div>
					{/foreach}
					&nbsp;
					
				</div>
			
			</div>
			
			<div class="discharges">
				<strong>Discharge</strong><br />
				
				<div class="clear"></div>
			
				All<br />
				<br />
				
				<div class="clear"></div>

				<!-- Patients sent back to the hospital -->
				{foreach $sents as $sent}
				{$hospital = CMS_Hospital::generate()}
				{$hospital->load($sent->hospital)}
					{if (! $sent->hasBedhold())}
						<div class="patient-box hospital">
							<span class="sent-name">Room {$sent->getRoomNumber()}<br /><strong>{$sent->getPatient()->fullName()}</strong><br />Hospital: {$hospital->name|default:"Unknown"}{scheduleMenu schedule=$sent}</span>
							
						</div>
					{/if}
				{/foreach}
				
				<!-- Patients scheduled for discharge -->
				{foreach $discharges as $discharge}
				<div class="patient-box discharge"{if $discharge->hasBedhold()} style="background-color: yellow;"{/if}>
					{if $discharge->getPatient()->physician_id != ''}
						{$dPhysician = CMS_Physician::generate()}
						{$dPhysician->load($discharge->getPatient()->physician_id)}
					{/if}
					<span class="discharge-name">Room {$discharge->getRoomNumber()}<br /><strong>{$discharge->getPatient()->fullName()}</strong><br />Physician: {if $discharge->getPatient()->physician_id != ''}{$dPhysician->last_name} {$dPhysician->first_name} M.D.{else}{$discharge->getPatient()->physician_name}{/if}{if $discharge->discharge_to == 'Discharge to Hospital (Bed Hold)'}<br />Bed hold until {$discharge->datetime_discharge_bedhold_end|datetime_format}{/if}{scheduleMenu schedule=$discharge}</span>
				
				</div>
				{/foreach}

				
			</div>
		
		</div>
		{/foreach}	

</div>
{/foreach}
