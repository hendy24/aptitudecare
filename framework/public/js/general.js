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


	$(".dialog").dialog({
		autoOpen: false,
		width: 'auto',
		height: 'auto',
		modal: true
	});


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


	function getAge(birthday) {
		var ageDif = Date.now() - birthday.getTime();
		var ageDate = new Date(ageDif);
		return Math.abs(ageDate.getUtcFullYear() - 1970);
	}


	setTimeout(function() {
		$("#flash-messages").fadeOut("slow");
	}, 6000

	);
	


});
