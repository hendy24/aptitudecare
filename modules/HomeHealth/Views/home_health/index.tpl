<script src="{$JS}/admissions.js" type="text/javascript"></script>
<script>
	$(document).ready(function() {
		$(function() {
			$(document).tooltip();
		});
	});
</script>




{if !$isMicro}
	{$this->loadElement("homeHealthHeader")}
	<div id="date-header">
		<div class="date-header-img-left">
			<a href="{$SITE_URL}/?module=HomeHealth&amp;location={$loc->public_id}&amp;area={if $selectedArea == "all"}{$selectedArea}{else}{$selectedArea->public_id}{/if}&amp;weekSeed={$retreatWeekSeed}"><img class="left" src="{$FRAMEWORK_IMAGES}/icons/prev-icon.png" alt="previous week" /></a>
		</div>
		<div class="date-header-img-right">
			<a href="{$SITE_URL}/?module=HomeHealth&amp;location={$loc->public_id}&amp;area={if $selectedArea == "all"}{$selectedArea}{else}{$selectedArea->public_id}{/if}&amp;weekSeed={$advanceWeekSeed}"><img class="left" src="{$FRAMEWORK_IMAGES}/icons/next-icon.png" alt="next week" /></a>
		</div>
		<div class="date-header-text">
			<h2>{$week[0]|date_format:"%a, %B %d, %Y"} &ndash; {$week[6]|date_format:"%a, %B %d, %Y"}</h2>
		</div>

	</div>

{else}
	<div id="desktop-mode-button">
		<a href="{$SITE_URL}/?module=HomeHealth&amp;location={$this->getLocation()->public_id}{if $this->getArea()}&amp;area={$this->getArea()->public_id}{/if}" class="button">Desktop Mode</a>
	</div>
	<div class="is-micro">
		<h1>{$loc->name}</h1>
	</div>

{/if}




<div id="location-wrapper">
	{foreach $admitsByDate as $day => $admits}
	<div class="location-container">
		<h3 class="day-title">{$day|date_format:"%a, %b %e"}</h3>

		<div class="location-day-box location-day-box-admit {cycle name="admitDayColumn" values="location-day-box-color, "}" droppable="true">
			<input type="hidden" class="date" value="{$day}" />
			<div class="box-title">Admit</div>
			{foreach $admits as $admit}
			{if isset($admit->id)}
			<div class="location-admit{if $admit->status == "Under Consideration"} under-consideration {if $admit->confirmed} confirmed{/if} {elseif $admit->status == "Pending"} pending{elseif $admit->status == "Approved"} approved{/if}" draggable="true">
				<strong>{$admit->last_name}, {$admit->first_name}</strong>{$patientMenu->menu($admit)}<br>

				<input type="hidden" class="schedule-id" value="{$admit->hh_public_id}" />

				{$admit->healthcare_facility_name}<br>
			</div>
			{/if}
			{/foreach}


		</div>
	</div>
	{/foreach}

	<div class="clear"></div>
	<div class="horizontal-break"></div>

	{foreach $dischargesByDate as $day => $discharges}
	<div class="location-container">
		<h3 class="day-title">{$day|date_format:"%a, %b %e"}</h3>
		<div class="location-day-box location-day-box-discharge {cycle name="dischargeDayColumn" values="location-day-box-color, "}">
			<div class="box-title">Discharge</div>
			{foreach $discharges as $discharge}
			{if isset ($discharge->id)}
				<div class="location-discharge">
					<strong>{$discharge->last_name}, {$discharge->first_name}</strong><br>
				</div>
			{/if}
			{/foreach}
		</div>
	</div>
	{/foreach}

</div>
<div class="clear"></div>

{if !$isMicro}
<br>
<br>
<div id="legend">
	<h2>Color Legend</h2>
	<div class="location-admit">Potential admission.</div>
	<div class="location-admit confirmed">Confirmed admission.</div>
	<div class="location-admit pending">All items have been received and confirmed. Patient is pending final approval.</div>
	<div class="location-admit approved">Admission is approved and complete.</div>
</div>
{/if}

