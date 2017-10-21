$().ready(function() {

	$("label").addClass("bold");

	$("#addItem").validate({
		rules: {
			first_name: "required",
			last_name: "required",
			location_name: "required",	
			type: "required",
			city: "required",
			state_name: "required",
			zip: "required",
			
		},
		messages: {
			first_name: "Enter the  first name",
			last_name: "Enter the last name",
			hospital_name: "Enter the healthcare facility",
			type: "Select the type",
			city: "Enter the city",
			state_name: "Select the state",
			zip: "Enter the zip code",
		},
		errorPlacement: function (error, element) {
            if (!element.next().hasClass('ui-widget')) {
                $('<div class="ui-widget"><div id="form-error" class="ui-state-error ui-corner-all"><p>' + error[0].innerHTML + ' </p></div></div>').insertAfter(element);
            }
        }
	});

	$("#newUser").validate({
		rules: {
			first: "required",
			last: "required",
			email: {
				required: true,
				minlength: 4
			},
			password: {
				required: true,
				minlength: 6
			},
			confirm_password: {
				required: true,
				equalTo: "#password"
			},
			facility: "required"
		}, 
		messages: {
			first: "Enter the  first name",
			last: "Enter the last name",
			email: {
				required: "Enter a username",
				minlength: "Must be at least 4 characters long"
			},
			password: {
				required: "Enter a password",
				minlength: "Password mut be 6 characters long"
			},
			confirm_password: {
				required: "Enter a password",
				minlength: "Password mut be 6 characters long",
				equalTo: "Enter the same password as above"
			},
			facility: "Select a facility"
		},
		errorPlacement: function (error, element) {
            if (!element.next().hasClass('ui-widget')) {
                $('<div class="ui-widget"><div id="form-error" class="ui-state-error ui-corner-all"><p>' + error[0].innerHTML + ' </p></div></div>').insertAfter(element);
            }
        }
		
	});

	$("#username").focus(function() {
		var firstname = $("#first_name").val().toLowerCase();
		var lastname = $("#last_name").val().toLowerCase();
		if (firstname && lastname && !this.value) {
			var username = firstname + "." + lastname;
			this.value = username + SITE_EMAIL;

			var searchString = $("#username");
			var stringLength = username.length;
			console.log(searchString[0]);
			searchString.focus();
			searchString[0].setSelectionRange(8, 10);
		}
	});

	$("#addItem input:not(:submit)").addClass("ui-widget-content");

	$(":submit").button();

});

