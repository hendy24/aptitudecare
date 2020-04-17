$(".active").removeClass("active");
$("#current-activities").addClass("active");


$(function() {
	$("#datepicker").datepicker({
		showOn: "button",
		buttonImage: "{$IMAGES}/calendar.png",
		buttonImageOnly: true
	});
});

$("#datepicker").change(function() {
	window.location = SITE_URL + "/?module=Activities&date=" + $(this).val();
});