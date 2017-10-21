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


var dragSrcEl = null;
var countPendings = 0;
var className = null;
var pubid = null;
var admitOrder = 1;
var requestData = {};
var orderData = null;
var cols = null;
var loadUrl = SITE_URL + "/?page=coord&action=order_pending_admits";








/*
 * -------------------------------------------------------------
 *  HTML5 FUNCTIONS
 * -------------------------------------------------------------
 * 
 */
 
 
 
function handleDragStart(e, ui) {
	$('div', this).opacity = '0.1';  // this / e.target is the source node.
	admitOrder = 1;
	dragSrcEl = this;
	countPendings = $(this).parent().find(".patient-box").length;

	e.dataTransfer.effectAllowed = 'move';
	e.dataTransfer.setData("text/html", this.innerHTML);
	
	className = this.className;
				
}

function handleDragOver(e, ui) {
	if (e.preventDefault) {
		e.preventDefault(); // Necessary. Allows us to drop.
	}

	e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

	return false;
}

function handleDragEnter(e, ui) {
	// this / e.target is the current hover target.
	$(this).addClass('over');
}

function handleDragLeave(e, ui) {
	$(this).removeClass('over');  // this / e.target is previous target element.
}

function handleDrop(e) {
	if (e.stopPropagation) {
		e.stopPropagation();
	}
	
	if (dragSrcEl != this) {
		dragSrcEl.innerHTML = this.innerHTML;
		dragSrcEl.className = this.className;
		this.innerHTML = e.dataTransfer.getData('text/html');
		this.className = className;
		pubid = $(this).find("input:hidden:first").val();
							
		$(this).parent().find(".patient-box").each(function() {
			requestData[admitOrder] = {order : admitOrder, pubid : $(this).find("input:hidden").val()};
			admitOrder++;					
		});
		
										
		// Need to determine the order of the items and apply the proper order number to admit_order
		
		//var jsonData = JSON.stringify(requestData);
						
		$.ajax({
			type: "POST",
			cache : false,
			url: loadUrl,
			data : {data:requestData}
		});
		
		
	}
	
	return false;
}



function handleDragEnd(e) {
	[].forEach.call(cols, function(col) {
		col.classList.remove('over');
	});
}








/*
 * -------------------------------------------------------------
 *  JQUERY DRAG AND DROP FUNCTIONALITY
 * -------------------------------------------------------------
 * 
 */


function init() {
	var transferData = null;
	
	$(".admits-pending").sortable({
		appendTo: document.body,
		axis: "y",
		containment: "parent",
		opacity: 0.5,
		activate: function(e, ui) {
			$(this).addClass('over');
		},
		stop: function(e, ui) {
			pubid = $(this).find("input:hidden:first").val();
							
			$(this).parent().find(".patient-box").each(function() {
				requestData[admitOrder] = {order : admitOrder, pubid : $(this).find("input:hidden").val()};
				admitOrder++;					
			});
										
			$.ajax({
				type: "POST",
				cache : false,
				url: loadUrl,
				data : {data:requestData}
			});
		}
	});
		
}






/*
 * -------------------------------------------------------------
 *  RUN WHEN PAGE LOADS
 * -------------------------------------------------------------
 * 
 */



$(document).ready(function() {
	
	$(init);
	// Check if browser is IE
/*
	if ($.browser.msie) {
		$(init);
	} else if (Modernizr.draganddrop) {
		cols = $(".drag");
		[].forEach.call(cols, function(col) {
			col.addEventListener('dragstart', handleDragStart, false);
			col.addEventListener('dragenter', handleDragEnter, false);
			col.addEventListener('dragover', handleDragOver, false);
			col.addEventListener('dragleave', handleDragLeave, false);
			col.addEventListener('drop', handleDrop, false);
			col.addEventListener('dragend', handleDragEnd, false);
		});	
	} else {
		$(init);
	}
*/
});




