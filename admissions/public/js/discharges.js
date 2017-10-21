/*
 * jQuery UI Touch Punch 0.2.2
 *
 * Copyright 2011, Dave Furfero
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Depends:
 *  jquery.ui.widget.js
 *  jquery.ui.mouse.js
 */
(function(b){b.support.touch="ontouchend" in document;if(!b.support.touch){return;}var c=b.ui.mouse.prototype,e=c._mouseInit,a;function d(g,h){if(g.originalEvent.touches.length>1){return;}g.preventDefault();var i=g.originalEvent.changedTouches[0],f=document.createEvent("MouseEvents");f.initMouseEvent(h,true,true,window,1,i.screenX,i.screenY,i.clientX,i.clientY,false,false,false,false,0,null);g.target.dispatchEvent(f);}c._touchStart=function(g){var f=this;if(a||!f._mouseCapture(g.originalEvent.changedTouches[0])){return;}a=true;f._touchMoved=false;d(g,"mouseover");d(g,"mousemove");d(g,"mousedown");};c._touchMove=function(f){if(!a){return;}this._touchMoved=true;d(f,"mousemove");};c._touchEnd=function(f){if(!a){return;}d(f,"mouseup");d(f,"mouseout");if(!this._touchMoved){d(f,"click");}a=false;};c._mouseInit=function(){var f=this;f.element.bind("touchstart",b.proxy(f,"_touchStart")).bind("touchmove",b.proxy(f,"_touchMove")).bind("touchend",b.proxy(f,"_touchEnd"));e.call(f);};})(jQuery);

/*
 * -------------------------------------------------------------
 *  DEFINE GLOBAL VARIABLES
 * -------------------------------------------------------------
 * 
 */




function init() {
	var transferData = null;
	var selectedPatient = null;
	var requestData = null;
	
	
	
	/*
	 * 
	 *
	 * Note: 
	 *
	 */
	 
	$(".current-patient").draggable({
		containment: "document",
		cursor: "move",
		stack: $(".discharge-day"),
		snap: $(".discharge-day"),
		start: function(e, ui) {
			/* Remove blue background and make white*/
			selectedPatient = $(this).find(".select-patient");
			$(this).removeClass("current-patient");
			selectedPatient.addClass("dragging-patient");
		},
		drag: function(e, ui) {
		
		},
		stop: function(e, ui) {
					
		}
	});
	
	
	$(".discharge-info").draggable({
		containment: "document",
		cursor: "move",
		stack: $(".discharge-day"),
		snap: $("discharge-day"),
		start: function(e, ui) {
			selectedPatient = $(this);
			$(this).removeClass("discharge-info");
			$(this).addClass("dragging-patient");
		}
	});
	
	
	
	/*
	 * DROP PATIENT INTO THE DISCHARGE SCHEDULE
	 *
	 * Note: Change the background color to red and save the discharge date to the database.
	 *
	 */
	 
	$(".discharge-day").droppable({
		tolerance: "pointer",
		
		over: function(e, ui) {
			$(this).addClass("drag-hover");
		},
		out: function(e, ui) {
			$(this).removeClass("drag-hover");
		},
		drop: function(e, ui) {
			var dropped = ui.draggable;
			var droppedOn = $(this);
			var publicId = dropped.find("input:first").val();
			var droppedDate = droppedOn.parent().find('input[name="date"]').val();
						
			requestData = { page: 'facility', action: 'save_discharge', pubid : publicId, date: droppedDate };
						
			$.post(
				SITE_URL, 
				requestData
			);
			
			
			$(this).removeClass("drag-hover");
			selectedPatient.removeClass("dragging-patient");
			selectedPatient.addClass("discharge-info");
			$(dropped).detach().css({ position: "relative", float: "left", top: 0, left: 0, clear: "both" }).appendTo(droppedOn);
		}
	});
	
	
	$("#current-patients").droppable({
		tolerance: "pointer",
		activeClass: "drag-hover",
		drop: function(e, ui) {
			var dropped = ui.draggable;
			var droppedOn = $("#current-patient-table tr:last");
			var publicId = dropped.find("input:first").val();
			
			requestData = { page: 'facility', action: 'clear_discharge', pubid: publicId };
			
			$.post(SITE_URL, requestData);
			
			selectedPatient.removeClass("dragging-patient");
			selectedPatient.removeClass("discharge-info");
			dropped.addClass("current-patient");
			//droppped.append().addClass(".select-patient");
			$(dropped).detach().css({ position: "relative", float: "left" , top: 0, left: 0 }).appendTo(droppedOn);
		}
	});
			
}


$(document).ready(function() {	
	$(init);
});

