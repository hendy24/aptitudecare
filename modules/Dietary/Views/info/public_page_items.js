<script type="text/javascript">
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

</script>
