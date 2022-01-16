$("#location").change(function() {
	var location = $("#location option:selected").val();
	window.location.href = SITE_URL + "/?module=Dietary&location=" + location;
});

$(".show").removeClass("show");
$(".active").removeClass("active");
$("#dietarySection").addClass("show");
$("#current-residents").addClass("active");

$('#deleteModal').on('show.bs.modal', function(e) {

	var button = $(e.relatedTarget);
	var publicId = button.data('publicid');
	var location = $("#location").val();

	var residentName = $("#" + publicId);
	var editButton = residentName.next("td");
	var trash = editButton.next("td");
	var room = residentName.prev("td").attr("id");

	$('#delete').click(function() {
		$.ajax({
			type: 'post',
			url: SITE_URL,
			data: {
				module: "Admissions",
				page: "Schedules",
				action: 'dischargePatient',
				id: publicId,
			},
			success: function(e) {
				residentName.html("");
				editButton.html("");
				trash.html("<button class=\"btn text-left add-patient\" type=\"button\"><i class=\"fas fa-user-plus\"></i></button><input type=\"hidden\" class=\"room\" value=\"" + room +"\">");

				$('#deleteModal').modal('hide');
				window.location.href(SITE_URL + '/?module=Dietary&page=dietary&action=index&location=' + location);
			}
		});
	});
});

$(".add-patient").on("click", function (e) {
	e.preventDefault();
	var roomNumber = $(this).next().val();
	var location = $("#location").val();
	window.location.href = SITE_URL + "/?module=Dietary&page=patient_info&action=add_patient&location=" + location + "&number=" + roomNumber;
});


$("#selectDateModal").on('show.bs.modal', function(e) {
	var button = $(e.relatedTarget);

	var url = $(this).attr("href");
	$("#selectDateModal").dialog({
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

