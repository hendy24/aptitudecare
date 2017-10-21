{setTitle title="Other Home Health Details"}
<script>

	function goBack() {
		window.history.back();
	}

</script>
{include file="patient/patient_search.tpl"}
{include file="patient/export_icons.tpl"}
<br />
<h1 class="text-center">Discharge Service Disposition Details</h1>
<h2 class="text-center">Other Home Health</h2>

<div class="left">
	<input type="button" value="Return To Previous Page" onclick="goBack()">
</div>
<br />
<br />
<br />

<table cellpadding="5" cellspacing="0">
	<tr>
		<th>Home Health Name</th>
		<th>Number of Discharges</th>
	</tr>
	{foreach $data as $d}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td><a href="{$SITE_URL}/?page=report&action=discharge_service_details&facility={$facility->pubid}&view={$view}&year={$year}&date_start={$date_start|date_format:"%Y-%m-%d"}&location_id={$d->pubid}">{$d->name}</a></td>
			<td align="center">{$d->count}</td>
		</tr>
	{/foreach}
</table>