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


function init() {
	var requestData = null;

	$(".location-admit").draggable({
		containment: "#location-wrapper",
		cursor: "move",
		cursorAt: { left: 5 },
		stack: $(".location-admit-pending"),
		helper: "clone",
		start: function (e, ui) {
			$(this).addClass("moving-element");
		},
		drag: function (e, ui) {
		}, 
		stop: function (e, ui) {
			$(this).removeClass("moving-element");
		}
	});


	$(".location-day-box").droppable({
		tolerance: "pointer",
		drop: function (e, ui) {
			var dropped = ui.draggable;
			var droppedOn = $(this);
			var publicId = dropped.find("input.schedule-id").val();
			var date = $(this).find("input:first").val();
			$(this).append(dropped);			
			$.post(SITE_URL, { page: "admissions", action: "moveAdmitDate", public_id: publicId, date: date });
		}
	});


}


$(document).ready(function() {
	$(init);
});