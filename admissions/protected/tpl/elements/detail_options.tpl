<div id="admission-bar">
	<div class="left">
		<strong>View Details for:</strong>
		<select id="filterby">
			<option value="">Select an option...</option>
			{foreach $filterByOpts as $k => $v}
				<option value="{$k}"{if $filterby == $k} selected{/if}>{$v}</option>
			{/foreach}
		</select>
	</div>


