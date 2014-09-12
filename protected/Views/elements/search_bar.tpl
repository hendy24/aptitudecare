<script>
	$(document).ready(function() {
		$("#module").change(function() {
			var module = $("#module option:selected").val();
			window.location.href = SiteUrl + "/?module=" + module;
		});
	});
</script>

<div id="search-header">
	
	{if count($modules) > 1}
	<div id="modules">
		Module: <select name="module" id="module">
			
			{foreach $modules as $m}
				<option value="{$m->name}" {if $module == $m->name} selected{/if}>{$m->name}</option>
			{/foreach}
		</select>
	</div>
	{/if}
	
	<div id="locations">
		<select name="location" id="location">
			<div id="optgroup">
			{foreach $locations as $location}	
				<option value="{$location->public_id}" {if isset($input->location)}{if $location->public_id == $input->location} selected{/if}{/if}><h1>{$location->name}</h1></option>
			{/foreach}
			</optgroup>
		</select>
	</div>
	
	
	<div id="areas">
		Area: <select name="areas" id="area">
			{foreach $areas as $area}
			<option value="{$area->public_id}" {if isset($input->area)}{if $area->public_id == $input->area} selected{/if}{/if}>{$area->name}</option>
			{/foreach}
		</select>
	</div>
</div>
