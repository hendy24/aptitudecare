$(document).ready(function() {
	$(function() {
		$('.datepicker').datepicker();
	});

	$(function() {
		$(".timepicker").timepicker({
			minutes: {
				starts: 0,
				ends: 45,
				interval: 15
			}
		});
	});

});