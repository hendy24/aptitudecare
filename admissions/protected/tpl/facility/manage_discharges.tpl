{setTitle title="Manage Discharges"}
{jQueryReady}
$("#facility").change(function(e) {
	window.location.href = SITE_URL + '/?page=facility&action=manage_discharges&facility=' + $("option:selected", this).val();
})

$(".schedule-datetime").datetimepicker({
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


<h1 class="text-center">Manage Discharges</h1>

<div id="census-options">
	<select id="facility">
		<option value="">Please Select a facility&nbsp;&nbsp;</option>
		{foreach $facilities as $f}
			<option value="{$f->pubid}"{if $f->id == $facility->id} selected{/if}>{$f->name}</option>
		{/foreach}
	</select>
</div>

<table id="census-report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Room</th>
		<th>Patient Name</th>
		<th>&nbsp;</th>
		<th>Admission Date</th>
		<th width="125px">Discharge Date</th>
		<th width="75px">&nbsp;</th>
		<th width="80px">&nbsp;</th>
	</tr>
	{foreach $discharges as $d}
		{$schedule = CMS_Schedule::generate()}
		{$schedule->load($d->schedule_id)}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td>{$d->number}</td>
			<td>{$d->last_name}, {$d->first_name}</td>
			<td style="text-align: left;">{scheduleMenu schedule=$schedule}</td>
			<td>{$d->datetime_admit|date_format: "m/d/Y"}</td>
			<td class="discharge-datetime">{$d->datetime_discharge|date_format: "m/d/Y H:i a"}</td>
			<td><input type="hidden" name="schedule" class="schedule-datetime" rel="{$schedule->pubid}" value="{datetimepickerformat($schedule->datetime_discharge)}" /></td>
			<td><a href="{$SITE_URL}/?page=facility&amp;action=discharge_details&amp;schedule={$schedule->pubid}" class="button">Edit Details</a></td>
		</tr>
		
	{/foreach}
</div>
