
	<div class="container">
		<div class="row">
			<div class="text-center">
				<img src="{$IMAGES}/aspencreek-logo-black.png" alt="Logo" class="mx-auto d-block"/>
			</div>
		</div>
		<table class="activities">
			{foreach from=$activitiesArray key="date" item="activities"}
			<tr>
				<th colspan="4">{$date|date_format: "%A, %B %e"}</th>
			</tr>	
			{if is_array($activities)}
			<tr>
				{foreach from=$activities item="activity"}
				<td>{$activity->time_start|date_format: "%I:%M %p"}<br />{$activity->description}</td>
				{/foreach}
			</tr>	
				{/if}
			{/foreach}
			
		</table>
	</div>