{setTitle title="{$facility->name} Census"}
	
{jQueryReady}
	$("#datetime").datetimepicker({
		showOn: "button",
		buttonImage: "{$ENGINE_URL}/images/icons/calendar.png",
		buttonImageOnly: true,
		timeFormat: "hh:mm tt",
		onClose: function(dateText, inst) {
			location.href = SITE_URL + '/?page=facility&action=census&facility={$facility->pubid}&datetime=' + dateText + '&_path={urlencode(currentURL())}';
		}
		
	});
	
	$("#facility").change(function(e) {
		window.location.href = SITE_URL + '/?page=facility&action=census&facility=' + $("option:selected", this).val() + '&datetime={$datetime}&type={$type}';
	})
	
	$("#type").change(function(e) {
	    window.location.href = SITE_URL + '/?page=facility&action=census&facility={$facility->pubid}&datetime={$datetime}&type=' + $("option:selected", this).val();
	});
	
	$(".schedule-datetime").datetimepicker({
		showOn: "button",
		buttonImage: "{$ENGINE_URL}/images/icons/calendar.png",
		buttonImageOnly: true,
		timeFormat: "hh:mm tt",
		stepMinute: 15,
		hour: 11,
		onSelect: function(dateText, inst) {
			$(this).parent().parent().find(".discharge-datetime").html(dateText);
			$(this).parent().parent().css("background-color", "#FF6A6A");
		},
		onClose: function(dateText, inst) {
			requestData =  { page: "facility", action: "save_discharge", pubid: $(this).attr('rel'), date: dateText };
			$.post(SITE_URL, requestData);
			
				
		}
		
	});
	
	$("#submit-button").click(function(e) {
		e.preventDefault();
		window.location = SITE_URL + "/?page=patient&action=search_results&patient_name=" + $("#patient-search").val();
	});

{/jQueryReady}

{if $facility == ''}
	<h1 class="text-center">AHC Facilities Census Page</h1>

	<br />
	<br />
{else}
<div class="right clear"><a href="{$SITE_URL}/?page=facility&action=census&facility={$facility->pubid}&export=excel"><img src="{$SITE_URL}/images/icons/file_xls.png" style="height: 42px;" /></a></a> <a href="{$SITE_URL}/?page=facility&action=census&facility={$facility->pubid}&export=pdf" type="_blank"><img src="{$SITE_URL}/images/icons/file_pdf.png" style="height: 42px;" /></a></a></div>
<h1 class="page-header">Census for {$facility->name}<br /><span class="text-16">on {datetimepickerformat($datetime)|date_format: "%a, %b %e, %Y at %l:%M %P"}</span> <input type="hidden" id="datetime" value="{datetimepickerformat($datetime)}" /></h1>

{/if}
{$facilities = $auth->getRecord()->getFacilities()}
<div id="census-options">
	<select id="facility">
		<option value="">Please Select a facility&nbsp;&nbsp;</option>
		{foreach $facilities as $f}
			<option value="{$f->pubid}"{if $f->id == $facility->id} selected{/if}>{$f->name}</option>
		{/foreach}
	</select>
	<div style="float: right; margin-bottom: 20px">
		<strong>Show:</strong>
		<select id="type">
			<option value="all"{if $type == 'all'} selected{/if}>All Rooms</option>
			{if $facility->short_term}
				<option value="scheduled"{if $type == 'scheduled'} selected{/if}>Scheduled Rooms</option>
				<option value="empty"{if $type == 'empty'} selected{/if}>Empty Rooms&nbsp;&nbsp;</option>
			{else}
				<option value="short_term"{if $type == 'short_term'} selected{/if}>Short-term patients</option>
				<option value="long_term"{if $type == 'long_term'} selected{/if}>Long-term patients&nbsp;&nbsp;</option>
			{/if}
		</select>
	</div>
</div>

{if $facility != ''}
	{if $type != 'empty'}
		{if !empty($physicians)}
			<div id="physican-admits" class="grow">
				<div class="physician-stats inner-grow">
					<h2>Attending Physicians</h2>
					{foreach $physicians as $k => $p}
						{$physician = CMS_Physician::generate()}
						{$physician->load($k)}
						{if $physician->id != ''}			
							<p>{$physician->last_name}, {$physician->first_name}: <span class="right">{$p}</span></p>
						{/if}
					{/foreach}
					<p align="right">Total: <strong>{$physicianTotal}</strong></p>
				</div>
			</div>
		{/if}
	{/if}
	<div class="census-info success">
		{$datetime|date_format: "%B"} Avg LoS:&nbsp; <span class="text-16">{$avgLength}</span> days
	</div>
	<div class="census-info {if $adc >= $adcGoal}success{else} alert{/if}">
		{$datetime|date_format: "%B"} ADC:&nbsp; <span class="text-16">{$adc}</span>
	</div>
	
	{if $type == 'assigned' || $type == 'all'}
		<div class="census-info {if $assignedRooms == $numOfRooms} success {else} alert{/if}">
			Current Census:&nbsp; <span class="text-16">{$assignedRooms}</span> <span class="text-11">of</span> {$numOfRooms}
		</div>
	{/if}	
	
	<form name="patient-search" accept="post">
		<div id="facility-search-box">
			<input type="text" name="search_patient" id="patient-search" placeholder="Enter the patients' name" size="30" /> <input type="submit" value="Search" id="submit-button" />
		</div>
	</form>


{/if}

<table id="census-report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Room</th>
		<th>Patient Name</th>
		<th>&nbsp;</th>
		<th>Date of Birth</th>
		<th>Admission<br />Date</th>
		{* <th>Scheduled<br />Discharge Date</th> *}
{* 		<th>&nbsp;</th>
 *}		<th>PCP</th>
		<th>Attending<br />Physician</th>
		<th>Surgeon/Specialist</th>
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
	<tr class="census border-bottom {if $room->status == 'Under Consideration'}under-consideration{/if}" {if $room->datetime_discharge_bedhold_end != ''} bgcolor="yellow" {elseif $room->is_complete == 0 && $room->is_complete != null && $room->datetime_discharge < $datetime} bgcolor="#A65878"  {elseif $room->datetime_discharge != ''} bgcolor="#FF6A6A" {elseif $occupant == false} bgcolor="#FFA1A1" {elseif $room->transfer_request} bgcolor="orange"{elseif $occupantSchedule->long_term}bgcolor="#e8e8e8"{else}bgcolor="#ffffff"" {/if}>

		{if $occupant != false}
			<td class="text-center">{$room->number}</td>
			<td style="text-align: left;">{$occupant->fullName()}</td>
			<td style="text-align: left; width: 37px;">{scheduleMenu schedule=$occupantSchedule}</td>
			<td>{$occupant->birthday|date_format: "%m/%d/%Y"}</td>
			<td>{$room->datetime_admit|date_format: "%m/%d/%Y"}</td>
{* 			<td class="discharge-datetime">{if $room->datetime_discharge_bedhold_end != ''}
				Hold until<br />{$room->datetime_discharge_bedhold_end|date_format: "%m/%d/%Y %I:%M %P"}
				{elseif $room->datetime_sent != '' && $room->is_complete == 0 && $room->datetime_discharge == ''}
				Sent on:<br />
				{$room->datetime_sent|date_format: "%m/%d/%Y %I:%M %P"}
				{else}
				{$room->datetime_discharge|date_format: "%m/%d/%Y %I:%M %P"}
				{/if}
			</td>
			<td><input type="hidden" name="schedule" class="schedule-datetime" rel="{$occupantSchedule->pubid}" value="{datetimepickerformat($occupantSchedule->datetime_discharge)}" /></td>

 *}			{if $occupant->doctor_id != ''}
 			{$pcp = CMS_Physician::generate()}
 			{$pcp->load($occupant->doctor_id)}
			<td>{$pcp->last_name}, {$pcp->first_name}</td>
			{else}
			<td>&nbsp;</td>
			{/if}
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
		{/if}
		{if $occupant == false}
			{$roomEmptyDate = CMS_Room::generate()}
			{$emptyDate = $roomEmptyDate->getEmptyRoomDate($room->id, $facility->id)}
			{foreach $emptyDate as $d}	
			
				<td>{$room->number}</td>
				<td></td>
				<td></td>
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
<div class="color-code background-yellow">Patient has already been discharged, but there is a current Bed Hold</div>
<div class="color-code background-purple">Patient has been sent to the hospital, but not discharged</div>
<div class="color-code background-red">Patient has been scheduled to be discharged.</div>
<div class="color-code background-orange">There has been a request to transfer to a different AHC facility.</div>
<div class="color-code background-blue">Patient has not yet been approved.</div>




