<div id="date-header">
	<div class="date-header-img">
		<a href="{$siteUrl}/?module=HomeHealth&amp;location={$location->public_id}&amp;weekSeed={$previousWeekSeed}"><img class="left" src="/img/icons/prev-icon.png" /></a>
	</div>
	<div class="date-header-text-center">
		<h2>{$week[0]|date_format:"%a, %B %d, %Y"} &ndash; {$week[6]|date_format:"%a, %B %d, %Y"}</h2>
	</div>
	<div class="date-header-img">
	<a href="{$siteUrl}/?module=HomeHealth&amp;location={$location->public_id}&amp;weekSeed={$nextWeekSeed}"><img class="right" src="/img/icons/next-icon.png" /></a>		
	</div>	
</div>

<div class="clear"></div>

<div class="location-container">
	
	{foreach $week as $day}
	<div class="location-day-text {if $day@last}location-day-text-last{/if}">

		<h3>{$day|date_format:"%a, %b %e"}</h3>

	</div>
	{/foreach}

	<div class="location-admits">
		<input type="hidden" name="location" id="location-id" value="{$location->id}" />
		{foreach $week as $day}
			<div class="location-day-box location-day-box-admit {if $day@last}location-day-box-last{/if} {cycle name="admitDayColumn" values="location-day-box-blue, "}">
		
			{$admits = $admitsByDate[$day]}
			<div class="regular-titles"><strong>Admit</strong></div>
			{foreach $admits as $admit}
				<div class="{if $admit->status == 'Pending'} location-admit-pending{else} location-admit{/if}">
					<span class="admit-name"><strong>{$admit->last_name}, {$admit->first_name}</strong></span>
					
				</div>
				
			{/foreach}
			</div>
		{/foreach}
	</div>
</div>
	