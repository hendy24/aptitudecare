<script>
	$(document).ready(function() {
		$(".facilities-list").hide();

		$("#reset").submit(function(e) {
			e.preventDefault();
			$.post(SITE_URL, { 
				module: "Dietary",
				page: "Menu",
				action: "deleteId",
				type: "MenuMod",
				id: $("#public-id").val(),
				}, function (response) {
					window.location.href = SITE_URL + "/?module=Dietary&page=dietary&action=current&location=" + $("#location").val();
				}, "json"
			);
		});

		{if !$location}
			$(".facilities-list").hide();
		{/if}

		$('input:radio[name="edit_type"]').change(function() {
			if ($(this).is(':checked') && $(this).val() == 'select_locations') {
				$(".facilities-list").show();
			} else {
				$(".facilities-list").hide();
			}
		});
	});

	$('#summernote').summernote({
    	height: 350
    });
    
</script>
