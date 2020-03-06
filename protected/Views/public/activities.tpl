<div class="container">
	<h1 class="info-title text-center">Current Activities</h1>

	<div class="row new-section">
		{foreach from=$activities item="activity" key="date"}
			<div class="col-12 info-item info-header-text">
				{$date|date_format:"%A, %B %e, %Y"}
			</div>
			{foreach from=$activity item="a"}
			<div class="col-lg-4 col-md-6">
				{if !empty($a->description)}<p
				><span class="font-weight-bold">{if !empty($a->time_start)}{$a->time_start|date_format:"%l:%M %p"}{/if}</span>
				&nbsp;&nbsp;{$a->description}</p>{/if}
			</div>
			{/foreach}
		{/foreach}
	</div>
</div>