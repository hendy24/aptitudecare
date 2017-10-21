<div id="normal-view" class="right"><a class="button" href="{$returnUrl}">Return to Previous Page</a></div>

<h1 class="text-center clear">Admission Report<br /><span class="text-16">for {$facility->name}</span></h1>
<br />
<h2 class="text-center">{if (isset ($admitFrom->name))}Admissions from {$admitFrom->name}{else}Referred by {$admitFrom->last_name}, {$admitFrom->first_name}{/if}</h2>

<table id="report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th width="50px"><a href="{$SITE_URL}/?page=report&action=details&facility={$facility->pubid}&type=admission&start_date={$start_date}&end_date={$end_date}&filterby={$filterby}&viewby={$viewby}&orderby=room">Room #</a></th>
		<th><a href="{$SITE_URL}/?page=report&action=details&facility={$facility->pubid}&type=admission&start_date={$start_date}&end_date={$end_date}&filterby={$filterby}&viewby={$viewby}&orderby=name">Patient Name</a></th>
		<th><a href="{$SITE_URL}/?page=report&action=details&facility={$facility->pubid}&type=admission&start_date={$start_date}&end_date={$end_date}&filterby={$filterby}&viewby={$viewby}&orderby=admit_date">Admit Date</a></th>
		<th width="150px"><a href="{$SITE_URL}/?page=report&action=details&facility={$facility->pubid}&type=admission&start_date={$start_date}&end_date={$end_date}&filterby={$filterby}&viewby={$viewby}&orderby=hospital">Hospital</a></th>
		<th><a href="{$SITE_URL}/?page=report&action=details&facility={$facility->pubid}&type=admission&start_date={$start_date}&end_date={$end_date}&filterby={$filterby}&viewby={$viewby}&orderby=physician">Attending Physician</a></th>
		<th><a href="{$SITE_URL}/?page=report&action=details&facility={$facility->pubid}&type=admission&start_date={$start_date}&end_date={$end_date}&filterby={$filterby}&viewby={$viewby}&orderby=surgeon">Specialist/Surgeon</a></th>
		<th><a href="{$SITE_URL}/?page=report&action=details&facility={$facility->pubid}&type=admission&start_date={$start_date}&end_date={$end_date}&filterby={$filterby}&viewby={$viewby}&orderby=case_manager">Case Manager</a></th>
	</tr>	
				
	{foreach $admits as $a}
	<tr class="text-left" bgcolor="{cycle values="#d0e2f0,#ffffff"}">
		<td>{$a->number}</td>
		<td>{$a->last_name}, {$a->first_name}</td>
		<td>{$a->datetime_admit|date_format:"%m/%d/%Y"}</td>
		<td>{$a->hospital_name}</td>
		<td>{if $a->physician_last != ''}{$a->physician_last}, {$a->physician_first} M.D.{else}</td>{/if}</td>
		<td>{if $a->surgeon_last != ''}{$a->surgeon_last}, {$a->surgeon_first} M.D.{else}</td>{/if}</td>

		<td>{if $a->cm_last != ''}{$a->cm_last}, {$a->cm_first}{else}</td>{/if}</td>
	</tr>
			
	{/foreach}
</table>

