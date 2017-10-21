{setTitle title="Inquiry Form"}
{javascript}
{/javascript}
{jQueryReady}

$inputs = $("#inquiry-form select");
$.each($inputs, function(i, elem) {
	var val = $(elem).val();
	$(elem).before("<span>" + val + "</span>").remove();
});

// calculate age on the fly
$("#birthday").change(function() {
	 if ($(this).val() == '') {
	 	$("#age").html('');
	 	return false;
	 }
	 var now = new Date()

	 // validate
	 var parts = $(this).val().split("/");
	 var month = parseInt(parts[0], 10);
	 var day = parseInt(parts[1], 10);
	 var year = parseInt(parts[2], 10);
	 	 
	 var msg = '';
	 
	 if (! (month >=1 && month <= 12) ) {
	 	msg += 'Birthday: month must be a number between 1 and 12.\n'; 
	 }
	 if (! (day >=1 && day <= 31) ) {
	 	msg += 'Birthday: day must be a number between 1 and 31.\n'; 
	 }
	 if ( year > now.getFullYear() ) {
	 	msg += 'Birthday: Year may not be in the future.\n'; 
	 }
	 
	 if (msg != '') {
	 	jAlert(msg, 'Attention');
	 	$("#age").html('');
	 	return false;
	 }
	 
	 var born = new Date($(this).val());
	 var years = Math.floor((now.getTime() - born.getTime()) / (365 * 24 * 60 * 60 * 1000));
	 if (! isNaN(years) ) {
		$("#age").html(years);
	 } else {
	 	$("#age").html('');
	 }
}).trigger("change");


// run on load
//referralRows();

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
	float: left;
	margin: 0px 0px 0px 0px;
	color: #195688;
	font-size: 10px;
}

h2 {
	float: left;
	margin: 0;
}

#inquiry-form input, #inquiry-form select, #inquiry-form textarea {
	color: "#000";
	border: none;
	background: none;
	font-size: 13px;
}


#inquiry-form select {
	color: "#000";
}

#inquiry-form input.submit {
	display: none;	
}

.back-link {
	display: none;	
}

td {
	font-size: 13px;
}
</style>

{$userModified = $patient->siteUserModified()}
<form name="admissions" method="post" action="{$SITE_URL}" id="inquiry-form"> 
	<input type="hidden" name="page" value="patient" />
	<input type="hidden" name="action" value="submitInquiry" />
	{if $patient != ''}
		{$data = get_object_vars($patient->getRecord())}
	{/if}
	<input type="hidden" name="id" value="{$patient->pubid}" />
	<h3 style="font-size: 18px; font-weight: bold;">{if $data.last_name != ''}{$data.last_name}{/if}{if $data.first_name != ''}, {$data.first_name}{/if}{if $data.middle_name != ''} {$data.middle_name}{/if}</h3>
	<table width="100%" cellpadding="2" cellspacing="0" id="inquiry-form"> 
	<tr>
		<td colspan="3">
			<strong>Transportation:</strong> {if $data.trans == 'wheelchair'} Wheelchair{/if} {if $data.trans == 'stretcher'} Stretcher{/if}{if $data.o2 == 1}&nbsp;&nbsp;<strong>Oxygen:</strong> {$data.o2_liters} liters<br />{/if}
			<strong>Transportation Provider:</strong> {$data.trans_provider}<br />
			<strong>Pick-Up Time:</strong> {$data.datetime_admit} {if $data.datetime_pickup != ''}{$data.datetime_pickup}{/if}
		</td>
	</tr>
	<tr class="form-header-row">
		<td width="34%"><strong>Location/Room:</strong> {if $schedule != ''}{$room = $schedule->getRoom()}{if $room->id != ''}{$schedule->getFacility()->name}:  Room #{$room->number}{/if}{else}No Room Assigned{/if}</td>
		<td width="33%"><strong>Number to call for nursing report:</strong> {$data.nursing_report_phone}</td>
		{*<td width="33%"><strong>Inquirer / Referral Source:</strong> <select id="referral-type" name="referral_type_old"><option value="org">An organization</option><option value="person">Another patient</option><option value="other">Other</option></select><input type="hidden" id="referral-type" value="{$data.referral_type}" /></td>*}
		<td width="33%"><strong>Name of Nurse:</strong> {$data.referral_nurse_name}</td>
	</tr>
	
	
	{if $data.admit_from != ''}
		{$hospital = CMS_Hospital::generate()}
		{$hospital->load($data.admit_from)}
	{/if}
	
	<tr class="form-header-row referral-org-row">
		<td><strong>Admit From:</strong> {if $data.admit_from != ''}{$hospital->name}{else}{$data.referral_org_name}{/if}</td>
		<td><strong>Case Manager:</strong> 
			{if $data.case_manager_id != ''}
				{$caseManager = CMS_Case_Manager::generate()}
				{$caseManager->load($data.case_manager_id)}
				{$caseManager->last_name}, {$caseManager->first_name}
			{/if}
			</td>
		<td><strong>Case Manager Phone Number:</strong> {$caseManager->phone}</td>
	</tr>
	{*
	<tr class="form-header-row referral-person-row">
		<td colspan="3">
			<strong>Referring Patient:</strong> (Last, First, Middle)<br />
			{$data.referral_person_last_name}, {$data.referral_person_first_name} {$data.referral_person_middle_name}
		</td>
	</tr>
	<tr class="form-header-row referral-other-row">
		<td colspan="3">
			<strong>Name of Referral Source:</strong> {$data.referral_other_contact_name}
			
		</td>
	</tr>
	<tr class="form-header-row">
		<td><strong>Referrer Phone Number:</strong> {$data.referral_phone}</td>
		<td><strong>Relationship to Patient:</strong> {$data.referral_relationship}</td>
		<td></td>
	</tr>
	*}
	<tr> 
		<td colspan="2">
			<strong>Patient Name:</strong> (Last, First, Middle)<br />
			{$data.last_name},  {$data.first_name}, {$data.middle_name}
		</td>
		<td><strong>Prefers to be called by:</strong> {$data.preferred_name} 
		</td> 
	</tr> 
	
	<tr> 
		<td colspan="3"><strong>Street Address:</strong> {$data.address}
		</td> 
	</tr>
	<tr>							
		<td colspan="3">
			<strong>City, State Zip:</strong><br />
			{$data.city}, <select name="state"> 
				<option value="blank"></option> 
				{foreach getUSAStates() as $stateAbbr => $stateName}
				<option value="{$stateAbbr}"{if $data.state == $stateAbbr} selected{/if}>{$stateName}</option>
				{/foreach}
			</select> {$data.zip}
		</td> 
		
	</tr> 
	
	<tr> 
		<td valign="top"><strong>Phone Number: </strong> {$data.phone} {if $data.phone_type != ''}<span style="font-size: 8px;">({$data.phone_type})</span>{/if}
		</td> 
		<td valign="top"><strong>Phone Number: </strong>{$data.phone_alt} {if $data.phone_alt_type != ''}<span style="font-size: 8px;">({$data.phone_alt_type})</span>{/if}
		</td> 
		<input type="hidden" id="birthday" value="{$data.birthday|strtotime|date_format:"%m/%d/%Y"|default:""}" />
		<td valign="top" width="30" ><strong>Date of Birth:</strong> {$data.birthday|strtotime|date_format:"%m/%d/%Y"|default:""} 
		</td> 
		
	</tr>
	
	<tr>
		
		<td valign="top"><strong>Age:</strong> <span id="age"></span> 
		</td> 

		<td colspan="3"><strong>Sex:</strong> <input type="radio" name="sex" value="Male"{if $data.sex == "Male"} checked{/if} />Male &nbsp;&nbsp;&nbsp; <input type="radio" name="sex" value="Female"{if $data.sex == "Female"} checked{/if} />Female
		</td> 
	</tr> 
	
	<tr> 
		<td valign="top"><strong>Ethnicity:</strong>
			<select name="ethnicity"> <option value=""></option> {foreach $availOptions.ethnicities as $e} <option value="{$e}"{if $data.ethnicity == $e} selected{/if}>{$e}</option> {/foreach} </select> 
		</td> 
		
		<td valign="top"><strong>Marital Status:</strong>
			<select name="marital_status"> 
				<option value=""></option> 
				{foreach $availOptions.maritalStatus as $s}
				<option value="{$s}"{if $data.marital_status == $s} selected{/if}>{$s}</option>
				{/foreach}
			</select> 
		</td> 
		
		<td valign="top"><strong>Religion:</strong> {$data.religion}
		</td> 
	</tr>
	
	<tr> 
		<td><strong>State Born:</strong>
			<select name="state_born"> 
				<option value="blank"></option> 
				{foreach getUSAStates() as $stateAbbr => $stateName}
				<option value="{$stateAbbr}"{if $data.state_born == $stateAbbr} selected{/if}>{$stateName}</option>
				{/foreach}
			</select> 
		</td> 
		<td> 
			<span style="white-space: nowrap;"><strong>SSN:</strong> {$data.ssn}</span>
		</td> 
		<td></td>
		
	</tr> 
		
	<tr> 
		<td valign="top"><strong>Hospital:</strong> 
			{if $data.hospital_id != ''}
				{$hospital = CMS_Hospital::generate()}
				{$hospital->load($data.hospital_id)}
			{/if}
			{$hospital->name}
		</td> 
		{if $data.physician_id != ''}
			{$physician = CMS_Physician::generate()}
			{$physician->load($data.physician_id)}
		{/if}
		<td><strong>Attending Physician:</strong> {if $data.physician_id != ''}{$physician->last_name}, {$physician->first_name} M.D.{else}{$data.physician_name}{/if}
		</td> 
		{if $data.ortho_id != ''}
			{$ortho = CMS_Physician::generate()}
			{$ortho->load($data.ortho_id)}
		{/if}
		<td><strong>Orthopedic Surgeon:</strong> {if $data.ortho_id != ''}{$ortho->last_name}, {$ortho->first_name} M.D.{else}{$data.surgeon_name}{/if}
		</td> 
	</tr> 
	
	<tr> 
		{if $data.doctor_id != ''}
			{$doctor = CMS_Physician::generate()}
			{$doctor->load($data.doctor_id)}
		{/if}
		<td><strong>Primary Doctor:</strong> {if $data.doctor_id != ''}{$doctor->last_name}, {$doctor->first_name} M.D.{else}{$data.doctor_name}{/if}
		</td> 
		<td><strong>Hospital Room number:</strong> {$data.hospital_room}
		</td> 
		<td></td>
		
	</tr> 
	
	<tr> 
		<td valign="top" colspan="3"><strong>Hospital Stay Dates:</strong><br /> 
			From: {$data.hospital_date_start|date_format:"%m/%d/%Y"} &nbsp;&nbsp;&nbsp;  
			To: {$data.hospital_date_end|date_format:"%m/%d/%Y"} 
		</td>
	</tr>
	<tr>
		<td colspan="1"><strong>Billing Info: </strong><br />
			<input type="radio" name="paymethod" class="paymethod" id="paymethod-medicare" value="Medicare"{if $data.paymethod == 'Medicare'} checked{/if} />Medicare &nbsp;&nbsp;&nbsp; 
			<input type="radio" name="paymethod" class="paymethod" value="HMO"{if $data.paymethod == 'HMO'} checked{/if} />HMO &nbsp;&nbsp;&nbsp; 
			<input type="radio" name="paymethod" class="paymethod" value="Private"{if $data.paymethod == 'Private'} checked{/if} />Private Pay 
		</td> 
		
		<td valign="top" colspan="2"><strong>3 Night Hospital Stay?</strong> 
			<input type="radio" name="three_night" value="1"{if $data.three_night == 1} checked{/if} />Yes &nbsp;&nbsp;&nbsp; 
			<input type="radio" name="three_night" value="0"{if $data.three_night === 0} checked{/if} />No	
		</td> 
	</tr> 
	<tr>
		<td><strong>X-Rays Received?</strong>
		<input type="radio" name="x_rays_received" value="0"{if $data.x_rays_received == 0} checked{/if} /> Not Received &nbsp;&nbsp;&nbsp; 
		<input type="radio" name="x_rays_received" value="1"{if $data.x_rays_received == 1} checked{/if} /> Received
		</td>
		<td><strong>Toured?</strong>
		<input type="radio" name="toured" value="0"{if $_history.toured == 0} checked{/if} /> No &nbsp;&nbsp;&nbsp; 
		<input type="radio" name="toured" value="1"{if $data.toured == 1} checked{/if} /> Yes
		</td>
		<td></td>
	</tr>
	
	<tr> 
		<td><strong style="font-size: 16px;">Medicare ID:</strong> <strong style="font-size: 16px;">{$data.medicare_number}</strong>
		</td> 
<!--
		<td colspan="1"><strong>Medicare Days Used:</strong> {$data.medicare_days_used}
		</td> 
		
		<td valign="top" colspan="2"><strong>Medicare Days Available:</strong> {$data.medicare_days_available}
		</td> 						
-->
	</tr> 
		<td><strong>Supplemental Ins.:</strong> {$data.supplemental_insurance_name}
		</td> 
		<td><strong>Supplemental Ins. ID:</strong> {$data.supplemental_insurance_number}
		</td> 
		<td></td>
	</tr>
	<tr> 
		<td><strong>HMO / Insurance:</strong> {$data.hmo_name}
		</td> 
		
		<td><strong>Authorization #:</strong> {$data.hmo_auth_number}
		</td> 
		
		<td><strong>HMO ID#:</strong> {$data.hmo_number}
		</td> 
	</tr> 
	
 
	<tr> 
		<td colspan="3" bgcolor="#999999"><strong>Emergency Contact #1:</strong></td>
	</tr>
	<tr>
		<td>
			<strong>Name:</strong> {$data.emergency_contact_name1}
		</td> 
		
		<td><strong>Relationship:</strong> {$data.emergency_contact_relationship1}
		</td> 
		<td><strong>Address:</strong> {$data.emergency_contact_address1} 
		</td> 
	</tr>
	<tr>
		<td><strong>Phone (primary):</strong> {$data.emergency_contact_phone1}
		</td> 
		<td><strong>Phone (secondary):</strong> {$data.emergency_contact_phone_alt1} 
		</td> 
		<td></td>
	</tr> 
	<tr> 
		<td colspan="3" bgcolor="#999999"><strong>Emergency Contact #2:</strong></td>
	</tr>
	<tr>
		<td>
			<strong>Name:</strong> {$data.emergency_contact_name2}
		</td> 
		
		<td><strong>Relationship:</strong> {$data.emergency_contact_relationship2}
		</td> 
		<td><strong>Address:</strong> {$data.emergency_contact_address2}
		</td> 
	</tr>
	<tr>
		<td><strong>Phone (primary):</strong> {$data.emergency_contact_phone2}
		</td> 
		<td><strong>Phone (secondary):</strong> {$data.emergency_contact_phone_alt2}
		</td> 
		<td></td>
	</tr> 
	<tr> 
		<td colspan="3" bgcolor="#999999"><strong>Private Pay Guarantor:</strong></td>
	</tr>
	<tr>
		<td><strong>Name:</strong> {$data.private_pay_guarantor_name}
		</td> 
		
		<td><strong>Relationship:</strong> {$data.private_pay_guarantor_relationship}
		</td> 

		<td><strong>Address:</strong> {$data.private_pay_guarantor_address}
		</td> 
	</tr>
	<tr>
		<td><strong>Phone:</strong> {$data.private_pay_guarantor_phone}
		</td> 
		<td></td>
		<td></td>
	</tr> 
	
	
	<tr> 
		<td colspan="6"><strong>Diagnosis:</strong><br /> 
			{$data.other_diagnosis}
		</td> 
	</tr> 
	
	<tr> 
		<td colspan="6"><strong>Discharge Plan:</strong><br /> 
			{$data.discharge_plan}
		</td> 
	</tr> 
	
	<tr> 
		<td colspan="6"><strong>Comments:</strong><br /> 
			{$data.comments}</textarea>
		</td> 
	</tr> 
	</table> 
	
</form> 
