var timeoutWarning = 300000; // 5 minutes
var timeoutNow = 360000; // 6 minutes
var logoutUrl = SITE_URL + '/?page=login&action=timeout';

var warningTimer;
var timeoutTimer;
	
// Start timers
function startTimer() {
	warningTimer = setTimeout("idleWarning()", timeoutWarning);
	timeoutTimer = setTimeout("idleTimeout()", timeoutNow);
}

// Reset timers
function resetTimer() {
	clearTimeout(warningTimer);
	clearTimeout(timeoutTimer);
	startTimer();
}

// Show idle timeout warning dialog
function idleWarning() {
	$("#timeout-warning").dialog({
		modal: true,
		dialogClass: "no-close",
		resizable: false,
		title: "Timeout Warning",
		buttons: {
			ok: {
				text: 'Stay Logged In',
				class: 'left',
				click: function() {
					$("#timeout-warning").dialog('close');
					resetTimer();
				}
			},
			Cancel: {
				text: 'Logout',
				class: 'right',
				click: function() {
					window.location = SITE_URL + '?page=login&action=logout';
				}
			}
		}
	});
	
}

// Logout the user
function idleTimeout() {
	window.location = logoutUrl;
}
	