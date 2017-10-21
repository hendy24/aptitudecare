{setTitle title="Track Hospital Visits for {$facility->name}"}
<h1 style="text-align: center;">Return to Hospital</h1>
<h2 class="text-center">{if $facility != ''}{$facility->name}{/if}</h2>

<br />
<br />

{jQueryReady}
$("#facility").change(function(e) {
    window.location.href = SITE_URL + '/?page=coord&action=trackHospitalVisits&facility=' + $("option:selected", this).val();
});


$(".stop-tracking").click(function(e) {
	var tableRow = $(this).parent().parent();
	e.preventDefault();
	var anchor = this;
			
	jConfirm ("Are you sure you want to stop tracking this hospital visit?  This cannot be undone.", 'Confirmation Required', function(r) {
		if (r == true) {
			$.getJSON(SITE_URL , { page: "coord", action: "stopTrackingHospitalVisit", schedule_hospital: $(anchor).attr("rel") }, function(json) {
				$(tableRow).fadeOut();
			}, "json");
		} else {
			return false;
		}
	});
	
	return false;
	
});


{/jQueryReady}

<div style="float: left;">
	Track Visits for:
	<select id="facility">
	<option value="">Select a facility...</option>
	{foreach $facilities as $f}
	    <option value="{$f->pubid}"{if $f->id == $facility->id} selected{/if}>{$f->name}</option>
	{/foreach}
	</select>
</div>
{if $atHospitalRecords !== false}

{if count($atHospitalRecords) > 0}
	{jQueryReady}
	$("#orderby").change(function(e) {
		window.location.href = SITE_URL + '/?page=coord&action=trackHospitalVisits&facility={$facility->pubid}&orderby=' + $("option:selected", this).val();
	});
	{/jQueryReady}
	{$orderByOpts = [
	'datetime_updated_DESC' => 'Date (last updated - newest first)',
	'datetime_updated_ASC' => 'Date (last updated - oldest first)',
	'datetime_created_DESC' => 'Date (initiated - newest first)',
	'datetime_created_ASC' => 'Date (initiated - oldest first)',
	'datetime_sent_DESC' => 'Date (sent to hospital - newest first)',
	'datetime_sent_ASC' => 'Date (sent to hospital - oldest first)',
	'hospital_name' => 'Hospital name (A &rarr; Z)',
	'facility' => 'Facility name (A &rarr; Z)',
	'discharge_nurse' => 'Discharge nurse name (A &rarr; Z)'
	]}
	<div style="float: right;">
		<strong>Order by:</strong>
		<select id="orderby">
		{foreach $orderByOpts as $k => $v}
			<option value="{$k}"{if $orderby == $k} selected{/if}>{$v}</option>
		{/foreach}
		</select>
	</div>
	<div style="float: left; clear: both; margin: 25px 50px; font-size: 14px;">
		There are <strong>{count($atHospitalRecords)}</strong> hospital visits currently being tracked.
	</div>
{/if}
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	{foreach $atHospitalRecords as $ahr}
	
		{$schedule = CMS_Schedule::generate()}
		{$schedule->load($ahr->pubid)}
		<tr bgcolor="{cycle values="#eeeeee,#ffffff"}">
	
			<td valign="top" nowrap class="track-visits-patient" width="20px">
				<div class="trackVisitsName
					{if $ahr->datetime_discharge != '' && $ahr->datetime_discharge_bedhold_end != ''}
						background-yellow
					{elseif $ahr->datetime_discharge != '' && $ahr->datetime_discharge_bedhold_end == ''}
						background-red
					{else}
						background-purple
					{/if}
				"></div>
			</td>
			<td width="25px">&nbsp;</td>
			<td>
				<strong>{$ahr->last_name}, {$ahr->first_name}</strong><br />
				{$ahr->name}
			</td>
			<td width="100px">{scheduleMenu schedule=$schedule}</td>
			<td>
				<a href="{$SITE_URL}/?page=facility&amp;action=sendToHospital&amp;schedule={$schedule->pubid}&amp;path={urlencode(currentURL())}" class="button">Hospital Visit Info</a>
			</td>
			{if $schedule->datetime_discharge != ''}
				<td>
					<a href="{$SITE_URL}/?page=coord&amp;action=readmit&facility={$facility->pubid}&schedule={$schedule->pubid}" class="button">Re-Admit this Patient</a>
				</td>
					{/if}
			<td valign="top" valign="right" style="padding: 25px;">
				{if $schedule->datetime_discharge != "" && ($schedule->datetime_discharge_bedhold_end == "" || strtotime($schedule->datetime_discharge_bedhold_end) < strtotime("now"))}
					<a class="stop-tracking" rel="{$ahr->schedule_hospital_pubid}"><img src="{$SITE_URL}/images/icons/trash.png" alt="Delete Hospital Visit" /></a>
				{else}
					&nbsp;
				{/if}
			</td>
	
		</tr>
	{foreachelse}
	
		<br />
		<br />
		<br />
		<div class="text-center">
			{if $facility}
				<strong> There are currently no hospital visits being tracked for this facility</strong>
			{else}
				<strong>Please select a facility to view hospital visits.</strong>
			{/if}
		</div>
	{/foreach}
</table>
{/if}
