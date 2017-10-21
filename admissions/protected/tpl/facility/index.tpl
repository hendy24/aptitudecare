{setTitle title="{$facility->name} Dashboard"}
{jQueryReady}

$("#alt-week").datepicker({
	showOn: "button",
	buttonImage: "{$ENGINE_URL}/images/icons/calendar.png",
	buttonImageOnly: true,
	onSelect: function(dateText, inst) { 
		var href = SITE_URL + '/?page=facility&id=' + $("#facility-id").val() + '&weekSeed=' + dateText;
		location.href = href;
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


{if $isTV == 1}

setInterval(function() {
	location.href = location.href;
}, 300000);

{/if}

	$("#submit-button").click(function(e) {
		e.preventDefault();
		window.location = SITE_URL + "/?page=patient&action=search_results&patient_name=" + $("#patient-search").val();
	});


{/jQueryReady}
{if $isTV != 1}
<script language="javascript">
$(window).load(function() {
	var admitHeight = 0;
	var dischargeHeight = 0;
	$(".facility-day-box-admit").each(function(e) {
		if ($(this).height() > admitHeight) {
			admitHeight = $(this).height();
		}
	});
	$(".facility-day-box-admit").height(admitHeight);
	
	$(".facility-day-box-discharge").each(function(e) {
		if ($(this).height() > dischargeHeight) {
			dischargeHeight = $(this).height();
		}
	});
	$(".facility-day-box-discharge").height(dischargeHeight);
	});
</script>


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

<div id="two-week-view"><a href="{$SITE_URL}/?page=facility&amp;action=two_week_view&amp;id={$facility->pubid}&amp;weekSeed={$weekSeed}&type=excel"><img src="{$SITE_URL}/images/icons/file_xls.png" style="height: 42px;" /></a> <a href="{$SITE_URL}/?page=facility&amp;action=two_week_view&amp;id={$facility->pubid}&amp;weekSeed={$weekSeed}&type=pdf" target="_blank"><img src="{$SITE_URL}/images/icons/file_pdf.png" style="height: 42px;" /></a></div>
<br />

{/if}
<h1 class="text-center" style="margin-bottom: 10px">{$facility->name} Dashboard</h1> 
<h2 class="text-center" style="margin-bottom: 20px">{$week[0]|date_format:"%a, %B %d, %Y"} to {$week[6]|date_format:"%a, %B %d, %Y"}<input type="hidden" id="alt-week" /></h2>

<div id="facility-tools">
	<form name="patient-search" accept="post">
		<div id="facility-search-box-left">
			<input type="text" name="search_patient" id="patient-search" placeholder="Enter the patients' name" size="30" /> <input type="submit" value="Search" id="submit-button" />
		</div>
	</form>


	<div id="week-nav"><a href="{$SITE_URL}/?page=facility&amp;id={$facility->pubid}&amp;weekSeed={$retreatWeekSeed}"><img src="{$SITE_URL}/images/icons/prev-icon.png" /> Previous Week</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="{$SITE_URL}/?page=facility&amp;id={$facility->pubid}&amp;weekSeed={$advanceWeekSeed}">Next Week <img src="{$SITE_URL}/images/icons/next-icon.png" /></a></div>
	<div id="facility-census-button">
		<a href="{$SITE_URL}/?page=facility&amp;action=census&amp;facility={$facility->pubid}" class="button">Census</a></li>
	</div>
	
</div>



<div class="clear"></div>

<div class="tv-week-links">

	<a href="{$SITE_URL}/?page=facility&amp;id={$facility->pubid}&amp;weekSeed={$retreatWeekSeed}">&laquo; Previous Week</a> &nbsp; <a href="{$SITE_URL}/?page=facility&amp;id={$facility->pubid}&amp;weekSeed={$advanceWeekSeed}">Next Week &raquo;</a>
	&nbsp;&nbsp;&nbsp;&nbsp;<i><strong>{$emptyRoomCount}</strong> empty rooms</i>
</div>


<div class="clear"></div>

<div class="side-titles">&nbsp;</div>

<div class="facility-container">

{foreach $week as $day}
<div class="facility-day-text {if $day@last}facility-day-text-last{/if}">

	<h3>{$day|date_format:"%A, %B %e"}</h3>

</div>
{/foreach}



<!-- !Admissions -->

<div class="facility-admits">
	<input type="hidden" name="facility" id="facility-id" value="{$facility->pubid}" />
	{foreach $week as $day}
	<div class="facility-day-box facility-day-box-admit {if $day@last}facility-day-box-last{/if} {cycle name="admitDayColumn" values="facility-day-box-blue, "}">
		
		{$admits = $admitsByDate[$day]}
		<div class="regular-titles"><strong>Admit</strong><br /><br /></div>
		{foreach $admits as $admit}
			{$onsiteVisit = CMS_Onsite_Visit::generate()}
			{$onsite = $onsiteVisit->fetchVisitInfo($admit->id)}
			<div class="{if $facility->id == 4 && $admit->paymethod == "HMO"}facility-hmo{elseif $admit->status == 'Under Consideration' && $admit->referral} facility-admit-pending{elseif $admit->status == 'Under Consideration'}facility-pending-no-referral{else}facility-admit{/if}">
				{if $admit->transfer_facility != ''}
					{$transferFacility = CMS_Facility::generate()}
					{$transferFacility->load($admit->transfer_facility)}
				{elseif $admit->admit_from != ''}
					{$admitFrom = CMS_Hospital::generate()}
					{$admitFrom->load($admit->admit_from)}
				{/if}
				
				{if $admit->getPatient()->physician_id != ""}
					{$physician = CMS_Physician::generate()}
					{$physician->load($admit->getPatient()->physician_id)}
				{/if}
								
				<span class="admit-name">
				{if $isTV == 1}
					{$ptName = substr($admit->getPatient()->fullName(),0,10)|cat:"..."}
					{if $admit->transfer_facility != ''}
						{$admitFrom = $transferFacility->name}
					{elseif $admit->admit_from != ''}
						{$admitFrom = $admitFrom->name}
					{else}
						{$admitFrom = $admit->getPatient()->referral_org_name}
					{/if}
					{if strlen($admitFrom) > 20}
						{$af = substr($admitFrom,0,20)|cat:"..."}
					{else}
						{$af = $admitFrom}
					{/if}
					{foreach $onsite as $o}
						{if $o->id != ''}<a href="#" class="tooltip"><img src="{$SITE_URL}/images/icons/check.png" style="height: 14px;" /><span>Patient has had an on-site visit</span></a>{/if}
					{/foreach}
					{$admit->getRoomNumber()}
					<strong>{$ptName}</strong><br />
					{if $admit->getPatient()->physician_id != ''}
						Dr. {$physician->last_name}<br />
					{/if}
					{$af}
				{else}
					Room {$admit->getRoomNumber()} <br />
					{foreach $onsite as $o}
						{if $o->id != ''}<a href="#" class="tooltip"><img src="{$SITE_URL}/images/icons/check.png" style="height: 14px;" /><span>Patient has had an on-site visit</span></a>{/if}
					{/foreach}
					{if $admit->confirmed == 1}<a href="#" class="tooltip"><img src="{$SITE_URL}/images/icons/star.png" style="height: 10px;" /><span>Elective admit has been confirmed.</span></a>{/if}
					<strong>{$admit->getPatient()->fullName()}</strong><br />
					{if $admit->transfer_facility != ''}{$transferFacility->name}{elseif $admit->admit_from != ''}{$admitFrom->name}{else}{$admit->getPatient()->referral_org_name}{/if}<br />
					
					
					<!-- !ABQ specific functionality -->
					{if $facility->id == 4 || $facility->id == 23}
						{if ($admit->datetime_pickup != '')}
							Pickup: {$admit->datetime_pickup}<br />
						{/if}
						{if $admit->case_manager_id != ''}
							{$case_manager = CMS_Case_Manager::generate()}
							{$case_manager->load($admit->case_manager_id)}
							CM: {$case_manager->last_name}, {$case_manager->first_name}<br />
						{/if}
						
					{/if}
					{if $admit->getPatient()->physician_id != ''} 
						Physician: Dr. {$physician->last_name}<br />
					{/if}
					{if $admit->other_diagnosis != ''}
						DX: {$admit->other_diagnosis}
					{/if}
					

					
					
					<div class="facility-day-box-tools">{scheduleMenu schedule=$admit weekSeed=$weekStart}</div></span>
					<input type="hidden" class="schedule-datetime" rel="{$admit->pubid}" />
				{/if}	
				</span>
			</div>
		{/foreach}
		</ul>
	
	</div>
	{/foreach}
	

</div>

<div class="clear"></div>

{foreach $week as $day}
<div class="facility-day-text facility-discharge-day-text {if $day@last}facility-day-text-last{/if}">

	<h3>{$day|date_format:"%A, %B %e"}</h3>

</div>
{/foreach}
<div class="facility-discharges">
	
	{foreach $week as $day}
	<div class="facility-day-box facility-day-box-discharge {if $day@last}facility-day-box-last{/if} {cycle name="dischargeDayColumn" values="facility-day-box-blue, "}">

		{$discharges = $dischargesByDate[$day]}
		<div class="regular-titles"><strong>Discharge</strong><br /><br /></div>

		<!-- Patients sent back to the hospital -->
		{$sents = $sentsByDate[$day]}
		{foreach $sents as $sent}
		{$onsiteVisit = CMS_Onsite_Visit::generate()}
		{$onsite = $onsiteVisit->fetchVisitInfo($sent->schedule)}
		{if $sent->physician_id != ''}
			{$sPhysician = CMS_Physician::generate()}
			{$sPhysician->load($sent->getPatient()->physician_id)}
		{/if}
		{$hospital = CMS_Hospital::generate()}
		{$hospital->load($sent->hospital)}
			{if (! $sent->hasBedhold())}
				<div class="facility-sent">
					<span class="sent-name">
					{if $isTV == 1}
						{foreach $onsite as $o}
							{if $o->id != ''}<a href="#" class="tooltip"><img src="{$SITE_URL}/images/icons/check.png" style="height: 14px;" /><span>Patient has had an on-site visit</span></a>{/if}
						{/foreach}
						{$sent->getRoomNumber()}&nbsp;
							{$ptName = substr($sent->getPatient()->fullName(),0,12)|cat:"..."}
							{if strlen($hospital->name) > 22}
								{$hospital = substr($hospital->name,0,22)|cat:"..."}
							{else}
								{$hospital = $hospital->name}
							{/if}
							{if $sent->getPatient()->physician_id != ''}{$physician = $sPhysician->last_name}{else}{$physician = $sent->getPatient()->physician_name}{/if}
							{$pName = substr($physician,0,24)}
							<strong>{$ptName}</strong><br />{$hospital}<br />Dr. {$pName}						</span>
					{else}
						Room {$sent->getRoomNumber()}<br />
						{foreach $onsite as $o}
							{if $o->id != ''}<a href="#" class="tooltip"><img src="{$SITE_URL}/images/icons/check.png" style="height: 14px;" /><span>Patient has had an on-site visit</span></a>{/if}
						{/foreach}
						<strong>{$sent->getPatient()->fullName()}</strong><br />Hospital: {$hospital->name|default:"Unknown"}<br />Physician: {if $sent->getPatient()->physician_id != ''}Dr. {$sPhysician->last_name}{else}{$sent->getPatient()->physician_name}{/if}{scheduleMenu schedule=$sent weekSeed=$weekStart}</a>
					{/if}
					</span>
				</div>
			{/if}
		{/foreach}
		
		
		
		<!-- Discharges -->
		{foreach $discharges as $discharge}
		{if $discharge->getPatient()->physician_id != ''}
			{$dPhysician = CMS_Physician::generate()}
			{$dPhysician->load($discharge->getPatient()->physician_id)}
		{/if}
			<div class="facility-discharge"{if $discharge->hasBedhold()} style="background-color: yellow;"{/if}>
				<span class="discharge-name">
					{if $isTV == 1}
						{if strlen($discharge->getPatient()->fullName()) > 20}
							{$ptName = substr($discharge->getPatient()->fullName(),0,20)|cat:"..."}
						{else}
							{$ptName = $discharge->getPatient()->fullName()}
						{/if}
						{if $discharge->getPatient()->physician_id != ''}
							{$physician = $dPhysician->last_name}
						{else}
							{$physician = $discharge->getPatient()->physician_name}
						{/if}
						{$discharge->getRoomNumber()} <strong>{$ptName}</strong><br />
						Dr. {$physician}
					{else}
						Room {$discharge->getRoomNumber()} <br /><strong>{if ($facility->id == 4 && $discharge->discharge_to == 'Co-Pay')}<span class="text-11">$ </span>{/if}{$discharge->getPatient()->fullName()}</strong><br />Physician: {if $discharge->getPatient()->physician_id != ''}{$dPhysician->last_name}, {$dPhysician->first_name}{else}{$discharge->getPatient()->physician_name}{/if}{if $discharge->discharge_to == 'Discharge to Hospital (Bed Hold)'}<br />Bed hold until {$discharge->datetime_discharge_bedhold_end|date_format: "%m/%d/%Y"}{/if}
						
						<br />
						{if ($discharge->discharge_to == "Transfer to another AHC facility")}
							{$ahc_facility = CMS_Facility::generate()}
							{$ahc_facility->load($discharge->transfer_to_facility)}
							Transfer to: {$ahc_facility->name}
							<br />
						{/if}
						
						{if ($discharge->discharge_to == "Transfer to other facility")}
							{$location = CMS_Hospital::generate()}
							{$location->load ($discharge->discharge_location_id)}
							Transfer to: {$location->name}
							<br />
						{/if}
						
						{if $discharge->service_disposition == "Other Home Health" && $discharge->home_health_id != ""}
							{$hh = CMS_Hospital::generate()}
							{$hh->load($discharge->home_health_id)}
							HH: {$hh->name}
						{else}
							HH: {$discharge->service_disposition|default: "No service"}
						{/if}
						
						<div class="facility-day-box-tools">{scheduleMenu schedule=$discharge  weekSeed=$weekStart}</div></span>	
					{/if}
				</span>
			</div>
		{/foreach}
	</div>
	{/foreach}

</div>

</div>