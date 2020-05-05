$(".active").removeClass("active");
$(".show").removeClass("show");
$("#dietarySection").addClass("show");
$("#infoSection").addClass("show");
$("#public-page-items").addClass("active");


function limitText(limitField, limitCount, limitNum) {
	console.log(limitField);
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}

$('#summernote').summernote({
	height: 200
});

