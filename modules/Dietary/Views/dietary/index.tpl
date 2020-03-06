
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
								page: "Schedules",
								action: 'dischargePatient',
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


		$("#tray-card-select-date").on("click", function(e) {
			e.preventDefault();
			var url = $(this).attr("href");
			$("#tray-card-dialog").dialog({
				buttons: {
					"Submit": function() {
						var selectedDate = $("#selected-date").val();
						window.open(url + "&date=" + selectedDate, '_blank');
						$(this).dialog("close");
					}
				}
			});
		});

		$("#meal-order-form-select-date").on("click", function(e) {
			e.preventDefault();
			var url = $(this).attr("href");
			$("#meal-order-dialog").dialog({
				buttons: {
					"Submit": function() {
						var selectedDate = $("#form-date").val();
						window.open(url + "&start_date=" + selectedDate, '_blank');
						$(this).dialog("close");
					}
				}
			});
		});

	});
</script>
<script>
	$('#deleteModal').on('shown.bs.modal', function () {
			$('#myInput').trigger('focus')
		})
</script>

<div class="row">
	<div class="col-lg-4 col-md-6 col-sm-12">
		{$this->loadElement("module")}
	</div>
	<div class="col-lg-4 col-md-12 col-sm-12 text-center">
		{$this->loadElement("selectLocation")}
	</div>

	<div class="col-lg-4 col-md-6 col-sm-12 header-buttons">
		<a id="tray-card-select-date"
			href="{$SITE_URL}/?module=Dietary&amp;page=patient_info&amp;action=meal_tray_card&amp;location={$location->public_id}&amp;patient=all&amp;pdf=true"
			class="btn btn-primary pull-right" target="_blank">Tray Cards</a>

		<a id="meal-order-form-select-date"
			href="{$SITE_URL}/?module=Dietary&amp;page=menu&amp;action=meal_order_form&amp;location={$location->public_id}&amp;pdf=true"
			class="btn btn-primary pull-right" target="_blank">Meal Order Forms</a>
	</div>		
</div>







<div class="page-container">
	<h1 class="text-center">Current Patients</h1>
	<input type="hidden" id="location" value="{$location->public_id}">
	<input type="hidden" name="currentUrl" value="{$current_url}">
	<table id="patient-info">
		<tr>
			<th style="width:70px">Room</th>
			<th>Patient Name</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th style="width: 20%">&nbsp;</th>
			<th style="width:70px">Room</th>
			<th>Patient Name</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
		</tr>
		<tr>
		{foreach from=$currentPatients key=k item=patient name=count}
			<td class="room-number" value="{$patient->number}">{$patient->number}</td>

			{if get_class($patient) == "Patient"}
			<td class="{$k}">{$patient->last_name}, {$patient->first_name}</td>
			<td class="{$k}">{$dietaryMenu->menu($patient, $selectedLocation)}</td>
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
</div>

<div id="dialog" title="Confirmation Required">
	<p>Are you sure you want to delete this patient? This cannot be undone.</p>
</div>

<div id="tray-card-dialog" title="Select Date">
	<p>Select the date for which you would like to print the tray cards.</p>
	<input type="text" id="selected-date" class="date-picker">
</div>
