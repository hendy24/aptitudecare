<script>
	$(document).ready(function() {
		$('#area').change(function() {
			window.location = "?module={$this->module}&page={$this->page}&action={$this->action}&location=" + $("#location").val() + "&area=" + $(this).val();
		});
	});
</script>


<div id="areas">
	<span class="text-grey">Area:</span> <select name="areas" id="area">
		<option value="all">All</option>
		{foreach $areas as $area}
		<option value="{$area->public_id}" {if $selectedArea && $area->public_id == $selectedArea->public_id} selected{/if}>{$area->name}</option>
		{/foreach}
	</select>
</div>