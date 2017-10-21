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

$(".room").change(function() {
	if ($(this).attr("placeholder") != "") {
		$(".occupant").val($(this).attr("placeholder"));
	} 
});


{/jQueryReady}

{$patient = $schedule->getPatient()}
{$facilities = $auth->getRecord()->getFacilities()} 


<h1 class="text-center">Transfer Room for {$patient->fullName()}</h1>
<h2 class="text-center">at {$facility->name}</h2>

<br />
<br />
<form method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="facility" />
	<input type="hidden" name="action" value="submitRoomTransfer" />
	<input type="hidden" name="schedule" value="{$schedule->pubid}" />
	<input type="hidden" name="_path" value="{urlencode(currentURL())}" />
	<input type="hidden" name="goToApprove" value="{$goToApprove}" />
	<input type="hidden" name="facility" value="{$facility->pubid}" />
	
	<table id="room-table" cellpadding="5" cellspacing="0">
		<tr>	
			<td colspan="4"><strong>Room Transfer Date &amp; Time</strong> <input type="text" id="schedule-datetime" name="datetime_admit" value="{$datetime|date_format:"%m/%d/%Y %I:%M %P"}" /></td>
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
		<tr bgcolor="{cycle values="#f4f4f4,#ffffff"}">

			<td>
				<input type="radio" class="room" name="room" {if $occupant != false}placeholder="{$occupantSchedule->pubid}"{/if} value="{$room->pubid}"{if $occupant != false && $occupant->id == $patient->id} checked{/if} />
				<input type="hidden" class="occupant" name="occupant" value="" />
			</td>		
			<td>{$room->number}</td>
			{if $occupant != false}
				<td>
					{$occupant->fullName()}
					
				</td>
				<td class="text-center">{$room->datetime_admit|date_format: "%b %e %Y"}</td>
				<td class="text-center">{$room->datetime_discharge|date_format: "%b %e %Y"}</td>
			{else}
				<td><input type="hidden" class="occupant-check" value="" /></td>
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