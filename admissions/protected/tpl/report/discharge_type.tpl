{setTitle title="Discharge Report"}
{include file="patient/export_icons.tpl"}
<!-- <a href="{$SITE_URL}/?page=report&amp;action=discharge&amp;facility={$facilityPubId}&amp;view={$view}&amp;year={$year}&isMicro=1" class="button">Print</a> -->
{if !$discharge_to}
	<h1 class="text-center">Discharge Type Report<br /><span class="text-16">for {$facility->name}</span></h1>
	{include file="report/index.tpl"}
	
<!--
	<div class="sort-left">
		<strong>Filter by:</strong>
		<select id="filterby">
			<option value="">Select an option...</option>
			{foreach $filterByOpts as $k => $v}
				<option value="{$k}"{if $filterby == $k} selected{/if}>{$v}</option>
			{/foreach}
		</select>
	</div>
-->




	<!-- !Main Discharge Report -->
	{if !$filterby}
		<table id="report-table" cellpadding="5" cellspacing="0">
			{foreach $dischargeData as $key => $data}
				<tr  class="report-total">
					<th colspan="2">
						{if $view == "year"}
							{$key|date_format: "%Y"}
						{elseif $view == "quarter"}
							{assign var=report value=PageControllerReport::getQuarter($key)}
							{$report}
						{else}{$key|date_format: "%B %Y"}{/if}</th>
					<th># of Discharges</th>
					<th>% of Discharges</th>
				</tr>
				{foreach $data as $k => $d}
					<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
						<td colspan="2" align="left" class="bold">{$k}</td>
						<td>{$d['dc_to_count']}</td>
						<td>{(($d['dc_to_count']/$d['dc_count'])*100)|string_format: "%.1f"}%</td>
						{foreach $d as $t => $detail}
							{if is_numeric($t)}
								</tr>
								<tr class="background-grey">
									<td>&nbsp;</td>
									<td align="left">{$detail['dc_disp']}</td>
									<td align="right" style="padding-right: 50px">{$detail['dc_disp_count']}</td>
									<td align="right" style="padding-right: 50px">{(($detail['dc_disp_count']/$d['dc_count'])*100)|string_format: "%.1f"}%</td>
								</tr>
								
							{/if}
						{/foreach}
						
					</tr>
						
				{/foreach}
				<tr class="bold" style="border-top: 1px solid black">
					<td>&nbsp;</td>
					<td align="right">Total</td>
					<td>{$d['dc_count']}</td>
					<td></td>
				</tr>	
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			{/foreach}
		</table>
	
	
		
	<!-- !Filtered Discharge Report -->
	{else}
		<table id="report-table" cellpadding="5" cellspacing="0">
			{foreach $data as $k => $d}
				{if !empty($d)}
					<tr  class="report-total">
						<th>
							{if $view == "year"}
								{$k|date_format: "%Y"}
							{elseif $view == "quarter"}
								{assign var=report value=PageControllerReport::getQuarter($k)}
								{$report}
							{else}{$k|date_format: "%B %Y"}{/if}</th>
						<th># of Discharges</th>
						<th>% of Discharges</th>
					</tr>
					{foreach $d as $i}						
						{foreach $countData as $key => $count}
							{foreach $count as $c}
								{if ($key == $k)}
									{$totalDc = $c->dc_count}
								{/if}
							{/foreach}
						{/foreach}
						
						<tr>
							<td align="left"><a href="{$SITE_URL}/?page=report&amp;action=discharge&amp;facility={$facility->pubid}&amp;view={$view}&amp;year={$year}&amp;discharge_to={$d['discharge_to']}&amp;discharge_disposition={$dc_disp}&amp;dateStart={$k}&amp;filterby={$filterby}">{if $i->discharge_to == ''}<span class="text-red">{/if}{$i->discharge_to|default: "No Discharge Type"}{if $i->dc_to == ''}</span>{/if}</a></td>
							<td>{$i->dc_count}</td>
							<td>{(($i->dc_count/$totalDc)*100)|string_format: "%.1f"}%</td>
						</tr>
					{/foreach}
					<tr class="bold" style="border-top: 1px solid black">
						<td align="right">Total</td>
						<td>{$totalDc}</td>
						<td></td>
					</tr>				
					<tr>
						<td>&nbsp;</td>
					</tr>
				{/if}
			{/foreach}
		</table>

	{/if}
	
	
	
	
<!-- !Discharge To details -->
{else}
	{jQueryReady}
		$("#orderby").change(function(e) {
			window.location.href = SITE_URL + '/?page=report&action=discharge&facility={$facility->pubid}&view={$view}&year={$year}&discharge_to={$discharge_to}&dateStart={$dateStart}&filterby={$filterby}&orderby=' + $("#orderby option:selected").val();
		});

	{/jQueryReady}
	<h1 class="text-center">{$discharge_to}<br /><span class="text-16">for {$facility->name}</span></h1>
	<a href="{$SITE_URL}/?page=report&amp;action=discharge&amp;facility={$facility->pubid}&amp;view={$view}&amp;year={$year}&amp;filterby={$filterby}" class="button">Back</a>
	<div class="sort-right">
	<strong>Order by:</strong>
	<select id="orderby">
			<option value=""></option>
		{foreach $orderByOpts as $k => $v}
			<option value="{$k}"{if $orderby == $k} selected{/if}>{$v}</option>
		{/foreach}
	</select>
</div>
	<br />
	<br />
	<br />
	<br />
	<table id="report-table" cellpadding="5" cellspacing="0">
		<tr  class="report-total">
			<th>Patient Name</th>
			<th>Discharge Disposition</th>
			<th>Service Disposition</th>
			<th>{if $discharge_to == "Transfer to another AHC facility"}Facility{else}Discharge Location Name{/if}</th>
		</tr>
		{foreach $data as $k => $d}	
			{foreach $d as $i}
				<tr bgcolor="{cycle values="#ffffff,#d0e2f0"}">
					<td><a href="{$SITE_URL}/?page=patient&amp;action=inquiry&amp;schedule={$i->schedule_id}">{$i->last_name}, {$i->first_name}</a></td>
					<td>{$i->discharge_disposition}</td>
					<td>{$i->service_disposition}</td>
					<td>{if $discharge_to == "Transfer to another AHC facility"}{$i->facility_name}{else}{$i->hospital_name}{/if}</td>
				</tr>
			{/foreach}
		{/foreach}
	</table>
	
{/if}