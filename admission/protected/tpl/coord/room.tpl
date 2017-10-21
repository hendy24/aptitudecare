{setTitle title="{$facility->name} Room Assignment"}
{jQueryReady}

$("#facility").change(function(e) {
	var $id = $("option:selected", this).val();
	location.href = SITE_URL + '/?page=coord&action=room&schedule={$schedule->pubid}&facility=' + $id;
});

$("#schedule-datetime").datetimepicker({
	showOn: "button",
	buttonImage: "{$ENGINE_URL}/images/icons/calendar.png",
	buttonImageOnly: true,
	timeFormat: "hh:mm tt",
	stepMinute: 15,
	hour: 13,
	onClose: function(dateText, inst) {
		location.href = SITE_URL + '/?page=coord&action=room&schedule={$schedule->pubid}&datetime=' + dateText + '&_path={urlencode(currentURL())}';
	}
	
});

$("#type").change(function(e) {
    window.location.href = SITE_URL + '/?page=coord&action=room&facility={$facility->pubid}&datetime={$datetime}&type=' + $("option:../facility/census.tplselected", this).val();
});

{/jQueryReady}
{javascript}
{/javascript}
{$patient = $schedule->getPatient()}
{$facilities = $auth->getRecord()->getFacilities()} 

{* $facilities = CMS_Facility::generate()->fetch() removed 2012-02-23 by kwh - only want list of facilities user is authorized to access *}

<h1 class="text-center">Select a Room for {$patient->fullName()}</h1>
<h2 class="text-center">at {$facility->name}</h2>


<!-- section commented out below was removed by bjc -->
{* Admission on {$schedule->datetime_admit|datetime_format} <input type="hidden" class="schedule-datetime" rel="{$schedule->pubid}" value="{$schedule->datetime_admit}" /> *}

<br />
<br />
<form method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="coord" />
	<input type="hidden" name="action" value="setScheduleFacilityAndRoom" />
	<input type="hidden" name="schedule" value="{$schedule->pubid}" />
	<input type="hidden" name="_path" value="{urlencode(currentURL())}" />
	<input type="hidden" name="goToApprove" value="{$goToApprove}" />
	<input type="hidden" name="facility" value="{$facility->pubid}" />
	
	<table id="room-table" cellpadding="5" cellspacing="0">
		<tr>	
			<td colspan="4"><strong>{if $goToApprove == 1}Admission{else} Room Assignment{/if} Date &amp; Time</strong> <input type="text" id="schedule-datetime" name="datetime_admit" value="{$datetime|date_format:"%m/%d/%Y %I:%M %P"}" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<th>Room</th>
			<th>Patient Name</th>
			<th>Admission Date</th>
			<th>Scheduled Discharge Date</th>
		</tr>
		{foreach $rooms as $room}
			{if $room->patient_admit_pubid != ''}
				{$occupant = CMS_Patient_Admit::generate()}
				{$occupant->load($room->patient_admit_pubid)}
				{$occupantSchedule = CMS_Schedule::generate()}
				{$occupantSchedule->load($room->schedule_pubid)}
			{else}
				{$occupant = false}
			{/if}

		<!--table to display all current patients -->
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">

			<td>
				<input type="radio" name="room" value="{$room->pubid}"{if $occupant != false && $occupant->id == $patient->id} checked{/if} />
				<input type="hidden" name="previous_room" value="{$schedule->room}" />
			</td>		
			<td>{$room->number}</td>
			{if $occupant != false}
				<td>{$occupant->fullName()}</td>
				<td class="text-center">{$room->datetime_admit|date_format: "%b %e %Y"}</td>
				<td class="text-center">{$room->datetime_discharge|date_format: "%b %e %Y"}</td>
			{else}
				<td></td>
				<td></td>
				<td></td>	
			{/if}
		</tr>
		{/foreach}
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="6" align="right"><input type="submit" value="Submit" /></td>
		</tr>
	</table>
	
</form>














{* Layout changed on 2012-02-23 by khendershot to match look of other similar pages
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
{foreach $rooms as $room}
	{if $room@iteration == 1}
		<td width="50%" valign="top">
	{elseif $room@iteration == ceil($room@total / 2)}
		</td><td width="50%" valign="top">
	{/if}
	<div style="padding: 4px 0px;">
		{if $room->patient_admit_pubid != ''}
			{$occupant = CMS_Patient_Admit::generate()}
			{$occupant->load($room->patient_admit_pubid)}
			{$occupantSchedule = CMS_Schedule::generate()}
			{$occupantSchedule->load($room->schedule_pubid)}
		{else}
			{$occupant = false}
		{/if}
		<input type="radio" name="room" value="{$room->pubid}"{if $occupant != false && $occupant->id == $patient->id} checked{/if} /> <span class="{if $occupant != false && $occupant->id != $patient->id}text-grey{else}text-black{/if}">{$room->number}</span>
		{if $occupant != false}
			{if $room->status == 'Under Consideration'}
				(Pending) Scheduled for:
			{else}
				Occupied by:
			{/if} 
			{if $occupant->id == $schedule->patient_admit}
				<strong>{$occupant->fullName()}</strong>
			{else}
				<span class="text-grey">{$occupant->fullName()}</span>
			{/if}
			<br />
			<div style="margin-left: 28px;">
			{if $room->status == 'Under Consideration'}
				From
			{else}
				Since
			{/if}
			{$room->datetime_admit|datetime_format}
			{if $room->datetime_discharge != ''}
				, until 
				{if $room->discharge_to == 'Discharge to Hospital (Bed Hold)'}
					{$room->datetime_discharge|datetime_format}<br />
					<span style="background-color: yellow;">Bed hold until {$room->datetime_discharge_bedhold_end|datetime_format}</span>
				{else}
					{$room->datetime_discharge|datetime_format}
				{/if}
			{/if}
			{scheduleMenu schedule=$occupantSchedule}
			</div>
		{else}
			<i style="color: #297823;">Unoccupied</i>
		{/if}
	</div>
	{if $room@last == true}
	</td>
	{/if}
	<br />
{/foreach}
<br />
		</td>
	</tr>
</table>
<br />
<input type="submit" value="Submit" />
</form>
*}