<script>
	$(document).ready(function() {
		$("#menu").change(function() {
			window.location.href = SITE_URL + "/?module=Dietary&page=info&action=corporate_menus&menu=" + $("option:selected", this).val();
		});
	});
</script>