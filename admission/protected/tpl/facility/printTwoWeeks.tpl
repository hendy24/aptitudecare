{setTitle title="Two Week View"}

<h1 class="text-center">{$facility->name}</h1>
<div class="clear"></div>

<div class="side-titles">&nbsp;</div>

<div class="facility-container-first-week">

	{foreach $week as $day}
	<div class="facility-day-text {if $day@last}facility-day-text-last{/if}">
	
		<h3>{$day|date_format:"%A, %B %e"}</h3>
	
	</div>
	{/foreach}
		
	<div class="facility-admits">
	<div class="regular-titles"><strong>Admit</strong><br /></div>
		{foreach $week as $day}
		<div class="facility-day-box facility-day-box-admit">
			
			{$admits = $admitsByDate[$day]}
			
					
			{foreach $admits as $admit}
				<div class="{if $admit->status != 'Under Consideration'}print-facility-admit{else} print-facility-admit-pending{/if}">
					<span class="admit-name">{if $admit->onsite_visit_id != ''} *{/if} {if $admit->status != 'Under Consideration'}({$admit->getRoomNumber()}){/if} {$admit->getPatient()->fullName()}</span>	
				</div>
			{/foreach}
			</ul>
		
		</div>
		{/foreach}
		
	
	</div>
	
	<div class="clear"></div>
	
	<div class="facility-discharges">
		<div class="regular-titles"><strong>Discharge</strong><br /></div>
		{foreach $week as $day}
		<div class="facility-day-box facility-day-box-discharge">
				
			{$discharges = $dischargesByDate[$day]}
			
												
			<!-- Discharges -->
			{foreach $discharges as $discharge}
				<div class="print-facility-discharge"{if $discharge->hasBedhold()} class="print-discharge-bed-hold"{/if}>
					<span class="discharge-name">({$discharge->getRoomNumber()}) {$discharge->getPatient()->fullName()} {if $discharge->discharge_to == 'Discharge to hospital (bed hold)'}<br />Bed hold until {$discharge->datetime_discharge_bedhold_end|date_format: "%m/%d/%Y"}{/if}</span>	
				</div>
			{/foreach}
		</div>
		{/foreach}

	</div>
	
</div>


{*
 *
 *
 *
 *
 * Display information for the next week
 *
 *}

<div class="clear"></div>

<div class="side-titles">&nbsp;</div>

<div class="facility-container-second-week">

	{foreach $nextWeek as $nextWeekDay}
	<div class="facility-day-text {if $nextWeekDay@last}facility-day-text-last{/if}">
	
		<h3>{$nextWeekDay|date_format:"%A, %B %e"}</h3>
	
	</div>
	{/foreach}
	
	<div class="facility-admits">
		<div class="regular-titles"><strong>Admit</strong><br /></div>
		{foreach $nextWeek as $nextWeekDay}
		<div class="facility-day-box facility-day-box-admit">
			
			{$nextAdmits = $nextAdmitsByDate[$nextWeekDay]}
			

			{foreach $nextAdmits as $admit}
				<div class="print-facility-admit{if $admit->status == 'Under Consideration'} print-facility-admit-pending{/if}">
					<span class="admit-name">{if $admit->status != 'Under Consideration'}({$admit->getRoomNumber()}){/if} {if $admit->onsite_visit_id != ''}*{/if} {$admit->getPatient()->fullName()}</span>	
				</div>
			{/foreach}
		
		</div>
		{/foreach}
	
	</div>
	
	<div class="facility-discharges">
		<div class="regular-titles"><strong>Discharge</strong><br /></div>
		{foreach $nextWeek as $nextWeekDay}
		<div class="facility-day-box facility-day-box-discharge">
	
			{$nextDischarges = $nextDischargesByDate[$nextWeekDay]}
										
			<!-- Discharges -->
			{foreach $nextDischarges as $nd}
				<div class="print-facility-discharge"{if $nd->hasBedhold()} style="font-style: italic;"{/if}>
					<span class="discharge-name">({$nd->getRoomNumber()}) {$nd->getPatient()->fullName()}{if $nd->discharge_to == 'Discharge to hospital (bed hold)'}<br />Bed hold until {$nd->datetime_discharge_bedhold_end|date_format: "%m/%d/%Y"}{/if}</span>	
				</div>
			{/foreach}
		</div>
		{/foreach}

	</div>
	
</div>
