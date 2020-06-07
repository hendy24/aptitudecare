
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
								location.reload();
								/*
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
								*/
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

		$(".move-patient").on("click", function(e) {
			e.preventDefault();
			//var deleteClass = $(this).children("img").attr("class");
			//var dataRow = $(this).parent().parent();
			var item = $(this);
			var public_id = item.find(".public-id").val();
			var roomNumber = item.find(".room-number").val();
			var patientName = item.find(".patient-name").val();
			
			console.log(public_id);
			console.log(roomNumber);
			console.log(patientName);
			
			var roomList = $.unique($("td.room-number").toArray().map(
				function(i){
					return i.innerText;
				})
			);
			
			//depopulate the room list.
			$("#move-patient-dialog select").empty();
			
			//populate the room list
			$.each(roomList, function(i, item){
				$("#move-patient-dialog select").append($('<option>', { 
					value: item,
					text : item 
				}));
			});
			
			//select the current room number
			$("#move-patient-dialog select").val(roomNumber);
			
			//set dialog to be the name
			$("#move-patient-dialog #patient-name").text(patientName); 
			
			$("#move-patient-dialog").dialog({
				buttons: {
					"Move": function() {
						var newRoom = $("#move-patient-dialog select option:selected").text();
						//console.log(newRoom);
						if(newRoom != roomNumber)
						{
							console.log("CHANGED! from " + roomNumber + " to: " + newRoom);
							$.ajax({
								type: 'post',
								url: SITE_URL,
								data: {
									page: "Schedules",
									action: 'movePatientRooms',
									id: public_id,
									oldroom: roomNumber,
									newroom: newRoom,
								},
								success: function() {
									location.reload();
							
								}
							});
						}
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


<div class="row"">
	<div class="col-lg-4">
		{$this->loadElement("module")}
	</div>
	<div class="col-lg-4 text-center">
		{$this->loadElement("selectLocation")}
	</div>

	<div class="col-lg-4">
		<a id="tray-card-select-date" href="{$SITE_URL}/?module=Dietary&amp;page=patient_info&amp;action=meal_tray_card&amp;location={$location->public_id}&amp;patient=all&amp;pdf2=true" class="btn btn-primary pull-right">Tray Cards</a>

		<a id="meal-order-form-select-date" href="{$SITE_URL}/?module=Dietary&amp;page=menu&amp;action=meal_order_form&amp;location={$location->public_id}&amp;pdf2=true" class="btn btn-primary pull-right" target="_blank">Meal Order Forms</a>
	</div>
</div>

<h1>Current Patients</h1>
<input type="hidden" id="location" value="{$location->public_id}">
<input type="hidden" name="currentUrl" value="{$current_url}">
<table id="patient-info">
	<tr>
		<th>Room</th>
		<th style="width: 35%">Patient Name</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th style="width: 40px">&nbsp;</th>
		<th>Room</th>
		<th style="width: 35%">Patient Name</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
	</tr>
	<tr>
	{foreach from=$currentPatients key=k item=patient name=count}
		<td class="room-number" value="{$patient->number}">{$patient->number}</td>

		{if get_class($patient) == "Patient" and $patient->patient_admit_id != NULL}
		<td class="{$k} patient-name">{$patient->last_name}, {$patient->first_name}</td>
		<td class="{$k}">{$dietaryMenu->menu($patient, $selectedLocation, $modEnabled, $k)}</td>
		<td class="{$k}">
			<a href="?module=Dietary&amp;page=patient_info&amp;action=diet&amp;patient={$patient->public_id}">
				<img src="{$FRAMEWORK_IMAGES}/edit.png" class="{$k}" style="position: relative; top: 7px;" alt="">
			</a>
			{*
			{if !$modEnabled}
			<a href="#" class="delete-patient">
				<img src="{$FRAMEWORK_IMAGES}/delete.png" class="{$k}" style="position: relative; top: 7px;" alt="">
				<input type="hidden" name="public_id" class="public-id" value="{$patient->public_id}">
				<input type="hidden" name="room_number" class="room-number" value="{$patient->number}">
			</a>
				<input type="hidden" class="patient-id" value="{$patient->public_id}">
			{/if} *}
			
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
<div id="move-patient-dialog" title="Move Patient" style="display:none;">
	<p>Where do you want to move <span id="patient-name"> </span>?</p>
	<p>You can have multiple people in one room!</p>
	<select id="roomlist">
	</select>
</div>
<div id="tray-card-dialog" title="Select Date">
	<p>Select the date for which you would like to print the tray cards.</p>
	<input type="text" id="selected-date" class="date-picker">
</div>
<div id="meal-order-dialog" title="Select Date">
	<p>Select the date for which you would like to print the meal order form.</p>
	<input type="text" id="form-date" class="date-picker">
</div>
