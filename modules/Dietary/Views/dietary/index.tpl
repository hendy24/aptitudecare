<script type="text/javascript">
	$(document).ready(function() {
		$("#location").change(function() {
			var location = $("#location option:selected").val();
			window.location.href = SITE_URL + "/?module=Dietary&location=" + location;
		});

		$(".add-patient").click(function (e) {
			e.preventDefault();
			var roomNumber = $(this).next().val();
			var location = $("#location").val();
			window.location.href = SITE_URL + "/?module=Dietary&page=patient_info&action=add_patient&location=" + location + "&number=" + roomNumber;
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
<input type="hidden" id="location" value="{$location->public_id}">
<table id="patient-info">
	<tr>
		<th>Room</th>
		<th>Patient Name</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th style="width: 20%">&nbsp;</th>
		<th>Room</th>
		<th>Patient Name</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
	</tr>
	<tr>
	{foreach from=$currentPatients key=k item=patient name=count}
		<td class="room-number" value="{$patient->number}">{$patient->number}</td>

		{if get_class($patient) == "Patient"}
		<td>{$patient->last_name}, {$patient->first_name}</td>
		<td>{$dietaryMenu->menu($patient)}</td>
		<td>
			{if !$modEnabled}
			<a href="#" class="delete-patient">
				<img src="{$FRAMEWORK_IMAGES}/delete.png" style="position: relative; top: 7px;" alt="">
			</a>
				<input type="hidden" class="patient-id" value="{$patient->public_id}">
			{/if}
		</td>
		{else}

		<td>
			{if !$modEnabled}
			<input type="button" class="add-patient" value="Add Patient">
			<input type="hidden" class="room" value="{$patient->number}">
			{/if}
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		{/if}

	{if $smarty.foreach.count.iteration is div by 2}
		</tr>
		<tr>
	{else}
		<td>&nbsp;</td>
	{/if}
	{/foreach}
	</tr>
</table>
