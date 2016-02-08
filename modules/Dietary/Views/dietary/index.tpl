<!-- modules/Dietary/Views/dietary/index.tpl -->

<script type="text/javascript">
	$(document).ready(function() {
		$("#location").change(function() {
			var location = $("#location option:selected").val();
			window.location.href = SITE_URL + "/?module=Dietary&location=" + location;
		});

		$(".add-patient").on("click", function (e) {
			e.preventDefault();
			var roomNumber = $(this).next().val();
			var location = $("#location").val();
			window.location.href = SITE_URL + "/?module=Dietary&page=patient_info&action=add_patient&location=" + location + "&number=" + roomNumber;
		});

		$(".delete-patient").on("change", function() {
			$(".add-patient").on("click", function (e) {
				e.preventDefault();
				var roomNumber = $(this).next().val();
				var location = $("#location").val();
				window.location.href = SITE_URL + "/?module=Dietary&page=patient_info&action=add_patient&location=" + location + "&number=" + roomNumber;
			});
		});

		$(".delete-patient").on("click", function(e) {
			e.preventDefault();
			var deleteClass = $(this).children("img").attr("class");
			var dataRow = $(this).parent().parent();
			console.log(dataRow);
			var item = $(this);
			$("#dialog").dialog({
				buttons: {
					"Confirm": function() {
						var row = item.children().next($(".public-id"));
						var roomNumber = item.find(".room-number").val();
						var id = row.val();

						$.ajax({
							type: 'post',
							url: SITE_URL,
							data: {
								page: "Patients",
								action: 'deleteId',
								id: id,
							},
							success: function() {
								$("."+deleteClass).empty();
								$("."+deleteClass).first().html('<input type="button" class="add-patient" value="Add Patient"><input type="hidden" class="room" value="' + roomNumber + '">');
								//Have to rebind add-patient
								$(".add-patient").on("click", function (e) {
									e.preventDefault();
									var roomNumber = $(this).next().val();
									var location = $("#location").val();
									window.location.href = SITE_URL + "/?module=Dietary&page=patient_info&action=add_patient&location=" + location + "&number=" + roomNumber;
								});
								// need to add back in the add patient option
							}
						});
						$(this).dialog("close");

					},
					"Cancel": function() {
						$(this).dialog("close");
					}
				}
			});
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
		<a href="{$SITE_URL}/?module=Dietary&amp;page=patient_info&amp;action=traycard&amp;location={$location->public_id}&amp;patient=all" class="button">Tray Cards</a>
		<a href="{$SITE_URL}/?module=Dietary&amp;page=menu&amp;action=meal_order_form&amp;location={$location->public_id}" class="button" target="_blank">Meal Order Forms</a>
	</div>
</div>

<h1>Current Patients</h1>
<input type="hidden" id="location" value="{$location->public_id}">
<input type="hidden" name="currentUrl" value="{$currentUrl}">
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
		<td class="{$k}">{$patient->last_name}, {$patient->first_name}</td>
		<td class="{$k}">{$dietaryMenu->menu($patient)}</td>
		<td class="{$k}">
			{if !$modEnabled}
			<a href="#" class="delete-patient">
				<img src="{$FRAMEWORK_IMAGES}/delete.png" class="{$k}" style="position: relative; top: 7px;" alt="">
				<input type="hidden" name="public_id" class="public-id" value="{$patient->public_id}">
				<input type="hidden" name="room_number" class="room-number" value="{$patient->number}">
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


<div id="dialog" title="Confirmation Required">
	<p>Are you sure you want to delete this patient? This cannot be undone.</p>
</div>
