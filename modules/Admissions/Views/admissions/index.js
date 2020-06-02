$(".show").removeClass("show");
$(".active").removeClass("active");
$("#admissionsSection").addClass("show");

var pipeline = getUrlParameter('pipeline');
if (pipeline == 'leads') {
	$("#leads").addClass("active");
} else {
	$("#current-prospects").addClass("active");
}


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
			location.reload();
		});
});



