$(".active").removeClass("active");
$("#current-activities").addClass("active");


// $(function() {
// 	$("#datepicker").datepicker({
// 		showOn: "button",
// 		buttonImage: "{$IMAGES}/calendar.png",
// 		buttonImageOnly: true
// 	});
// });

$("#datepicker").change(function() {
	window.location = SITE_URL + "/?module=Activities&date=" + $(this).val();
});


$(".delete").click(function(e) {
	e.preventDefault();
	var dataRow = $(this).parent().parent();
	var item = $(this);
	$("#dialog").dialog({
		buttons: {
			"Confirm": function() {
				var row = item.children().next($(".public-id"));
				var id = row.val();
					
				$.ajax({
					type: 'post',
					url: SITE_URL,
					data: {
						module: "Activities",
						page: "activities",
						action: "deleteActivity",
						id: id,
					},
					success: function(e) {
						console.log(e);
						dataRow.fadeOut("slow");
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
