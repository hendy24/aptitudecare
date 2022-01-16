$(".show").removeClass("show");
$(".active").removeClass("active");
$("#admissionsSection").addClass("show");
$("#prospects").addClass("active");

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

$(function () {
  $('[data-toggle="popover"]').popover()
});

$(".timeframe").change(function() {
	var residentID = $(this).siblings().val();
	var timeframe = $(this).val();

	$.post(SITE_URL, {
		module: 'Admissions',
		page: 'admissions',
		action: 'change_timeframe',
		id: residentID,
		timeframe: timeframe
		}, function(e) {
			//console.log(e);
			location.reload();
		});
});




