{setTitle title="AHC Reports"}
{include file="patient/patient_search.tpl"}
{include file="patient/export_icons.tpl"}

<h1 class="text-center">Admission Report<br /><span class="text-16">for {$facility->name}</span></h1>
{include file="report/index.tpl"}
	
<!--
<div class="sort-right">
	<strong>Order by:</strong>
	<select id="orderby">
		{foreach $orderByOpts as $k => $v}
			<option value="{$k}"{if $orderby == $k} selected{/if}>{$v}</option>
		{/foreach}
	</select>
</div>
-->

	<div id="admission-report-details" class="left">
		<strong>View Details for:</strong><br />
		<select id="filterby">
			<option value="">Select an option...</option>
			{foreach $filterByOpts as $k => $v}
				<option value="{$k}"{if $filterby == $k} selected{/if}>{$v}</option>
			{/foreach}
		</select>
	</div>

	<div class="right-phrase">There were <strong>{count($admits)}</strong> total admissions for the selected time period.</div>

<br />

<table id="report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Room #</th>
		<th>Patient Name</th>
		<th>Admit Date</th>
		<th width="150px">Hospital</th>
		<th>PCP</th>
		<th>Attending Physician</th>
		<th>Specialist/Surgeon</th>
		<th>Case Manager</th>
	</tr>	
				
	{foreach $admits as $a}
	<tr class="text-left" bgcolor="{cycle values="#d0e2f0,#ffffff"}">
		<td>{$a->number}</td>
		<td>{$a->last_name}, {$a->first_name}</td>
		<td>{$a->datetime_admit|date_format:"%m/%d/%Y"}</td>
		<td>{$a->hospital_name}</td>
		<td>{if $a->pcp_last != ''}{$a->pcp_last}, {$a->pcp_first} M.D.{else}&nbsp;{/if}</td>
		<td>{if $a->physician_last != ''}{$a->physician_last}, {$a->physician_first} M.D.{else}</td>{/if}</td>
		<td>{if $a->surgeon_last != ''}{$a->surgeon_last}, {$a->surgeon_first} M.D.{else}</td>{/if}</td>

		<td>{if $a->cm_last != ''}{$a->cm_last}, {$a->cm_first}{else}</td>{/if}</td>
	</tr>
			
	{/foreach}
</table>
