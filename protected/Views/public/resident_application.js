$('.datepicker').pickadate({
	max: new Date('now'),
	selectYears: 100,
	selectMonths: true
});

$("#phone").mask("(999) 999-9999");
$("#contact-phone").mask("(999) 999-9999");
$("#pcp-phone").mask("(999) 999-9999");

$("#care-needs").selectize({
	plugins: ['remove_button'],
	delimiter: ',',
	persist: false
});


// diabetes
$("#diabetes").click(function() {
	console.log($(this).val());

	if ($(this).val() == 1) {
		$("#self-manage").show();
	} else {
		$("#self-manage").hide();
	}
});

$("#diabetes1").click(function() {
	console.log($(this).val());

	if ($(this).val() == 0) {
		$("#self-manage").hide();
	} 
});



// dementia wandering
$("#dementia").change(function() {
	console.log($(this).val());
	if ($(this).val() != null && $(this).val() != 1) {
		$("#wandering").show();
	} else {
		$("#wandering").hide();
	}
});

$("#dementia1").click(function() {
	if ($(this).val() == 0) {
		$("#wandering").hide();
	} 
});



// mental health diagnosis
$("#mh-diagnosis").click(function() {
	if ($(this).val() == 1) {
		$("#mh-diagnosis-row").show();
	} 
});

$("#mh-diagnosis1").click(function() {
	if ($(this).val() == 0) {
		$("#mh-diagnosis-row").hide();
	} 
});


$("#chemical-dependencies").click(function() {
	if ($(this).val() == 1) {
		$("#dependency-explanation-row").show();
	} 
});

$("#chemical-dependencies1").click(function() {
	if ($(this).val() == 0) {
		$("#dependency-explanation-row").hide();
	} 
});

