$("#location").change(function() {
	var location = $("#location option:selected").val();
	window.location.href = SITE_URL + "/?module=Dietary&page=info&action=current&location=" + location;
});

$(".active").removeClass("active");
$(".show").removeClass("show");
$("#infoSection").addClass("show");
$("#current-menu").addClass("active");

$('.datepicker').pickadate();

$('.print').on('click', function(e) {
	e.preventDefault();
	var selectedDate = $('.datepicker').val();
	var location = $('#location').val();
	
	//var location = $("#location option:selected").val();
	var url = SITE_URL + '?module=Dietary&page=menu&action=print_menu&location=' + location;
	window.open(url + '&weekSeed=' + selectedDate + '&pdf=true', '_blank');
	$(this).dialog("close");
});

$("#print-menu-select-date").on("click", function(e){
	e.preventDefault();
	var location = $("#location option:selected").val();
	var url = SITE_URL + '?module=Dietary&page=menu&action=print_menu&location=' + location;
	console.log(url);

	$('#menu-date-dialog').dialog({
		buttons: {
			"Submit": function() {
				var selectedDate = $("#selected-date").val();
				window.open(url + '&weekSeed=' + selectedDate + '&pdf=true', '_blank');
				$(this).dialog("close");
			}
		}
	});

});



