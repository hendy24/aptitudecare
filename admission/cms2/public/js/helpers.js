function popupWin(u,n,o) { // v3
	var ftr = o.split(",");
	nmv=new Array();
	for (i in ftr) {
		x=ftr[i];
		t=x.split("=")[0];
		v=x.split("=")[1];
		nmv[t]=v;
	}
	if (nmv['width']=='100%'){
		nmv['width']=screen.width-10;
		if(nmv['width'] > 912){
			nmv['width'] = 912;
		}
	}
	if (nmv['height']=='100%'){
		nmv['height']=screen.height-65;
		if(nmv['height'] > 768){
			nmv['height'] = 768;
		}
	}
	if (nmv['centered']=='yes' || nmv['centered']==1) {
		nmv['left']=(screen.width-nmv['width'])/2 ;
		nmv['top']=(screen.height-nmv['height']-72)/2 ;
		nmv['left'] = (nmv['left']<0)?'0':nmv['left'] ;
		nmv['top']=(nmv['top']<0)?'0':nmv['top'];
		delete nmv['centered'];
	} else {
		nmv['left']= 0;
		nmv['top']= 0;
		nmv['left'] = 0;
		nmv['top']= 0;
	}
	o="";
	var j=0;
	for (i in nmv) {
		o+=i+"="+nmv[i]+"\,";
	}
	o=o.slice(0,o.length-1);
	window.open(u,n,o);
}

function getQueryVariable(variable, q) {
	  if (q==undefined) {
		  var query = window.location.search.substring(1);
	  } else {
		  var query = q;
	  }
	  var vars = query.split("&");
	  for (var i=0;i<vars.length;i++) {
	    var pair = vars[i].split("=");
	    if (pair[0] == variable) {
	      return pair[1];
	    }
	  }
	  alert('Query Variable ' + variable + ' not found');
} 

function urlEncodeCharacter(c)
{
	return '%' + c.charCodeAt(0).toString(16);
}

function urlDecodeCharacter(str, c)
{
	return String.fromCharCode(parseInt(c, 16));
}

function urlEncode( s )
{
      return encodeURIComponent( s ).replace( /\%20/g, '+' ).replace( /[!'()*~]/g, urlEncodeCharacter );
};

function urlDecode( s )
{
      return decodeURIComponent(s.replace( /\+/g, '%20' )).replace( /\%([0-9a-f]{2})/g, urlDecodeCharacter);
}

new function($) {
	  $.fn.setCursorPosition = function(pos) {
	    if ($(this).get(0).setSelectionRange) {
	      $(this).get(0).setSelectionRange(pos, pos);
	    } else if ($(this).get(0).createTextRange) {
	      var range = $(this).get(0).createTextRange();
	      range.collapse(true);
	      range.moveEnd('character', pos);
	      range.moveStart('character', pos);
	      range.select();
	    }
	  }
	}(jQuery);