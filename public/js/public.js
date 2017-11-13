/*** Rotate pages on main public page ****/


var nextPanel = 0;
var currentPanel = 0;
var panelCount = 0;
var firstRun = 0;
var loadNumber = 0;
var loadFailCount = 0;
var errorStatus = 0;
	
 $(document).ready(function() {
	//loadPage();  //Load the menu on the page load
	
	$panels = $('#transitionDiv').children('.rotatingPage');
	panelCount = $panels.length - 1;
	
	//setup debugger
	$('.panelCount').html('panelCount = '+window.panelCount);
	
	setInterval(autoAdvance, 18000); // page rotates every 18 seconds

	//Tell javascript to call loadpage every X milliseconds
	setInterval(loadPage, 900000); // 15 minute (900000) ms for production
	
});

	
//function called by JS engine every X seconds to update the menu contents.
function loadPage()
{	// check if we have internet access
	if (navigator.onLine) {
		// if we do have access simply reload the page
		location.reload();
	}	
}
	
//function called async after an attempted load, 
function postLoad(response, status, xhr)
{
	//if we errored, increment counter and if failed more than X times display the red warning in the lower right corner
	if (status == "error") {
		errorStatus = 1;
		console.log("Load failed. " + xhr.status + " " + xhr.statusText);
		loadFailCount++;
		if(loadFailCount >= 3)  //fail threshold, With the interval length,  After 30 minutes of failure?
		{
			$('#error').show();
		}
	} else {
	//We sucessfully loaded the page.  Set counter to 0 and hide the warning mark
		loadNumber++;
		errorStatus = 0;
		console.log("Load succeeded.");
		loadFailCount = 0;
		$('#error').hide();
		
	}
}

function autoAdvance(){
	// get all panels
	$panels = $('.rotatingPage');
	
	// derive integer of the last page
	var lastPanel = $panels.length - 1;
					
	// derive integer val of the next panel
	if (currentPanel == panelCount) {
		nextPanel = 0;
	} else {
		if (firstRun == 0) {
			nextPanel = currentPanel +1;
			if (currentPanel > 0) {
				nextPanel = 0;
				firstRun =+ 1;
			}
		} else {
			nextPanel = currentPanel + 2;
			firstRun = 0;
		}
	}
	
	// clean slate: hide all panels
	$panels.fadeOut(3000);
	
	// show the next panel
	$('#panel-' + nextPanel).fadeIn(3000);
	
	// update global tracking var
	currentPanel = nextPanel;
	
	$('.currentPanel').html('currentPanel = '+window.currentPanel);
	$('.nextPanel').html('nextPanel = ' + window.nextPanel);
	$('.firstRun').html('firstRun = ' + window.firstRun);
}


$('.clock').ready(function() {
	var options = {
		format: '%A, %B %d, %Y %I:%M %p',
	}
	$('#clock').jclock(options);
});