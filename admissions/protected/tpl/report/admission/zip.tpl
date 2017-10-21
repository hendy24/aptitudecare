{setTitle title="Zip Code | Admission Report"}
{include file="patient/export_icons.tpl"}

<h1 class="text-center">Admission Report by Zip Code<br /><span class="text-16">for {$facility->name}</span></h1>
{include file="report/index.tpl"}

{include file="elements/detail_options.tpl"}
	<div id="normal-view" class="right"><a class="button">Return to Normal View</a><a class="button" href="{$SITE_URL}/?page=report&action=zip_map&facility={$facility->pubid}&start_date={$start_date}&end_date={$end_date}&orderby={$orderby}&filterby={$filterby}">View on Graph</a></div>
</div>
	
<table id="summary-table" cellpadding="5" cell-spacing="0">
	<tr>
		<th>Zip Code</th>
		<th>Number of Admissions</th>
		<th>% of <br />Total Admissions</th>
	</tr>
	{foreach $filterData as $d}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td><a href="{$SITE_URL}/?page=report&action=details&facility={$facility->pubid}&type={$type}&start_date={$dateStart|date_format: "%m/%d/%Y"}&end_date={$dateEnd|date_format: "%m/%d/%Y"}&orderby={$orderby}&filterby={$filterby}&viewby={$d->zip}">{$d->zip|default:'Not Entered'}</a></td>
			<td style="text-align: left;">{$d->count}</td>
			<td>{round($d->count/$countTotalAdmits, 2)*100}%</td>
		</tr>
	{/foreach}
	<tr>
		<td><strong>TOTAL ADMISSIONS</strong></td>
		<td>&nbsp;</td>
		<td><strong>{$countTotalAdmits}</strong></td>

	</tr>
</table>
