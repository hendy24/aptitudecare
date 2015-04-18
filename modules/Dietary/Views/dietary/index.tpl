<script type="text/javascript">
	$(document).ready(function() {
		$("#location").change(function() {
			var location = $("#location option:selected").val();
			window.location.href = SITE_URL + "/?module=Dietary&location=" + location;
		});
	});
</script>


<div id="page-header">
	<div id="action-left">
		{$this->loadElement("module")}
	</div>
	<div id="center-title">
		{$this->loadElement("selectLocation")}
	</div>
	<div id="action-right">
		<a href="" class="button">Print</a>
	</div>
</div>


<h1>Current Patients</h1>
<table id="patient-info">
	<tr>
		<th>Room</th>
		<th>Patient Name</th>
		<th>&nbsp;</th>
		<th style="width: 20%">&nbsp;</th>
		<th>Room</th>
		<th>Patient Name</th>
		<th>&nbsp;</th>
	</tr>
	<tr>
	{foreach from=$currentPatients key=k item=patient name=count}
		<td>{$patient->number}</td>
		<td>{$patient->last_name}, {$patient->first_name}</td>
		<td>{$dietaryMenu->menu($patient)}</td>

	{if $smarty.foreach.count.iteration is div by 2}
		</tr>
		<tr>
	{else}
		<td>&nbsp;</td>
	{/if}
	{/foreach}
	</tr>
</table>
