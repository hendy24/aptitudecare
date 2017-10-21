{setTitle title="Print AHC Reports"}
{javascript}
{/javascript}
{jQueryReady}

$inputs = $("#inquiry-form select");
$.each($inputs, function(i, elem) {
	var val = $(elem).val();
	$(elem).before("<span>" + val + "</span>").remove();
});

{/jQueryReady}

<script type="text/javascript">
/*$(window).load(function() {
	window.print();
});*/
</script>

<style type="text/css">
html, body {
	background: none;
	font-size: 13px;
}
#content-container {
	background: none;
}
table {
	border-top: 1px solid #000;
	border-left: 1px solid #000;
	margin-left: auto;
	margin-right: auto;
}
td, th {
	font-size: 13px;
	border-bottom: 1px solid #000;
	border-right: 1px solid #000;
}
input {
	background: none;
	color: #000;
	border: none;
}
#header-container {
	display: none;
}
h1 {
	text-align: center;
	margin: 0px 0px 0px 0px;
	color: #000000;
	font-size: 16px;
	margin-bottom: 5px;
}

h2 {
	float: left;
	margin: 0;
}

p {
	text-align: center;
	font-size: 12px;
	margin-top: 0;
	margin-bottom: 5px;
}

.back-link {
	display: none;	
}

td {
	font-size: 13px;
}
</style>

<h1>{$type|capitalize} Report for {$facility->name}</h1>
<p>From {$dateStart|date_format: "%B %e, %Y"} to {$dateEnd|date_format: "%B %e, %Y"}</p>
<form name="report" method="post" action="{$SITE_URL}" id="inquiry-form"> 
	<input type="hidden" name="page" value="coord" />
	<input type="hidden" name="action" value="submitReport" />
	<table cellpadding="5" cellspacing="0">
		
		<!-- Admission Report -->
		{if $type == 'admit'}
			<tr>
				<th>Room #</th>
				<th>Patient Name</th>
				<th>Admit Date</th>
				<th>Primary Physician</th>
				<th>Specialist/Surgeon</th>
				<th>Date Seen</th>
			</tr>
			{foreach $admits as $a}
			<tr bgcolor="{cycle values="#eeeeee,#ffffff"}">
				<td>{$a->number}</td>
				<td>{$a->getPatient()->fullName()}</td>
				<td>{$a->datetime_admit|date_format:"%m/%d/%Y"}</td>
				{$physician = CMS_Physician::generate()}
				{$physician->load($a->getPatient()->physician_id)}
				<td>{if $a->getPatient()->physician_id != ''}{$physician->last_name}, {$physician->first_name} M.D.{/if}</td>
				{$ortho = CMS_Physician::generate()}
				{$ortho->load($a->getPatient()->ortho_id)}
				<td>{if $a->getPatient()->ortho_id != ''}{$ortho->last_name}, {$ortho->first_name} M.D.{/if}</td>
				<td>{$a->datetime_first_seen|date_format:"%m/%d/%Y"}</td>
			</tr>
					
			{/foreach}
		{/if}

		<!-- Discharge Report -->
		{if $type == 'discharge'}
			<tr>
				<th>Patient Name</th>
				<th>Admit Date</th>
				<th>D/C Date</th>
				<th>D/C Disposition</th>
				<th>LoS</th>
				<th>Primary Physician</th>
			</tr>
			{foreach $discharges as $d}
			<tr bgcolor="{cycle values="#eeeeee,#ffffff"}">
				<td>{$d->getPatient()->fullName()}</td>
				<td>{$d->datetime_admit|date_format:"%m/%d/%Y"}</td>
				<td>{$d->datetime_discharge|date_format:"%m/%d/%Y"}</td>
				<td>{$d->discharge_disposition}</td>
				<td>{$d->los($d->datetime_discharge, $d->datetime_admit)} days</td>
				<td>{$d->getPatient()->physician_name}</td>
			</tr>
			{/foreach}
		{/if}

		<!-- Cancell inquiry report -->
		{if $type == 'cancelled' && $facility != ''}
			<tr>
				<th>Inquiry Name</th>
				<th>Desired Admit Date</th>
				<th>Referall Source</th>
				<th>Payor Source</th>
			</tr>
			{foreach $rejected as $r}
			<tr bgcolor="{cycle values="#eeeeee,#ffffff"}">
				<td>{$r->getPatient()->fullName()}</td>
				<td>{$r->datetime_admit|date_format:"%m/%d/%Y"}</td>
				<td>{$r->name}</td>
				<td>{$r->paymethod}</td>
			</tr>
			{/foreach}

		{/if}

		<!-- Re-Admission Report -->
		{if $type == 're-admission'}
			<tr>
				<th>Patient Name</th>
				<th>Hospital</th>
				<th>Sent</th>
				<th>ICD-9 Code</th>
				<th>Primary Physician</th>
				<th>Re-Admit to AHC</th>
			</tr>
			{foreach $readmitReport as $r}
			<tr bgcolor="{cycle values="#eeeeee,#ffffff"}">
				<td>{$r->getPatient()->fullName()}</td>
				<td>{$r->hospital_name}</td>
				<td>{$r->datetime_sent|date_format:"%m/%d/%Y"}</td>
				{if $r->code != ''}
				<td>{$r->short_desc} [{$r->code}]</td>
				{else}
				<td>&nbsp;</td>
				{/if}
				<td>{$r->getPatient()->physician_name}</td>
				<td>{if $r->readmit_type == 'hospital'}{$datetime_admit|date_format:"%m/%d/%Y"}{/if}</td>
			</tr>	
			{/foreach}
		{/if}

		<!-- Returned to hospital report -->
		{if $type == 'returned_to_hospital'}
			<tr>
				<th>Patient Name</th>
				<th>Hospital</th>
				<th>Sent</th>
				<th>ICD-9 Code</th>
				<th>Primary Physician</th>
				<th>Re-Admit to AHC</th>
			</tr>
			{foreach $returnedReport as $r}
			{$hospital = CMS_Hospital::generate()}
			{$hospital->load($r->hospital)}
			<tr bgcolor="{cycle values="#eeeeee,#ffffff"}">
				<td class="text-left">{$r->getPatient()->fullName()}</td>
				<td>{$hospital->name}</td>
				<td>{$r->datetime_sent|date_format:"%m/%d/%Y"}</td>
				{if $r->code != ''}
				<td>{$r->short_desc} [{$r->code}]</td>
				{else}
				<td>&nbsp;</td>
				{/if}
				<td>{$r->getPatient()->physician_name}</td>
				<td></td>
			</tr>	
			{/foreach}
		{/if}


	</table>
	
</form> 
