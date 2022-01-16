$(".active").removeClass("active");
$(".show").removeClass("show");
$("#dietarySection").addClass("show");
$("#infoSection").addClass("show");
$("#corporate-menus").addClass("active");


$("#menu").change(function() {
	window.location.href = SITE_URL + "/?module=Dietary&page=info&action=corporate_menus&menu=" + $("option:selected", this).val();
});
