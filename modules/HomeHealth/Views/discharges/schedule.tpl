<script src="{$JS}/discharges.js" type="text/javascript"></script>
<script>
	$(document).ready(function() {
		var url = SITE_URL + "/?module=HomeHealth&page=discharges&action=schedule";

		$('#area').change(function() {
			window.location = url + "&location=" + $("#location").val() + "&area=" + $(this).val();
		});

		$("#location").change(function() {
			window.location = url + "&location=" + $(this).val();
		});
	});
</script>
{include file="$VIEWS/elements/{$searchBar}-search.tpl"}
<h2>Schedule Discharges</h2>

<div id="discharges">
	<div id="current-patients">
		<h2>Current Patients</h2>
	{foreach $current as $c}
		{if $c->datetime_discharge == ''}
		<div class="current-patient" droppable="true" >
			{$c->fullName()}
			<input type="hidden" name="public_id" value="{$c->schedule_pubid}">
		</div>
		{/if}
	{/foreach}
		<div class="clear"></div>
	</div>
	

	<br><br>
	<div id="date-header">
		<div class="date-header-img">
			<a href="{$SITE_URL}/?module=HomeHealth&amp;page=discharges&amp;action=schedule&amp;weekSeed={$retreatWeekSeed}"><img class="left" src="{$FRAMEWORK_IMAGES}/icons/prev-icon.png" /></a>
		</div>
		<div class="date-header-text-center">
			<h2>{$week[0]|date_format:"%a, %b %d, %Y"} &ndash; {$week[6]|date_format:"%a, %b %d, %Y"}</h2>
		</div>
		<div class="date-header-img">
		<a href="{$SITE_URL}/?module=HomeHealth&amp;page=discharges&amp;action=schedule&amp;weekSeed={$advanceWeekSeed}"><img class="left" src="{$FRAMEWORK_IMAGES}/icons/next-icon.png" /></a>	
		</div>	
	</div>

	<div class="clear"></div>


	<div id="discharge-container">
		{foreach $discharged as $day => $discharge}
			<div class="discharge-day-wrapper" >
				<h3>{$day|date_format:"%a, %b %e, %Y"}</h3>
				<input type="hidden" name="date" value="{$day}">
				<div class="discharge-day" droppable="true">
					{foreach $discharge as $d}
					{if isset($d->id)}
					<div class="discharge-info">
						{$d->fullName()}
						<input type="hidden" name="public_id" value="{$d->schedule_pubid}">
					</div>
					{/if}
					{/foreach}
				</div>
			</div>
		{/foreach}
				
	</div>
</div>