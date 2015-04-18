<script>
	$(document).ready(function() {
		$('#area').change(function() {
			window.location = "?module={$this->getModule()}&page={$this->page}&action={$this->action}&location=" + $("#location").val() + "&area=" + $(this).val();
		});
	});
</script>

<span class="text-grey">Area:</span> <select name="areas" id="area">
	<option value="all" {if $selectedArea == "all"} selected{/if}>All</option>
	{foreach $areas as $a}
	<option value="{$a->public_id}" {if isset ($selectedArea->public_id) && $selectedArea->public_id == $a->public_id} selected{/if}>{$a->name}</option>
	{/foreach}
</select>
