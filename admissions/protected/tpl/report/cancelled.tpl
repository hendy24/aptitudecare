{setTitle title="AHC Reports"}
<h1 class="text-center">Not Admitted Report<br /><span class="text-16">for {$facility->name}</span></h1>
{include file="report/index.tpl"}


	<div class="sort-left-phrase">There are <strong>{count($cancelled)}</strong> total rejected inquiries for the selected timeframe.</div>
	
<div style="float: right; clear: both;">
	<strong>Order by:</strong>
	<select id="orderby">
		{foreach $orderByOpts as $k => $v}
			<option value="{$k}"{if $orderby == $k} selected{/if}>{$v}</option>
		{/foreach}
	</select>
</div>
<br />
<br />
<table id="report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Inquiry Name</th>
		<th>Desired Admit Date</th>
		<th>Referall Source</th>
		<th>Payment Method</th>
	</tr>
	{foreach $cancelled as $c}
	<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
		<td style="text-align: left;">{$c->getPatient()->fullName()}</td>
		<td>{$c->datetime_admit|date_format:"%m/%d/%Y"}</td>
		{$source = CMS_Hospital::generate()}
		{$source->load($c->hospital)}
		<td>{$source->name}</td>
		<td>{$c->paymethod}</td>
	</tr>
	{/foreach}
</table>

