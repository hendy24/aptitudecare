<div class="container">
	<h1 class="text-center">Current Activities</h1>

	<div class="row activities">
		{foreach from=$activities item="activity" key="date"}
			<div class="col-12 mt-4 mb-2 info-item info-header-text">
				<h6>{$date|date_format:"%A, %B %e, %Y"}</h6>
			</div>
			{foreach from=$activity item="a"}
			<div class="col-lg-12">
				{if !empty($a->description)}<p
				>{if !empty($a->time_start)}{$a->time_start|date_format:"%l:%M %p"}{/if}
				&nbsp;&nbsp;{$a->description}</p>{/if}
			</div>
			{/foreach}
		{/foreach}
	</div>
</div>