<script type="text/javascript">
	$(document).ready(function() {
		$("#location").change(function() {
			window.location.href = SITE_URL + "/?module={$this->getModule()}&page={$this->page}&action={$this->action}&location=" + $("option:selected", this).val();
		});
	});
</script>


<div class="dropdown">
	<button class="btn btn-secondary dropdown-toggle" type="button" id="locationDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		{$location->name}
	</button>
	{if count($locations) > 1}
	<div class="dropdown-menu" aria-labelledby="locationDropdownButton">
	<select name="location" id="location" class="btn btn-primary dropdown-toggle dropdown-toggle-split">
		{foreach $locations as $location}
		<a class="dropdown-item" href="{$SITE_URL}/?module={$this->getModule()}&page={$this->page}&action={$this->action}&location={$location->public_id}" class="dropdown-item {if $selectedLocation == $location->name} selected{/if}">{$location->name}</a>
		{/foreach}		
	</div>
	{/if}
</div>
