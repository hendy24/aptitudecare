{setTitle title="AHC Reports"}
<h1 class="text-center">AHC Reports</h1>
{if $type != '' && $facility != '' && $dateStart != '' && $dateEnd != ''}
	<a href="{$SITE_URL}/?page=coord&amp;action=printReport&amp;facility={$facility->pubid}&amp;type={$type}&amp;start_date={$dateStart|date_format: "%Y-%m-%d"}&amp;end_date={$dateEnd|date_format: "%Y-%m-%d"}&orderby={$orderby}&filterby={$filterby}&viewby={$viewby}&amp;isMicro=1" target="_blank" class="printForm">Print Report</a>
{/if}
{jQueryReady}
	$("#report-search").click(function(e) {
		window.location.href = SITE_URL + '/?page=coord&action=reports&facility=' + $("#facility option:selected").val() + '&type=' + $("#report-type option:selected").val() + '&start_date=' + $("#start-date").val() + '&end_date=' + $("#end-date").val();		
	});

$("#orderby").change(function(e) {
	window.location.href = SITE_URL + '/?page=coord&action=reports&facility={$facility->pubid}&type={$type}&start_date={$dateStart|date_format: "%m/%d/%Y"}&end_date={$dateEnd|date_format: "%m/%d/%Y"}&orderby=' + $("option:selected", this).val() + "&filterby=" + $("#filterby option:selected").val() + "&viewby=" + $("#viewby option:selected").val();
});

$("#filterby").change(function(e) {
	window.location.href = SITE_URL + '/?page=coord&action=reports&facility={$facility->pubid}&type={$type}&start_date={$dateStart|date_format: "%m/%d/%Y"}&end_date={$dateEnd|date_format: "%m/%d/%Y"}&orderby={$orderby}&filterby=' + $("option:selected", this).val();
});

$("#view-by").hide();

$("#view-by").change(function(e) {
	window.location.href = SITE_URL + '/?page=coord&action=reports&facility={$facility->pubid}&type={$type}&start_date={$dateStart|date_format: "%m/%d/%Y"}&end_date={$dateEnd|date_format: "%m/%d/%Y"}&orderby={$orderby}&filterby={$filterby}&viewby=' + $("option:selected", this).val();
});


$(".dischargeInfo").fancybox({
	'transitionIn' 	:	'elastic',
	'transitionOut'	:	'elastic',
	'speedIn'		:	600,
	'speedOut'		:	200,
	'overlayShow'	:	true,
	'overlayOpacity' :	0.3,
	'overlayColor'	:	'#333'
});

{/jQueryReady}

{if $filterby != ''}
	{jQueryReady}
		$("#view-by").show();

	{/jQueryReady}
{/if}

{$facilities = $auth->getRecord()->getFacilities()}

{$reportTypes = [
	'admit' => 'Admission',
	'discharge' => 'Discharge',
	'cancelled' => 'Not Admitted',
	'returned_to_hospital' => 'Returned to Hospital',
	're-admission' => 'Re-Admission'
]}

<table id="select-report-info" cellpadding="5">
	<tr>
		<td align="top">
			<strong>Run report for</strong><br />
				<select id="facility">
					<option value="">Select a facility...&nbsp;&nbsp;</option>
					{foreach $facilities as $f}
    					<option value="{$f->pubid}"{if $f->id == $facility->id} selected{/if}>{$f->name}</option>
					{/foreach}
				</select>
		</td>
		<td>
			<strong>Type of Report</span><br />
			<select id="report-type">
				<option value="">Select the type of report...</option>
				{foreach $reportTypes as $k => $v}
				<option value="{$k}"{if $type == $k} selected{/if}>{$v}</option>
				{/foreach}
			</select>
		</td>
		<td valign="top">
			<strong>Start Date:</strong><br />
			<input type="text" id="start-date" class="date-picker" value="{$dateStart|date_format: "%m/%d/%Y"}" />
		</td>
		<td valign="top">
			<strong>End Date:</strong><br />
			<input type="text" id="end-date" class="date-picker" value="{$dateEnd|date_format: "%m/%d/%Y"}" />
		</td>
	</tr>
	<tr>
		<td colspan="4" align="right"><input type="button" value="Search" id="report-search" /></td>
	</tr>
</table>


<!-- Admission Report -->
{if $type == 'admit' && $facility != ''}
	
	<!-- orderby options -->
	{$orderByOpts = [
		'room_ASC' => 'Room # (lowest to highest)',
		'patient_name_ASC' => 'Patient Name (A &rarr; Z)',
		'datetime_admit_DESC' => 'Admission Date (most recent first)',
		'datetime_admit_ASC' => 'Admission Date (oldest first)',
		'physician_ASC' => 'Attending Physician (A &rarr; Z)',
		'surgeon_ASC' => 'Specialist/Surgeon (A &rarr; Z)',
		'datetime_seen_DESC' => 'Date first seen by Physician (most recent first)',
		'datetime_seen_ASC' => 'Date first seen by Physician (oldest first)'
	]}
	
	{$filterByOpts = [
		'hospital' => 'Hospital',
		'physician' => 'Attending Physician',
		'ortho' => 'Orthopedic Surgeon/Specialist'
	]}
	
	

	<h2 class="text-center">Admission Report for {$facility->name}</h2>
	<br />
	<br />
	<div class="sort-right">
		<strong>Order by:</strong>
		<select id="orderby">
			{foreach $orderByOpts as $k => $v}
				<option value="{$k}"{if $orderby == $k} selected{/if}>{$v}</option>
			{/foreach}
		</select>
	</div>
	<div class="sort-left">
		<strong>Filter by:</strong>
		<select id="filterby">
			<option value="">Select an option...</option>
			{foreach $filterByOpts as $k => $v}
				<option value="{$k}"{if $filterby == $k} selected{/if}>{$v}</option>
			{/foreach}
		</select>
	</div>
	<div class="sort-left2" id="view-by">
		<strong>View by:</strong>
		<select id="viewby">
			<option value="">Select...</option>		
			{if $filterby == 'hospital'}
				{foreach $filterData as $d}
					<option value="{$d->hospital_id}"{if $viewby == $d->hospital_id} selected{/if}>{$d->name}</option>
				{/foreach}
			{elseif $filterby == 'physician'}
				{foreach $filterData as $d}
					{$p = CMS_Physician::generate()}
					{$p->load($d->physician_id)}
					<option value="{$d->physician_id}"{if $viewby == $d->physician_id} selected{/if}>{$p->last_name}, {$p->first_name} M.D.</option>
				{/foreach}
			{elseif $filterby == 'ortho'}
				{foreach $filterData as $d}
					{$ortho = CMS_Physician::generate()}
					{$ortho->load($d->ortho_id)}
					<option value="{$d->ortho_id}"{if $viewby == $d->ortho_id} selected{/if}>{$ortho->last_name}, {$ortho->first_name} M.D.</option>
				{/foreach}
			{/if}
		</select>
	</div>
	<div class="text-14 clear sort-left">There were <strong>{count($admits)}</strong> total admissions for the selected time period.</div>

	<table id="report-table" cellpadding="5" cellspacing="0">
		<tr>
			<th>Room #</th>
			<th>Patient Name</th>
			<th>Admit Date</th>
			<th>Hospital</th>
			<th>Attending Physician</th>
			<th>Specialist/Surgeon</th>
			<th>Initial Visit</th>
		</tr>		
		{foreach $admits as $a}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td>{$a->number}</td>
			<td align="left">{$a->getPatient()->fullName()}</td>
			<td>{$a->datetime_admit|date_format:"%m/%d/%Y"}</td>
			
			{$h = CMS_Hospital::generate()}
			{$h->load($a->getPatient()->hospital_id)}
			<td>{$h->name}</td>
			
			{$physician = CMS_Physician::generate()}
			{$physician->load($a->getPatient()->physician_id)}
			{if $a->getPatient()->physician_id != ''}<td>{$physician->last_name}, {$physician->first_name} M.D.{else}<td>{/if}</td>
			
			
			{$ortho = CMS_Physician::generate()}
			{$ortho->load($a->getPatient()->ortho_id)}
			<td>{if $a->getPatient()->ortho_id != ''}{$ortho->last_name}, {$ortho->first_name} M.D.{else}<td>{/if}</td>

			<td>{if $a->datetime_first_seen == '0000-00-00 00:00:00'}{else}{$a->datetime_first_seen|date_format:"%m/%d/%Y"}{/if}</td>
		</tr>
				
		{/foreach}
	</table>
{/if}


<!-- Discharge Report -->
{if $type == 'discharge' && $facility != ''}
	{$orderByOpts = [
		'datetime_discharge_ASC' => 'Discharge Date (oldest first)',
		'datetime_discharge_DESC' => 'Discharge Date (most recent first)',
		'patient_name_ASC' => 'Patient Name (A &rarr; Z)',
		'datetime_admit_DESC' => 'Admission Date (most recent first)',
		'datetime_admit_ASC' => 'Admission Date (oldest first)',
		'discharge_disposition_ASC' => 'Discharge Disposition',
		'physician_ASC' => 'Attending Physician (A &rarr; Z)'
	]}
	
	{$filterByOpts = [
		'discharge_disposition' => 'Discharge Disposition',
		'home_health_disposition' => 'Home Health',
		'physician' => 'Attending Physician'
	]}

	<h2 class="text-center">Discharge Report for {$facility->name}</h2>
	<br />
	<br />
	<div class="sort-right">
		<strong>Order by:</strong>
		<select id="orderby">
			{foreach $orderByOpts as $k => $v}
				<option value="{$k}"{if $orderby == $k} selected{/if}>{$v}</option>
			{/foreach}
		</select>
	</div>
		<div class="sort-left">
		<strong>Filter by:</strong>
		<select id="filterby">
			<option value="">Select an option...</option>
			{foreach $filterByOpts as $k => $v}
				<option value="{$k}"{if $filterby == $k} selected{/if}>{$v}</option>
			{/foreach}
		</select>
	</div>
	
	<div class="sort-left2" id="view-by">
		<strong>View by:</strong>
		<select id="viewby">
			<option value="">Select...</option>		
			{if $filterby == 'discharge_disposition'}
				{foreach $filterData as $d}
					{if $d->discharge_disposition != '' && $d->discharge_disposition != 'Home with AHC Home Health' && $d->discharge_disposition != 'Home with other home health'}
						<option value="{$d->discharge_disposition}"{if $viewby == $d->discharge_disposition} selected{/if}>{$d->discharge_disposition}</option>
					{/if}
				{/foreach}
			{elseif $filterby =='home_health_disposition'}
				{foreach $filterData as $d}
					<option value="{$d->home_health_disposition}"{if $viewby == $d->home_health_disposition} selected{/if}>{$d->home_health_disposition}</option>
				{/foreach}
			{elseif $filterby == 'physician'}
				{foreach $filterData as $d}
					{$p = CMS_Physician::generate()}
					{$p->load($d->physician_id)}
					<option value="{$d->physician_id}"{if $viewby == $d->physician_id} selected{/if}>{$p->last_name}, {$p->first_name} M.D.</option>
				{/foreach}
			{/if}
		</select>
	</div>
	<div class="sort-left-phrase">The <strong>Average Length of Stay (LoS)</strong> for the selected time period is <strong>{$totalDays}</strong> days.</div>

	<br />
	<br />
	<table id="report-table" cellpadding="5" cellspacing="0">
		<tr>
			<th>Patient Name</th>
			<th>Admit Date</th>
			<th>Discharge Date</th>
			<th>Discharge Disposition</th>
			<th>Home Health</th>
			<th>LoS</th>
			<th>Attending Physician</th>
		</tr>
		{foreach $discharges as $d}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td align="left">{$d->last_name}, {$d->first_name}</td>
			<td>{$d->datetime_admit|date_format:"%m/%d/%Y"}</td>
			<td>{$d->datetime_discharge|date_format:"%m/%d/%Y"}</td>
			
			<!-- Discharge Disposition -->
			<td>{if $d->discharge_disposition == "Group Home"  || $d->discharge_disposition == "Assisted Living"}<a class="dischargeInfo" href="/?page=coord&action=discharged_patient_info&patient={$d->getPatient()->pubid}&schedule={$d->SchedulePubid}&isMicro=1">{$d->discharge_disposition}</a>{elseif $d->discharge_disposition == 'Home with AHC Home Health' || $d->discharge_disposition == 'Home with other home health'}Home{else}{$d->discharge_disposition}{/if}</td>
			
			<!-- Home Health Disposition -->
			<td>{if $d->discharge_disposition == 'Home with AHC Home Health'}AHC Home Health{elseif $d->discharge_disposition == 'Home with other home health'}<a class="dischargeInfo" href="/?page=coord&action=discharged_patient_info&patient={$d->getPatient()->pubid}&schedule={$d->SchedulePubid}&isMicro=1">Other Home Health</a>{else}{$d->home_health_disposition}{/if}</td>
			
			<td>{$d->los($d->datetime_discharge, $d->datetime_admit)} days</td>
			{if $d->physician_id != ''}
			{$physician = CMS_Physician::generate()}
			{$physician->load($d->physician_id)}
			<td>{$physician->last_name}, {$physician->first_name} M.D.</td>
			{elseif $d->physician_name != ''}
			<td>{$d->physician_name|Default:"Not entered"}</td>
			{else}
			<td></td>
			{/if}
		</tr>
		{/foreach}
	</table>

{/if}

<!-- Not Admitted inquiries report -->
{if $type == 'cancelled' && $facility != ''}
{$orderByOpts = [
	'datetime_admit_ASC' => 'Desired Admission Date (oldest first)',
	'datetime_admit_DESC' => 'Desired Admission Date (most recent first)',
	'hospital_name_ASC' => 'Referall Source (Hospital name)',
	'paymethod_ASC' => 'Payment Method'
	]}

	<h2 class="text-center">Rejected Inquiry Report for {$facility->name}</h2>
	<br />
	<br />
	<span class="text-14">There are <strong>{count($cancelled)}</strong> total rejected inquiries for the selected timeframe.
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
			<td align="left">{$c->getPatient()->fullName()}</td>
			<td>{$c->datetime_admit|date_format:"%m/%d/%Y"}</td>
			{$source = CMS_Hospital::generate()}
			{$source->load($c->hospital)}
			<td>{$source->name}</td>
			<td>{$c->paymethod}</td>
		</tr>
		{/foreach}
	</table>

{/if}


<!-- Returned to Hospital Report -->
{if $type == 'returned_to_hospital' && $facility != ''}
{$orderByOpts = [
'datetime_sent_DESC' => 'Sent to Hospital Date (most recent first)',
'datetime_sent_ASC' => 'Sent to Hospital Date (oldest first)',
'patient_name_ASC' => 'Patient Name (A &rarr; Z)',
'physician_ASC' => 'Attending Physician (A &rarr; Z)'
]}

<h2 class="text-center">Returned to Hospital Report for {$facility->name}</h2>
<br />
	<br />
	<span class="text-14">The <strong>Re-Admit to hospital Rate</strong> for the selected time period is <strong>{$readmitRate}%</strong>.</span>
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
			<th>Patient Name</th>
			<th>Hospital</th>
			<th>Sent</th>
			<th>Discharge<br />Diagnosis</th>
			<th>Attending Physician</th>
			<th>Re-Admit to AHC</th>
		</tr>
		{foreach $returnedReport as $r}
		{$hospital = CMS_Hospital::generate()}
		{$hospital->load($r->hospital)}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td class="text-left">{$r->getPatient()->fullName()} {$r->getPatient()->id}</td>
			<td>{$hospital->name}</td>
			<td>{$r->datetime_sent|date_format:"%m/%d/%Y"}</td>
			{if $r->code != ''}
			<td>{$r->short_desc} [{$r->code}]</td>
			{else}
			<td>&nbsp;</td>
			{/if}
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
{/if}


<!-- Re-admission report -->
{if $type == 're-admission' && $facility != ''}
{$orderByOpts = [
'datetime_admit_DESC' => 'Admission Date (most recent first)',
'datetime_admit_ASC' => 'Admission Date (oldest first)',
'patient_name_ASC' => 'Patient Name (A &rarr; Z)',
'readmit_type' => 'Type of Re-Admission (A &rarr; Z)',
'physician_ASC' => 'Attending Physician (A &rarr; Z)'
]}

<h2 class="text-center">Re-Admitted to {$facility->name} Report</h2>
<br />
	<br />
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
			<th>Patient Name</th>
			<th>Re-Admit Date</th>
			<th>Type of Re-Admission</th>
			<th>Hospital</th>
			<th>Attending Physician</th>
		</tr>
		{foreach $readmit as $r}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td align="left">{$r->getPatient()->fullName()}</td>
			<td>{$r->datetime_admit|date_format:"%m/%d/%Y"}</td>
			<td>{$r->readmit_type|capitalize}</td>
			{$hospital = CMS_Hospital::generate()}
			{$hospital->load($r->hospital)}
			<td>{$hospital->name}</td>
			{$physician = CMS_Physician::generate()}
			{$physician->load($r->getPatient()->physician_id)}
			<td>Dr. {$physician->last_name}</td>
			<td></td>
		</tr>	
		{/foreach}
	</table>
{/if}
