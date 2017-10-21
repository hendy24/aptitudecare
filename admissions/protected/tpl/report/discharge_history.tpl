{jQueryReady}
	$('#selectFacility').change(function() {
		window.location = SITE_URL + '/?page=report&action=discharge_history&facility=' + $('#selectFacility option:selected').val() + '&week_start={"last Sunday - 1 week"|date_format: "Y-m-d"}';
	});
{/jQueryReady}

<h1 class="text-center">Discharge History</h1>
<h2 class="text-center">for {$facility->name}</h2>

<div class="sort-left">
	<select name="facility" id="selectFacility">
		<option value="">Select facility...</option>
		{foreach $facilities as $f}
		<option value="{$f->pubid}" {if $facility->pubid == $f->pubid} selected {/if}>{$f->name}</option>
		{/foreach}
	</select>
</div>

<br />
<br />
<br />
<br />

<table id="info-data" cellpadding="5">
	<tr>
		<th>Patient Name</th>
		<th>Discharge Date</th>
		<th>Discharge Disposition</th>
		<th>Service Disposition</th>
		<th>Discharged To</th>
		<th>Phone</th>
	</tr>
	
	{foreach $dischargeHistory as $dh}
		<tr>
			<td>{$dh->last_name}, {$dh->first_name}</td>
			<td>{$dh->datetime_discharge|date_format: "m/d/Y"}</td>
			<td>{$dh->discharge_disposition}</td>
			<td>{$dh->service_disposition}</td>
			<td>{$dh->hospital}</td>
			<td>{$dh->discharge_phone|default: $dh->phone}</td>
		</tr>
	{/foreach}
</table>
<br />
<br />
<div id="pagination">
	{$lastWeek = date('Y-m-d', strtotime('last Sunday - 1 week'))}
	<a href="{$SITE_URL}/?page=report&action=discharge_history&facility={$facility->pubid}&week_start={$lastWeek}" {if $start_date == $lastWeek} class="selected-page"{/if}>Last Week</a>
	&nbsp;|&nbsp;
	{$week2 = date('Y-m-d', strtotime('last Sunday - 2 weeks'))}
	<a href="{$SITE_URL}/?page=report&action=discharge_history&facility={$facility->pubid}&week_start={$week2}" {if $start_date == $week2} class="selected-page"{/if}>Two Weeks Ago</a> 
	&nbsp;|&nbsp;
	{$week3 = date('Y-m-d', strtotime('last Sunday - 3 week'))}
	<a href="{$SITE_URL}/?page=report&action=discharge_history&facility={$facility->pubid}&week_start={$week3}" {if $start_date == $week3} class="selected-page"{/if}>Three Weeks Ago</a>
	&nbsp;|&nbsp;
	{$week4 = date('Y-m-d', strtotime('last Sunday - 4 week'))}
	<a href="{$SITE_URL}/?page=report&action=discharge_history&facility={$facility->pubid}&week_start={$week4}" {if $start_date == $week4} class="selected-page"{/if}>Four Weeks Ago</a>	
</div>