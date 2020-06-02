$("#admissionsSection").addClass("show");


$('.datepicker').pickadate({
	max: new Date('now'),
	selectYears: 100,
	selectMonths: true
});

$(".phone").mask("(999) 999-9999");
$("#zip").mask("99999");

// add minus icon for collapse element which is open by default
$(".collapse.show").each(function() {
     $(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
});

// toggle plus minus icon on show hide of collapse element
$(".collapse").on('show.bs.collapse', function() {
     $(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
}).on('hide.bs.collapse', function() {
     $(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
});


$('.modal-webpage').on('click', function(){
	$($(this).data("target") + ' .modal-body').load($(this).data("remote"));
});


$('#deleteModal').on('shown.bs.modal', function(event) {
	var triggerElement = $(event.relatedTarget);
	var row = $(triggerElement).parent().parent();

	var prospectId = triggerElement.siblings('.prospect-id').val();
	var contactId = triggerElement.siblings('.contact-id').val();
	var contactLink = triggerElement.siblings('.contact-link').val();


	$('#delete').click(function() {
		$.post(SITE_URL, {
			module: 'Admissions',
			page: 'admissions',
			action: 'unlink_contact',
			prospect: prospectId,
			contact: contactId,
			contact_link: contactLink
			}, function (e) {
				console.log(e);
				$("#deleteModal").modal('toggle');
				row.fadeOut('slow');

			}
		);
	});

});



// add a new file
$(document).on('click', '.add-file', function() {
	// use ajax to save the file?

	var $row = $(this).parent().parent();
	// clone the file upload row
	$(".file-select").clone().appendTo("#file-card");
	$(".add-file").addClass("active-button");
	// remove the file-select class so if we add more rows we only add 1 at a time
	$row.removeClass("file-select");
	// remove the add button so it is only on the last row
	$(this).remove();
});
