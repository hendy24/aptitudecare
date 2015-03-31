<script type="text/javascript">
	$(document).ready(function() {
		$("#location").change(function() {
			window.location.href = SITE_URL + "/?module={$this->module}&page={$this->page}&action={$this->action}&location=" + $("option:selected", this).val();
		});
	});
</script>

<div id="locations">
	<select name="location" id="location">
		{foreach $locations as $location}	
			<option value="{$location->public_id}" {if $location->public_id == $selectedLocation->public_id} selected{/if}>{$location->name}</option>
		{/foreach}
	</select>
</div>
