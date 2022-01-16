$(document).ready(function() {
   //if cookie hasn't been set...
   console.log(document.cookie);
    if (document.cookie.indexOf("ModalShown=true") < 0) {
        $('#covid-19-message').show();
        //Modal has been shown, now set a cookie so it never comes back
        var date = new Date();
        var minutes = 1440;
        date.setTime(date.getTime() + (minutes * 60 * 1000));
       document.cookie = "ModalShown=true; expires=" + date + "; path=/";
    } else {
    	$('#covid-19-message').hide();
    }
});