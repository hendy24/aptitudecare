{setTitle title="AHC Reports: Returned to Hospital"}
{include file="patient/export_icons.tpl"}

{include file="patient/patient_search.tpl"}
<h1 class="text-center">Returned To Hospital Report<br /><span class="text-16">for {$facility->name}</span></h1>
{include file="report/index.tpl"}



<div id="report-info">
	<div class="left-info background-blue">
		Re-Admit to Hospital Rate:<strong>{$readmitRate}%</strong>.
	</div>
	
	<div class="right-info">
		<strong>Order by:</strong>
		<select id="orderby">
			{foreach $orderByOpts as $k => $v}
				<option value="{$k}"{if $orderby == $k} selected{/if}>{$v}</option>
			{/foreach}
		</select>
	</div>
</div>
<table id="report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Patient Name</th>
		<th>Hospital</th>
		<th>Admission Date</th>
		<th>Sent</th>
		<th>Comment</th>
		<th>Attending Physician</th>
		<th>Re-Admit to AHC</th>
	</tr>
	{foreach $returnedReport as $r}
	{$hospital = CMS_Hospital::generate()}
	{$hospital->load($r->hospital)}
	<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
		<td class="text-left">{$r->getPatient()->fullName()}</td>
		<td>{$hospital->name}</td>
		<td>{$r->datetime_admit|date_format:"%m/%d/%Y"}</td>
		<td>{$r->datetime_sent|date_format:"%m/%d/%Y"}</td>
		<td style="text-align:left">{$r->comment}</td>
		{if $r->getPatient()->physician_id != ''}
		{$physician = CMS_Physician::generate()}
		{$physician->load($r->getPatient()->physician_id)}
		<td>Dr. {$physician->last_name}</td>
		{elseif ($r->getPatient()->physician_name != '')}
		<td>{$r->getPatient()->physican_name}</td>
		{else}
		<td></td>
		{/if}
		<td>{$r->datetime_returned|date_format:"%m/%d/%Y"}</td>
	</tr>	
	{/foreach}
</table>
