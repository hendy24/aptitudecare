$('.datepicker').pickadate({
	max: new Date('now'),
	selectYears: 100,
	selectMonths: true
});

$("#pcp-phone").mask("(999) 999-9999");

$("#care-needs").selectize({
	plugins: ['remove_button'],
	delimiter: ',',
	persist: false
});

$("#self-manage").hide();

$("#diabetes").click(function() {
	console.log($(this).val());

	if ($(this).is(":checked")) {
		$("#self-manage").show();
	} else {
		$("#self-manage").hide();
	}
});

$("#mh-diagnosis-row").hide();

$("#mh-diagnosis").click(function() {
	if ($(this).is(":checked")) {
		$("#mh-diagnosis-row").show();
	} else {
		$("#mh-diagnosis-row").hide();
	}
});

$("#dependency-explanation-row").hide();

$("#chemical-dependencies").click(function() {
	if ($(this).is(":checked")) {
		$("#dependency-explanation-row").show();
	} else {
		$("#dependency-explanation-row").hide();
	}
});

