{setTitle title="AHC Census Page"}
	
{jQueryReady}
$("#datetime").datetimepicker({
	showOn: "button",
	buttonImage: "{$ENGINE_URL}/images/icons/calendar.png",
	buttonImageOnly: true,
	ampm: true,
	hour: 12,
	minute: 45,
	onClose: function(dateText, inst) {
		location.href = SITE_URL + '/?page=facility&action=roomStatus&facility={$facility->pubid}&datetime=' + dateText + '&_path={urlencode(currentURL())}';
	}
	
});

$("#facility").change(function(e) {
	window.location.href = SITE_URL + '/?page=facility&action=roomStatus&facility=' + $("option:selected", this).val() + '&datetime={$datetime}&type={$type}';
})

{/jQueryReady}

{jQueryReady}
$("#type").change(function(e) {
    window.location.href = SITE_URL + '/?page=facility&action=roomStatus&facility={$facility->pubid}&datetime={$datetime}&type=' + $("option:selected", this).val();
});
{/jQueryReady}

{if $facility == ''}
	<h1 class="text-center">AHC Facilities Census Page</h1>

	<br />
	<br />
{else}
<h1 class="text-center">Census for {$facility->name}<br /><span class="text-16">on {datetimepickerformat($datetime)|date_format: "%a, %b %e, %Y at %l:%M %P"}</span> <input type="hidden" id="datetime" value="{datetimepickerformat($datetime)}" /></h1>
{/if}
{$facilities = $auth->getRecord()->getFacilities()}
<select id="facility">
	<option value="">Please Select a facility&nbsp;&nbsp;</option>
	{foreach $facilities as $f}
		<option value="{$f->pubid}"{if $f->id == $facility->id} selected{/if}>{$f->name}</option>
	{/foreach}
</select>
<div style="float: right; margin-bottom: 20px">
	<strong>Show:</strong>
	<select id="type">
		<option value="all"{if $type == 'all'} selected{/if}>All rooms</option>
		<option value="empty"{if $type == 'empty'} selected{/if}>Just empty rooms</option>
		<option value="scheduled"{if $type == 'scheduled'} selected{/if}>Just assigned rooms&nbsp;&nbsp;</option>
	</select>
</div>
{if $type == 'empty' || $type == 'all'}<br /><br />There are currently <strong>{$emptyRooms} empty rooms</strong>.<br />{/if}
<br />
{if $facility != ''}
Number of Patients by Physician:</br >
	<div id="physican-admits">
		{foreach $physicians as $p}
			{$physician = CMS_Physician::generate()}
			{$physician->load($p->physician_id)}
			{if $physician->id != ''}
				<strong>{$physician->last_name}, {$physician->first_name}</strong>: {count(CMS_Schedule::numberOfActivePatientsByPhysician($facility->id, $physician->id, $datetime))}<br />
			{/if}
		{/foreach}
	</div>
{/if}

<table id="census-report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Room</th>
		<th>Patient Name</th>
		<th>&nbsp;</th>
		<th>Admission<br />Date</th>
		<th>Scheduled<br />Discharge Date</th>
		<th>Attending<br />Physician</th>
		<th>Surgeon/Specialist</th>
		<th>Diagnosis</th>
	</tr>
	{foreach $rooms as $room}
		{if $room->patient_admit_pubid != ''}
			{$occupant = CMS_Patient_Admit::generate()}
			{$occupant->load($room->patient_admit_pubid)}
			{$occupantSchedule = CMS_Schedule::generate()}
			{$occupantSchedule->load($room->schedule_pubid)}
			{$codes = CMS_Icd9_Codes::generate()}
			{$codes->load($occupant->icd9_id)}

		{else}
			{$occupant = false}
		{/if}

	<!--table to display all current patients -->
	<tr class="census" {if $room->datetime_discharge_bedhold_end != ''} bgcolor="yellow" {elseif $room->is_complete == 0 && $room->is_complete != null && $room->datetime_discharge < $datetime} bgcolor="#A65878"  {elseif $room->datetime_discharge != ''} bgcolor="#FF6A6A" {elseif $occupant == false} bgcolor="#FFA1A1" {else}bgcolor="{cycle values="#d0e2f0,#ffffff"}" {/if}>

		{if $occupant != false}
			<td class="text-center">{$room->number}</td>
			{if $room->status == 'Under Consideration'}
				<td class="text-blue">{$occupant->fullName()}</td>
			{else}
			<td align="left">{$occupant->fullName()}</td>
			{/if}
			<td align="left">{scheduleMenu schedule=$occupantSchedule}</td>
			<td>{$room->datetime_admit|date_format: "%m/%d/%Y"}</td>
			<td>{if $room->datetime_discharge_bedhold_end != ''}
				Hold until<br />{$room->datetime_discharge_bedhold_end|date_format: "%m/%d/%Y"}
				{elseif $room->datetime_sent != '' && $room->is_complete == 0 && $room->datetime_discharge == ''}
				Sent on:<br />
				{$room->datetime_sent|date_format: "%m/%d/%Y"}
				{else}
				{$room->datetime_discharge|date_format: "%m/%d/%Y"}
				{/if}
			</td>
			{if $occupant->physician_id != ''}
			{$physician = CMS_Physician::generate()}
			{$physician->load($occupant->physician_id)}
			<td>{$physician->last_name}, {$physician->first_name}</td>
			{else}
			<td>{$occupant->physician_name}</td>
			{/if}
			{if $occupant->ortho_id != ''}
			{$ortho = CMS_Physician::generate()}
			{$ortho->load($occupant->ortho_id)}
			<td>{$ortho->last_name}, {$ortho->first_name}</td>
			{else}
			<td>{$occupant->surgeon_name}</td>
			{/if}
			{if $codes->short_desc != ''}
				<td>{$codes->short_desc} [{$codes->code}]</td>
			{else}
				<td></td>
			{/if}
		{/if}
		{if $occupant == false}
			{$roomEmptyDate = CMS_Room::generate()}
			{$emptyDate = $roomEmptyDate->getEmptyRoomDate($room->id, $facility->id)}
			{foreach $emptyDate as $d}
				<td>{$room->number}</td>
				<td align="left" colspan="2"><strong>Empty Since:</strong><br />{$d->empty_date|date_format: "%m/%d/%Y at %I:%M %P"}</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			{/foreach}
		{/if}
	</tr>
	{/foreach}
</table>
<br />
<strong>Color Code Key:</strong><br />
<br />
<div class="background-yellow">Patient has already been discharged, but there is a current Bed Hold</div>
<div class="background-purple">Patient has been sent to the hospital, but not discharged</div>
<div class="background-red">Patient has been scheduled to be discharged.</div>




