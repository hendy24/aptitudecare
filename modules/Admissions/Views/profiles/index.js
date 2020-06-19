
$("#admissionsSection").addClass("show");

PDFObject.embed();

$('.datepicker').pickadate({
	max: new Date('now'),
	selectYears: 100,
	selectMonths: true
});

$(".phone").mask("(999) 999-9999");
$("#zip").mask("99999");



$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

$(function () {
  $('[data-toggle="popover"]').popover()
});


// set variables for page
var key = 1;



$("#searchExistingButton").click(function() {
	$("#searchExisting").collapse('show');
	$("#addNew").collapse('hide');	
});

$("#addNewButton").click(function() {
	$("#addNew").collapse('show');
	$("#searchExisting").collapse('hide');
});


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


$("#contact-name").autocomplete({
	serviceUrl: SITE_URL + "/?module=Admissions&page=admissions&action=fetchContactNames",
	minChars: 4,
	onSelect: function (suggestion) {
		// set the values in the dom
		$(this).val(suggestion.value);
		$("#contact-id").val(suggestion.data);
	}
});


// Link an existing contact to the new prospect
$("#link-contact").click(function() {
	var contactId = $("#contact-id").val();
	var contactName = $("#contact-name").val();
	var contactTypeName = $("#addExistingContactType option:selected").text();
	var contactType = $("#addExistingContactType").val();

	if ($("input#poa").is(':checked')) {
		var poa = '<i class="fas fa-balance-scale-left" data-toggle="tooltip" data-placement="top" title="Power of Attorney"><input type="hidden" name="contact[' + key +'][poa]" value="1">';
	} else {
		var poa = "";
	}

	if ($("input#primary-contact").is(':checked')) {
		var pContact = '<i class="fas fa-hospital-user" data-toggle="tooltip" data-placement="top" title="Primary Contact"><input type="hidden" name="contact[' + key + '][primary_contact]" value="1">';
	} else {
		var pContact = "";
	}

	// add html contact info to table row
	$("#contact-table-body").append('<tr><td>' + poa + ' ' + pContact + '</td><td>' + contactName + '<input type="hidden" name="contact[' + key + '][id]" value="' + contactId + '"></td><td>' + contactTypeName + '<input type="hidden" name="contact[' + key + '][contact_type]" value="' + contactType + '"></td></tr>');

	// clear values in the autocomplete boxes
	$("#contact-id").val("");
	$("#contact-name").val("");
	$("#addExistingContactType").val("");
	$("input#primary-contact").prop("checked", false);
	$("input#poa").prop("checked", false);
	key++;
});



// create and add a new contact to the prospect
$("#add-contact").click(function(e) {
	e.preventDefault();
	var firstName = $("#contactFirstName").val();
	var lastName = $("#contactLastName").val();
	var email = $("#contactEmail").val();
	var phone = $("#contactPhone").val();
	var address = $("#contactAddress").val();
	var city = $("#contactCity").val();
	var state = $("#contactState option:selected").val();
	var zip = $("#contactZip").val();

	var contactTypeName = $("#addNewContactType option:selected").text();
	var contactType = $("#addNewContactType").val();

	if ($("input#newPoa").is(':checked')) {
		var poa = '<i class="fas fa-balance-scale-left" data-toggle="tooltip" data-placement="top" title="Power of Attorney"><input type="hidden" name="contact[' + key + '][poa]" value="1">';
	} else {
		var poa = "";
	}

	if ($("input#newPrimaryContact").is(':checked')) {
		var primaryContact = '<i class="fas fa-hospital-user" data-toggle="tooltip" data-placement="top" title="Primary Contact"><input type="hidden" name="contact[' + key + '][primary_contact]" value="1">';
	} else {
		var primaryContact = "";
	}

	$.post(SITE_URL, {
		module: 'Admissions',
		page: 'admissions',
		action: 'addNewContact',
		first_name: firstName,
		last_name: lastName,
		email: email,
		phone: phone,
		address: address,
		city: city,
		state: state,
		zip: zip
		}, function (response) {
			// add html contact info to table row
			$("#contact-table-body").append('<tr><td>' + poa + ' ' + primaryContact + '</td><td>' + response.first_name + ' ' + response.last_name + '<input type="hidden" name="contact[' + key + '][id]" value="' + response.id + '"></td><td>' + contactTypeName + '<input type="hidden" name="contact[' + key + '][contact_type]" value="' + contactType + '"></td></tr>');

			$("#contactFirstName").val("");
			$("#contactLastName").val("");
			$("#contactEmail").val("");
			$("#contactPhone").val("");
			$("#contactAddress").val("");
			$("#contactCity").val("");
			$("#contactState option:selected").val("");
			$("#contactZip").val("");

			$("#addNewContactType option:selected").text("");
			$("#addNewContactType").val("");
			$("input#newPrimaryContact").prop('checked', false);
			$("input#newPoa").prop('checked', false);

			key++;

		}
	);
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

$('.modal-webpage').on('click', function(){
	$($(this).data("target") + ' .modal-body').load($(this).data("remote"));
});




/*
 *
 * FILE UPLOAD 
 *
 */

$('#file').on('change', function() {
	var file = this.files[0];

	// do some validation here
	//if (file.type == "") {
		//...
	//}
});

$('#addFile').click(function(e) {
	e.preventDefault();
	fd = new FormData();
	var files = $('#file')[0].files[0];
	var prospect = $('#prospect').val();
	var fileType = $('#file-type :selected').val();

	fd.append('file', files);

	$.ajax({
		url: SITE_URL + '/?module=Admissions&page=profiles&action=uploadFiles&prospect=' + prospect + '&fileType=' + fileType,
		type: 'post',
		data: fd,
		contentType: false,
		processData: false,
		success: function(response) {
			// place the name of the file in a row above the file input
			$('#file-table-row').append('<tr><td>' + response.name + '</td><td class="text-right"><a href="' + AWS + '/client_files/' + response.file_name + '" target="_blank"><i class="fas fa-file fa-2x"></i></a></td></tr>');
			$('#file-type').val("");
			$('#file').val("");
		}
	});

});

// add a new file
// $(document).on('click', '.add-file', function() {
// 	// use ajax to save the file?
// 	var form = $("form");
// 	var $file = form.find("#file");
// 	var $row = $(this).parent().parent();

// 	readFile($file[0].files[0]).done(function(fileData){
// 		var formData = form.find(":input:not('#file')").serializeArray();
// 	   	formData.file = [fileData, $file[0].files[0].name];
// 	   	upload(form.attr("action"), formData).done(function(){ 
// 			alert("successfully uploaded!"); 
// 			// clone the file upload row
// 			$(".file-select").clone().appendTo("#file-card");
// 			$(".add-file").addClass("active-button");
// 			// remove the file-select class so if we add more rows we only add 1 at a time
// 			$row.removeClass("file-select");
// 			// remove the add button so it is only on the last row
// 			$(this).remove();
// 		});
// 	});	

	
// });


// function readFile(file){
//    var loader = new FileReader();
//    var def = $.Deferred(), promise = def.promise();

//    //--- provide classic deferred interface
//    loader.onload = function (e) { def.resolve(e.target.result); };
//    loader.onprogress = loader.onloadstart = function (e) { def.notify(e); };
//    loader.onerror = loader.onabort = function (e) { def.reject(e); };
//    promise.abort = function () { return loader.abort.apply(loader, arguments); };

//    loader.readAsBinaryString(file);

//    return promise;
// }

// function upload(url, data){
//     var def = $.Deferred(), promise = def.promise();
//     var mul = buildMultipart(data);
//     var req = $.ajax({
//         url: url,
//         data: mul.data,
//         processData: false,
//         type: "post",
//         async: true,
//         contentType: "multipart/form-data; boundary="+mul.bound,
//         xhr: function() {
//             var xhr = jQuery.ajaxSettings.xhr();
//             if (xhr.upload) {

//                 xhr.upload.addEventListener('progress', function(event) {
//                     var percent = 0;
//                     var position = event.loaded || event.position; /*event.position is deprecated*/
//                     var total = event.total;
//                     if (event.lengthComputable) {
//                         percent = Math.ceil(position / total * 100);
//                         def.notify(percent);
//                     }                    
//                 }, false);
//             }
//             return xhr;
//         }
//     });
//     req.done(function(){ def.resolve.apply(def, arguments); })
//        .fail(function(){ def.reject.apply(def, arguments); });

//     promise.abort = function(){ return req.abort.apply(req, arguments); }

//     return promise;
// }

// var buildMultipart = function(data){
//     var key, crunks = [], bound = false;
//     while (!bound) {
//         bound = $.md5 ? $.md5(new Date().valueOf()) : (new Date().valueOf());
//         for (key in data) if (~data[key].indexOf(bound)) { bound = false; continue; }
//     }

//     for (var key = 0, l = data.length; key < l; key++){
//         if (typeof(data[key].value) !== "string") {
//             crunks.push("--"+bound+"\r\n"+
//                 "Content-Disposition: form-data; name=\""+data[key].name+"\"; filename=\""+data[key].value[1]+"\"\r\n"+
//                 "Content-Type: application/octet-stream\r\n"+
//                 "Content-Transfer-Encoding: binary\r\n\r\n"+
//                 data[key].value[0]);
//         }else{
//             crunks.push("--"+bound+"\r\n"+
//                 "Content-Disposition: form-data; name=\""+data[key].name+"\"\r\n\r\n"+
//                 data[key].value);
//         }
//     }

//     return {
//         bound: bound,
//         data: crunks.join("\r\n")+"\r\n--"+bound+"--"
//     };
// };




