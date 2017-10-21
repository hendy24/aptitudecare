{setTitle title="AHC Reports"}
<h1 class="text-center">Discharge Report<br /><span class="text-16">for {$facility->name}</span></h1>
{include file="report/index.tpl"}


<div class="sort-right">
	<strong>Order by:</strong>
	<select id="orderby">
		{foreach $orderByOpts as $k => $v}
			<option value="{$k}"{if $orderby == $k} selected{/if}>{$v}</option>
		{/foreach}
	</select>
</div>
	<div class="sort-left">
	<strong>Filter by:</strong>
	<select id="filterby">
		<option value="">Select an option...</option>
		{foreach $filterByOpts as $k => $v}
			<option value="{$k}"{if $filterby == $k} selected{/if}>{$v}</option>
		{/foreach}
	</select>
</div>

<div class="sort-left2" id="view-by">
	<strong>View by:</strong>
	<select id="viewby">
		<option value="">Select...</option>		
		{if $filterby == 'discharge_disposition'}
			{foreach $filterData as $d}
				{if $d->discharge_disposition != ''}
					<option value="{$d->discharge_disposition}"{if $viewby == $d->discharge_disposition} selected{/if}>{$d->discharge_disposition}</option>
				{/if}
			{/foreach}
		{elseif $filterby =='service_disposition'}
			{foreach $filterData as $d}
				<option value="{$d->service_disposition}"{if $viewby == $d->service_disposition} selected{/if}>{$d->service_disposition}</option>
			{/foreach}
		{elseif $filterby == 'physician'}
			{foreach $filterData as $d}
				{$p = CMS_Physician::generate()}
				{$p->load($d->physician_id)}
				<option value="{$d->physician_id}"{if $viewby == $d->physician_id} selected{/if}>{$p->last_name}, {$p->first_name} M.D.</option>
			{/foreach}
		{/if}
	</select>
</div>
<div class="sort-left-phrase">The <strong>Average Length of Stay (LoS)</strong> for the selected time period is <strong>{$totalDays}</strong> days.</div>
	
<br />
<br />
<table id="report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Patient Name</th>
		<th>Admit Date</th>
		<th>Discharge Date</th>
		<th>Discharge Disposition</th>
		<th>Home Health</th>
		<th>LoS</th>
		<th>Attending Physician</th>
	</tr>
	{foreach $discharges as $d}
	<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
		<td style="text-align: left;">{$d->last_name}, {$d->first_name}</td>
		<td>{$d->datetime_admit|date_format:"%m/%d/%Y"}</td>
		<td>{$d->datetime_discharge|date_format:"%m/%d/%Y"}</td>
		
		<!-- Discharge Disposition -->
		<td>{if $d->discharge_disposition == "Group Home"  || $d->discharge_disposition == "Assisted Living"}<a class="dischargeInfo" href="/?page=coord&action=discharged_patient_info&patient={$d->getPatient()->pubid}&schedule={$d->SchedulePubid}&isMicro=1">{$d->discharge_disposition}</a>{else}{$d->discharge_disposition}{/if}</td>
		
		<!-- Home Health Disposition -->
		<td>{if $d->discharge_disposition == 'Home with AHC Home Health'}AHC Home Health{elseif $d->service_disposition == 'Other Home Health'}<a class="dischargeInfo" href="/?page=coord&action=discharged_patient_info&patient={$d->getPatient()->pubid}&schedule={$d->SchedulePubid}&isMicro=1">Other Home Health</a>{else}{$d->service_disposition}{/if}</td>
		
		<td>{$d->los($d->datetime_discharge, $d->datetime_admit)} days</td>
		{if $d->physician_id != ''}
		{$physician = CMS_Physician::generate()}
		{$physician->load($d->physician_id)}
		<td>{$physician->last_name}, {$physician->first_name} M.D.</td>
		{elseif $d->physician_name != ''}
		<td>{$d->physician_name|Default:"Not entered"}</td>
		{else}
		<td></td>
		{/if}
	</tr>
	{/foreach}
</table>
	
