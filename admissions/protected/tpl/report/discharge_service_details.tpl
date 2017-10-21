{setTitle title="Discharge Service Disposition Details"}

<script>

	function goBack() {
		window.history.back();
	}

</script>

{include file="patient/patient_search.tpl"}
{include file="patient/export_icons.tpl"}
<h1 class="text-center">Discharge Service Disposition Details</h1>
<h2 class="text-center">{$type}</h2>
<div class="left">
	<input type="button" value="Return To Previous Page" onclick="goBack()">
</div>
<br />
<br />
<br />
<table id="report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Patient Name</th>
		<th>Discharge To</th>
		<th>Discharge Disposition</th>
		<th>Discharge Location Name</th>
		<th>Discharge Date</th>
	</tr>
	{foreach $data as $d}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td align="left">{$d->last_name}, {$d->first_name}</td>
			<td>{$d->discharge_to}</td>
			<td>{$d->discharge_disposition}</td>
			<td>{$d->name}</td>
			<td>{$d->datetime_discharge|date_format:"%m/%d/%Y"}</td>
		</tr>
	{/foreach}