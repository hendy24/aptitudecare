{setTitle title="AHC Reports"}
{include file="patient/export_icons.tpl"}

<h1 class="text-center">Facility Transfer Report<br /><span class="text-16">for {$facility->name}</span></h1>
{include file="report/index.tpl"}
	
<table id="report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Patient Name</th>
		<th>Admit Date</th>
		<th>Transfer Facility</th>
	</tr>	
	
	{foreach $transfers as $t}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td align="left">{$t->last_name}, {$t->first_name}</td>
			<td>{$t->datetime_admit|date_format: "m/d/Y"}</td>
			<td>{$t->transfer_from}</td>
		</tr>
	{/foreach}
</table>
