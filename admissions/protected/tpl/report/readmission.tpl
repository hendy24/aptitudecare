{setTitle title="AHC Reports"}
{include file="patient/export_icons.tpl"}

<h1 class="text-center">Re-Admission Report<br /><span class="text-16">for {$facility->name}</span></h1>

{include file="report/index.tpl"}
<div class="left">
	<strong>Re-Admission Type:</strong>
	<select id="readmit-type">
		<option value="">Select Type...</option>
		<option value="Former Patient" {if $readmitType == "Former Patient"} selected{/if}>Former Patient</option>
		<option value="From Hospital" {if $readmitType == "From Hospital"} selected{/if}>From Hospital</option>
	</select>
</div>
<div class="right" style="margin-bottom: 35px;">
	<strong>Order by:</strong>
	<select id="orderby">
		{foreach $orderByOpts as $k => $v}
			<option value="{$k}"{if $orderby == $k} selected{/if}>{$v}</option>
		{/foreach}
	</select>
</div>

<br />
<div class="clear">
	<strong>{$readmit|@count} total re-admissions</strong><br />
</div>
<br />
<table id="report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Patient Name</th>
		<th>Re-Admit Date</th>
		<th>Type of Re-Admission</th>
		<th>Hospital</th>
		<th>Attending Physician</th>
	</tr>
	{foreach $readmit as $r}
	<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
		<td style="text-align: left;">{$r->getPatient()->fullName()}</td>
		<td>{$r->datetime_admit|date_format:"%m/%d/%Y"}</td>
		<td>{$r->readmit_type|capitalize}</td>
		{$hospital = CMS_Hospital::generate()}
		{$hospital->load($r->hospital_id)}
		<td>{$hospital->name}</td>
		{$physician = CMS_Physician::generate()}
		{$physician->load($r->getPatient()->physician_id)}
		<td>Dr. {$physician->last_name}</td>
		<td></td>
	</tr>	
	{/foreach}
</table>
