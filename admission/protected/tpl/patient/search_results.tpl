{setTitle title="Search Results"}

{jQueryReady}
	var deletePatient = new Array();
	$(".schedule-status").change(function(e) {
		$.getJSON(SITE_URL , { page: "patient", action: "setScheduleStatus", schedule: $(this).attr("rel"), status: $("option:selected", this).val() }, function(json) {
			if (json.status == true) {
				jAlert("The patient's status has been changed.", "Success!", function(r) {
					window.parent.location.href = SITE_URL + "/?page=patient&action=search_results&patient_name={$searchName}";
				});
			} else {
				var msg = "";
				$.each(json.errors, function(i, v) {
					msg = msg + v + "\n";
				});
				jAlert(msg, "Error");
			}
		}, "json");
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
	
	$(".discharge-datetime").datetimepicker({
		showOn: "button",
		buttonImage: "{$ENGINE_URL}/images/icons/calendar.png",
		buttonImageOnly: true,
		timeFormat: "hh:mm tt",
		stepMinute: 15,
		hour: 11,
		onSelect: function(dateText, inst) {
			$(this).parent().parent().find(".discharge-datetime").html(dateText);
		},
		onClose: function(dateText, inst) {
			requestData =  { page: "facility", action: "save_discharge", pubid: $(this).attr('rel'), date: dateText };
			$.post(SITE_URL, requestData);
			
				
		}
		
	});
	
{/jQueryReady}

<input type="button" value="Return to Previous Page" onclick="history.go(-1)">
<h1 class="text-center">Search Results <br /><span class="text-18">for {$searchName}</span></h1>
	
<table id="report-table" cellpadding="5" cellspacing="0">

	<tr>
		<th>Patient Name</th>
		<th>&nbsp;</th>
		<th>Facility</th>
		<th>Hospital</th>
		<th>Physician Name</th>
		<th>Admission Date</th>
		<th>&nbsp;</th>
		<th>Discharge Date</th>
		<th>&nbsp;</th>
		<th>Schedule Status</th>
	</tr>
	
	{foreach $results as $result}
		{foreach $result as $r}
			{$schedule = CMS_Schedule::generate()}
			{$schedule->load($r->schedule_id)}
			<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
				<td>{$r->last_name}, {$r->first_name}</td>
				<td>{scheduleMenu schedule=$schedule}</td>
				<td>{$r->facilityName}</td>
				<td>{$r->hospitalName}</td>
				<td>{if $r->physicianLast != ''}{$r->physicianLast}, {$r->physicianFirst}{/if}</td>
				<td>{$r->datetime_admit|date_format: "m/d/Y"}</td>
				<td><input type="hidden" name="schedule" class="schedule-datetime" rel="{$schedule->pubid}" value="{datetimepickerformat($schedule->datetime_admit)}" /></td>
				<td class="discharge-datetime">{$r->datetime_discharge|date_format: "m/d/Y H:i a"}</td>
				<td><input type="hidden" name="schedule" class="discharge-datetime" rel="{$schedule->pubid}" value="{datetimepickerformat($schedule->datetime_discharge)}" /></td>
				<td>{$r->status}</td>
				
					
<!--
					<select class="schedule-status" rel="{$r->schedule->pubid}">
						{foreach $statusOptions as $option}
							<option value="{$option}" {if $r->status == $option} selected{/if}>{$option}</option>
						{/foreach}
					</select>

				</td> -->
				
			</tr>
		{/foreach}
	{/foreach}
	