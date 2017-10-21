{setTitle title="Length of Stay Report"}
{include file="patient/export_icons.tpl"}

{if !$isMicro}
<!-- <a href="{$SITE_URL}/?page=report&amp;action=length_of_stay&amp;facility={$facilityPubId}&amp;view={$view}&amp;year={$year}&isMicro=1" class="button">Print</a> -->
<h1 class="text-center">Length of Stay Report<br /><span class="text-16">for {$facility->name}</span></h1>
{include file="report/index.tpl"}
{jQueryReady}
	$("#filterby").change(function() {
		window.location = SITE_URL + "/?page=report&action=length_of_stay&facility={$facilityPubId}&view={$view}&year={$year}&filterby=" + $("#filterby option:selected").val();
	});
{/jQueryReady}
<br />
<div class="sort-left">
	<strong>Filter by:</strong>
	<select id="filterby">
		<option value="">Select an option...</option>
		{foreach $filterByOpts as $k => $v}
			<option value="{$k}"{if $filterby == $k} selected{/if}>{$v}</option>
		{/foreach}
	</select>
</div>
{/if}

{if $isMicro}
	<h1 class="text-center text-18">Length of Stay Report for {$facility->name}</h1>
	<table id="report-table" style="margin: 0 auto;">
{else}
	<table id="report-table" cellpadding="5" cellspacing="5">
{/if}
	{foreach $lengthOfStay as $key => $stay}
		{$total = array()}
		<tr bgcolor="#d1d1d1">
			<th colspan="3" >
			{if $view == "year"}
				{$key|date_format: "%Y"}
			{elseif $view == "quarter"}
				{assign var=report value=PageControllerReport::getQuarter($key)}
				{$report}
			{else}{$key|date_format: "%B %Y"}{/if}</th>
		</tr>
		<tr class="bold">
				<td>&nbsp;</td>
				<td>Total Discharges</td>
				<td>Average Length of Stay</td>
		</tr>
		{foreach $stay as $k => $s}
			<tr>
				{if $k != "totalDischarges" && $k != "avgLoS"}
				<td align="left"><a href="{$SITE_URL}/?page=report&amp;action=los_details&amp;facility={$facility->pubid}&amp;date_start={$s["minDate"]}&amp;date_end={$s["maxDate"]}&amp;discharge_to={$k}">{$k}</a></td>
				{/if}
				<td>{$s["totalDischarges"]}</td>
				<td>{$s["lengthOfStay"]}</td>
				{$total[] = $s["totalDischarges"]}
				{$totalLoS[] = $s["lengthOfStay"]}
			</tr>
		{/foreach}

		<tr style="border-top: 1px solid #000;" class="bold">
			<td align="right">Total</td>
			<td>{$stay["totalDischarges"]}</td>
			<td>{$stay["avgLoS"]|string_format: "%.2f"}</td>
		</tr>
		{if !$isMicro}
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		{/if}
	{/foreach}
	<tr class="report-total">
		<td>Year Totals</td>
		<td>{$yearInfo["totalDischarges"]}</td>
		<td>{$yearInfo["totalAvgLoS"]|string_format: "%.2f"}</td>
	</tr>
</table>
