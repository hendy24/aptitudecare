{setTitle title="AHC Reports: 30 Day Discharge Phone Calls"}
{include file="patient/export_icons.tpl"}
<h1 class="text-center">30 Days Discharge Phone Calls<br /><span class="text-14">for</span><br /><span class="text-20">{$facility->name}</span></h1>
{include file="report/index.tpl"}



<table id="report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Room</th>
		<th>Patient Name</th>
		<th width="90px">Phone #</th>
		<th>Discharge Date</th>
		<th width="180px">Diagnosis</th>
		<th>Admitted From</th>
		<th>Discharge Disposition</th>
		<th>Service Disposition</th>
	</tr>
{foreach $data as $d}
	{$dl = CMS_Hospital::generate()}
	{$dl->load($d->discharge_location_id)}
	<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
		<td>{$d->number}</td>
		<td class="text-left">{$d->last_name}, {$d->first_name}</td>
		<td>{$d->phone}</td>
		<td>{$d->datetime_discharge|date_format:"%x"}</td>
		<td class="text-left">{$d->other_diagnosis}</td>
		<td>{$d->name}</td>
		<td>{$d->discharge_disposition}</td>
		{if $d->service_disposition == "Other Home Health"}
			{$hh = CMS_Hospital::generate()}
			{$hh->load($d->home_health_id)}
			<td>{$hh->name}</td>
		{else}
			<td>{$d->service_disposition}</td>
		{/if}
		
	</tr>
{/foreach}
</table>