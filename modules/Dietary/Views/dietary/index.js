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


	$('#deleteModal').on('shown.bs.modal', function () {
		$('#myInput').trigger('focus')
	})


</script>