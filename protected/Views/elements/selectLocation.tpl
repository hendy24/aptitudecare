<script type="text/javascript">
	$(document).ready(function() {
		$("#location").change(function() {
			window.location.href = SITE_URL + "/?module={$this->getModule()}&page={$this->page}&action={$this->action}&location=" + $("option:selected", this).val();
		});
	});
</script>
	<select name="location" id="location" class="btn btn-primary dropdown-toggle dropdown-toggle-split">
		{foreach $locations as $location}
			<option value="{$location->public_id}" {if $location->public_id == $selectedLocation->public_id} selected{/if}>{$location->name}</option>
		{/foreach}
	</select>
