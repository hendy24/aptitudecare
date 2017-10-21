{setTitle title="Physicians | Admission Report"}
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

{include file="elements/detail_options.tpl"}
	<div id="normal-view" class="right"><a class="button">Return to Normal View</a></div>
</div>

	
<table id="summary-table" cellpadding="5" cell-spacing="0">
		<tr>
			<th>Physician Name</th>
			<th>Number of <br />Admissions</th>
			<th>% of <br />Total Admissions</th>
		</tr>
		{foreach $summaryReport as $r}
			<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
				<td style="text-align: left;"><a href="{$SITE_URL}/?page=report&action=details&facility={$facility->pubid}&type={$type}&start_date={$dateStart|date_format: "%m/%d/%Y"}&end_date={$dateEnd|date_format: "%m/%d/%Y"}&orderby={$orderby}&filterby={$filterby}&viewby={$r['id']}">{$r['name']}</a></td>
				<td>{$r['numberOfAdmits']}</td>
				<td>{$r['percentageOfAdmits']}%</td>
			</tr>
		{/foreach}
		<tr>
			<td><strong>TOTAL ADMISSIONS</strong></td>
			<td><strong>{$countTotalAdmits}</strong></td>
			<td></td>

		</tr>
	</table>
