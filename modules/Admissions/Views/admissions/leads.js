$(".show").removeClass("show");
$(".active").removeClass("active");
$("#admissionsSection").addClass("show");

var pipeline = getUrlParameter('pipeline');
if (pipeline == 'leads') {
	$("#leads").addClass("active");
} else if (pipeline == 'prospect') {
	$("#current-prospects").addClass("active");

}


var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

