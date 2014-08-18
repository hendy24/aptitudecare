<div id="search-header">
	
	{if count($modules) > 1}
	<div id="modules">
		Module: <select name="module" id="module">
			
			{foreach $modules as $m}
				<option value="{$m->public_id}" {if $module == $m->name} selected{/if}>{$m->name}</option>
			{/foreach}
		</select>
	</div>
	{/if}

	<h1>{$headerTitle}</h1>
	<div id="patient-search">
		Location: <select name="locations" id="locations">
			{foreach $locations as $location}
			<option value="{$location->public_id}" {if isset($input->location)}{if $location->public_id == $input->location} selected{/if}{/if}>{$location->name}</option>
			{/foreach}
		</select>
	</div>
</div>
