$().ready(function() {

	Date.firstDayOfWeek = 0;
	Date.format = 'mm/dd/yyyy';

	// date picker
	$(".date-picker").datepicker({
		startDate: '01/01/2008',
		clickInput: true,
		createButton: false
	});

	// time picker
	$(".time-picker").datetimepicker({
		ampm: true
	});
	$(".datetime-picker").datetimepicker({
		ampm: true
	});

	$(".dialog").dialog({
		autoOpen: false,
		width: 'auto',
		height: 'auto',
		modal: true
	});

	$(".remote-dialog").live("click", function(e) {
		e.preventDefault();
		
		var href = $(this).attr("href");
		
		$("#remote-dialog").dialog({
			width: 'auto', 
			height: 'auto',
			modal: 'true',
			open: function() {
				$("#remote-dialog-frame").load(href + "&isMicro=1");
			}
		}).dialog("open")
		
		return false;
	});

	$("li#facility-dashboard").hoverIntent(
		function() {
			$("ul#facility-dashboard-dropdown").stop().fadeIn(500, function() {
				$("ul#facility-dashboard-dropdown").show();	
			});	
		}, function() {
			$("ul#facility-dashboard-dropdown").hide();
		}
	);


	$(".dropdown dt a").click(function() {

	    // Change the behaviour of onclick states for links within the menu.
		var toggleId = "#" + this.id.replace(/^link/,"ul");

	    // Hides all other menus depending on JQuery id assigned to them
		$(".dropdown dd ul").not(toggleId).hide();
		$(".dropdown dt a").not(toggleId).css("z-index", "1");
		$(".dropdown dt").not(toggleId).css("z-index", "1");
		$(".dropdown").not(toggleId).css("z-index", "1");

	    //Only toggles the menu we want since the menu could be showing and we want to hide it.
		$(toggleId).toggle();
		$(toggleId).parent().parent().css("z-index", 5000);
		$(toggleId).parent().css("z-index", 5000);
		$(toggleId).css("z-index", 5000);

	    //Change the css class on the menu header to show the selected class.
		if($(toggleId).css("display") == "none"){
			$(this).removeClass("selected");
		}else{
			$(this).addClass("selected");
		}

	});

	$(".dropdown dd ul li a").click(function() {

	    // This is the default behaviour for all links within the menus
	    var text = $(this).html();
	    $(".dropdown dt a span").html(text);
	    $(".dropdown dd ul").hide();
	});

	$(document).bind('click', function(e) {

	    // Lets hide the menu when the page is clicked anywhere but the menu.
	    var $clicked = $(e.target);
	    if (! $clicked.parents().hasClass("dropdown")){
	        $(".dropdown dd ul").hide();
			$(".dropdown dt a").removeClass("selected");
		}

	});

	$(".admit-error-details").click(function() {
		var msg = $(this).attr("title");
		alert(msg);
	});

	$(".approve").click(function(e) {
		e.preventDefault();
		var td = $(this).parent().parent().parent().parent().parent().parent().parent().find(".under-consideration");
		//td.removeClass("text-red").addClass("text-black");
		var url = SITE_URL + "/?page=facility&action=approveInquiry&schedule=";
		var data = { page: "facility", action: "approveInquiry", schedule: $(this).attr("name"), status: "Approved"};
		$.post(SITE_URL, data, function() { td.removeClass("under-consideration").addClass("approved"); } );

	});


	// $(".approve-admit-link").click(function(e) {
	// 	e.preventDefault();
	// 	var anchor = this;
	// 	jConfirm('Are you sure you want to approve this request? If you click OK, you will be prompted to assign this patient to a room.', 'Confirmation Required', function(r) {
	// 		if (r == true) {
	// 			location.href = $(anchor).attr("href");
	// 		} else {
	// 			return false;
	// 		}
	// 	});

	// 	return false;
	// });
	$(".cancel-admit-link").click(function(e) {
		e.preventDefault();
		var anchor = this;
		jConfirm('Are you sure you want to cancel this inquiry? This action cannot be undone.', 'Confirm Cancellation', function(r) {
			if (r == true) {
				location.href = $(anchor).attr("href");
			}
		});
		return false;
	});
});
