{setTitle title="Schedule Discharges"}
<script src="{$SITE_URL}/js/discharges.js" type="text/javascript"></script>
{jQueryReady}
$("#facility").change(function(e) {
	window.location.href = SITE_URL + '/?page=facility&action=schedule_discharges&facility=' + $("option:selected", this).val();
})
{/jQueryReady}
<h1 class="text-center">Schedule Discharges</h1>

<div id="census-options">
	<select id="facility">
		<option value="">Please Select a facility&nbsp;&nbsp;</option>
		{foreach $facilities as $f}
			<option value="{$f->pubid}"{if $f->id == $facility->id} selected{/if}>{$f->name}</option>
		{/foreach}
	</select>
</div>

<div id="current-patients">
	<table cellpadding="5" cellspacing="5" id="current-patient-table">
		<tr>
	{foreach $current as $c}
		{if $c->datetime_discharge == ''}
			{$occupant = CMS_Patient_Admit::generate()}
			{$occupant->load($c->patient_admit_pubid)}
			{$occupantSchedule = CMS_Schedule::generate()}
			{$occupantSchedule->load($room->schedule_pubid)}
			{$ptName = substr($occupant->fullName(),0,20)|cat:"..."}
			<td class="current-patient" droppable="true" ><div class="select-patient" draggable="true">{$c->number} {$ptName}<input type="hidden" name="pubid" value="{$c->schedule_pubid}"></div></td>
			{if $c@iteration is div by 5}
			</tr>
			<tr>
			{/if}				
		{/if}
	{/foreach}
		</tr>
	</table>
</div>

<div id="week-nav">

	<a href="{$SITE_URL}/?page=facility&amp;action=schedule_discharges&amp;weekSeed={$prevWeekSeed}"><img src="{$SITE_URL}/images/icons/prev-icon.png" /> Previous Week</a> 
	
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	
	<a href="{$SITE_URL}/?page=facility&amp;action=schedule_discharges&amp;weekSeed={$nextWeekSeed}">Next Week <img src="{$SITE_URL}/images/icons/next-icon.png" /></a>
	
</div>

<div id="discharge-calendar">
	{foreach $week as $day}
		<div class="discharge-day-text {if $day@last}discharge-day-text-last{/if}" >
			<h3 class="select-day">{$day|date_format:"%a, %b %e, %Y"}</h3>
			<input type="hidden" name="date" value="{$day}">
			<div class="discharge-day {cycle name="admitDayColumn" values="facility-day-box-blue, "}">
				{$discharges = $discharged[$day]}
				{foreach $discharges as $d}
					<div class="discharge-info">
						{$d->number} {$d->last_name}, {$d->first_name}
						<input type="hidden" name="pubid" value="{$d->schedule_pubid}">
					</div>
				{/foreach}
			</div>
			<div class="clear"></div>
		</div>
	{/foreach}
			
</div>
