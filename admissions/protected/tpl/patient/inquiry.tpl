{setTitle title="Inquiry Record"}


{javascript}

function setConfirmUnload(on) {
    
     window.onbeforeunload = (on) ? unloadMessage : null;

}

function unloadMessage() {
    
     return 'You have entered new data on this page.  If you navigate away from this page without first saving your data, the changes will be lost.';

}

{/javascript}
{jQueryReady}

$("#home-health-field").hide();

if ($("#homehealth-search").val() != "") {
	$("#home-health-field").show();
}

$("#scheduled-home-health").click(function() {
	if ($("#scheduled-home-health").attr('checked')) {
		$("#home-health-field").show();
	} else {
		$("#home-health-field").hide();
	}
});


$(".phone-format").text(function(i, text) {
    return text.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");
});

{$states = getUSAStates()}
var states = [
{foreach $states as $abbr => $state}
{if $state != ''}
	{
		value: "{$abbr}",
		label: "({$abbr}) {$state}"
	}
	{if $state@last != true},{/if}
{/if}
{/foreach}
];

$("#state-search").autocomplete(
	{
		minLength: 0,
		source: states,
		focus: function( event, ui ) {
			$( "#state-search" ).val( ui.item.label );
			return false;
		},
		select: function( event, ui ) {
			$( "#state-search" ).val( ui.item.label );
			$( "#state" ).val( ui.item.value );
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.label + "</a>" )
		.appendTo( ul );
	};


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
}).blur(function() { $(this).trigger("change"); }).trigger("change");

{if $auth->getRecord()->canEditInquiry($schedule->getFacility()) == false || $mode != "edit"}

$("#inquiry-form input, #inquiry-form select, #inquiry-form textarea").attr("disabled", true).css("color", "#000").css("border", "none").css("background", "none");
$("#inquiry-form select").attr("disabled", true).css("color", "#000");

{else}

// enforce formats on certain fields
$(".date").mask("99/99/9999");
$(".phone").mask("(999) 999-9999");
$("#age").mask("9?99");
$("#ssn").mask("999-99-9999");
//$("#medicare_number").mask("999999999a");



{/if}

$("#icd9-code-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'coord', action: 'searchCodes', term: req.term}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.short_desc + " [" + val.code + "]";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#icd9").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

$("#admit-from-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.name + " (" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#admit-from").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

$("#hospital-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.name + " (" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#hospital").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

$("#case-manager-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'caseManager', action: 'searchCaseManagers', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.pubid = val.pubid;
				obj.label = val.last_name + ", " + val.first_name;
				obj.phone = val.phone;
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#case-manager").val(ui.item.value);
		$("#cm-phone").html(ui.item.phone + " &nbsp;&nbsp;<a href=" + SITE_URL + "/?page=caseManager&action=edit&case_manager=" + ui.item.pubid + "&isMicro=1 rel=shadowbox>Edit</a>");
		e.target.value = ui.item.label;		
	}
});

$("#homehealth-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHomeHealth', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.name + " (" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#home-health").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});


$("#physician-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'physician', action: 'searchPhysicians', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.last_name + ", " + val.first_name + " M.D. " + "(" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#physician").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

$("#ortho-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'physician', action: 'searchPhysicians', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.last_name + ", " + val.first_name + " M.D. " + "(" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#ortho").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

$("#doctor-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'physician', action: 'searchPhysicians', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.last_name + ", " + val.first_name + " M.D. " + "(" + val.state + ")";
				obj.phone = val.phone;
				obj.fax = val.fax;
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#doctor").val(ui.item.value);
		$("#doc-phone").val(ui.item.phone);
		$("#doc-fax").val(ui.item.fax);
		e.target.value = ui.item.label;	
	}
	
});


$("#pharmacy-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'pharmacy', action: 'searchPharmacies', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.name + " (" + val.address + ' ' + val.city + ', ' + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#pharmacy").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});


$("#submit-button").click(function(e) {
	e.preventDefault();
	setConfirmUnload(false);
	$("#inquiry-form").submit();
});

$("#return-to-dashboard").click(function(e) {
	e.preventDefault();
	setConfirmUnload(false);
	$("#inquiry-form").submit();
});


{/jQueryReady}
{if $patient != ''}
	{$data = get_object_vars($patient->getRecord())}
{/if}

{if $auth->getRecord()->isAdmissionsCoordinator() == 1}
<a href="{$SITE_URL}/?page=coord" id="return-to-dashboard" class="back-to-dashboard button" style="margin-top: 10px;">Back to Dashboard</a>
{/if}
<a href="{$SITE_URL}/?page=patient&action=printInquiry{if $schedule == ''}&id={$patient->pubid}{else}&schedule={$schedule->pubid}{/if}&mode=edit" target="_blank" class="right"><img src="{$SITE_URL}/images/print.png" /></a>
<br />
<br />
<h1 class="text-center">Pre-Admission Inquiry Record</h1>

<h2 class="text-center"><span class="text-14">for</span> {if $data.last_name != ''}{$data.last_name}{/if}{if $data.first_name != ''}, {$data.first_name}{/if}{if $data.middle_name != ''} {$data.middle_name}{/if}</h2>
<br />
{$userModified = $patient->siteUserModified()}
{$userCreated = $patient->siteUserCreated()}
<div id="created-info">
	This record was created <strong>{$patient->datetime_created|datetime_format}</strong> {if $userCreated != false} by {$userCreated->getFullName()} {/if}and last modified <strong>{$patient->datetime_modified|datetime_format}</strong>{if $userModified != false} by {$userModified->getFullName()}{/if}.
</div>

<form name="admissions" method="post" action="{$SITE_URL}" id="inquiry-form"> 
	<input type="hidden" name="page" value="patient" />
	<input type="hidden" name="action" value="submitInquiry" />
	<input type="hidden" name="id" value="{$patient->pubid}" />
	<input type="hidden" name="schedule" value="{$schedule->pubid}" />
	<input type="hidden" id="facility" name="facility" value="{$facility->pubid}" />
	<input type="hidden" id="facility-state" name="facility_state" value="{$facility->state}" />
	<input type="hidden" name="_path" value="{urlencode(currentURL())}" />
	<input type="hidden" name="state" value="{$facility->state}" />
	<input type="hidden" name="weekSeed" value="{$weekSeed}" />
	<table cellpadding="0" border="0" id="inquiry-form"> 
	
	<tr>
		<th colspan="3">Transporation &amp; Hospital Contact Info</th>
	</tr>
	<tr class="form-header-row">
		<td width="33%">Transportation</td>
		<td width="33%">Transportation Provider</td>
		<td width="33%">Pick-Up Time</td>
	</tr>
	<tr>
		<td valign="top">
			<input type="radio" name="trans" value="wheelchair"{if $data.trans == 'wheelchair'} checked{/if} />Wheelchair<br /> 
			<input type="radio" name="trans" value="stretcher"{if $data.trans == 'stretcher'} checked{/if} />Stretcher<br /> 
			<input type="checkbox" name="o2" value="1"{if $data.o2 == 1} checked{/if} />Oxygen <input type="text" name="o2_liters" style="width: 25px" value="{$data.o2_liters}" /> liters
			
		</td> 
		<td valign="top">
			<input type="text" name="trans_provider" value="{$data.trans_provider}" size="25" />
		</td>
		<td valign="top"><input type="text" size="20" name="datetime_pickup" value="{$data.datetime_pickup}" /></td>
	</tr>
	<tr class="form-header-row">
		<td>Patient Location/Room Number</td>
		<td>Number to call for nursing report</td>
		<td>Name of Nurse</td>
	</tr>
	<tr>
		<td>
			{if $schedule != ''}
				{$room = $schedule->getRoom()}
				{if $room->id != ''}
					{$schedule->getFacility()->name}:  Room #{$room->number}	
				{/if}
			{else}
				No Room Assigned
			{/if}
		</td>
		<td><input type="text" size="15" name="nursing_report_phone" class="phone" value="{$data.nursing_report_phone}" />
		<td><input type="text" name="referral_nurse_name" value="{$data.referral_nurse_name}" size="25" /></td>
	</tr>
	
	
	<tr class="form-header-row">
		<td><strong>Referred by type:</strong></td>
		<td><strong>Referred by:</strong></td>
		<td><strong>Referred by Phone:</strong></td>
	</tr>
	<tr>
		{if $data.referred_by_type == "Organization"}
			{$refby = CMS_Hospital::generate()}
			{$refby->load($data.referred_by_id)}
		{elseif $data.referred_by_type == "Doctor"}
			{$refby = CMS_Physician::generate()}
			{$refby->load($data.referred_by_id)}
		{elseif $data.referred_by_type == "Case Manager"}
			{$refby = CMS_Case_Manager::generate()}
			{$refby->load($data.referred_by_id)}
		{/if}
		<td>{$data.referred_by_type}</td>
		<td>{if $data.referred_by_type == "Organization"}{$refby->name}{elseif $data.referred_by_type == "Other"}{$data.referred_by_name}{else}{$refby->last_name}, {$refby->first_name}{/if}</td>
		<td>{if $data.referred_by_type == "Other"}{$data.referred_by_phone}{else}{$refby->phone}{/if}</td>
	</tr>
	
	
	{jQueryReady}
	$("#referral-org-name").change(function(e) {
		if ($("#referral-org-name option:selected").val() == '__OTHER__') {
			jPrompt('Enter the name of the referring organization', '', 'User Input', function(r) {
				if (r == null || r == '') {
					$("#referral-org-name-other").val('').hide();
					$("#referral-org-name :selected").attr("selected", false);
					$("#referral-org-name :first-child").attr("selected", true);
				} else {
					$("#referral-org-name-other").attr("disabled", false).val(r).show();
				}
			}); 
		} else {
			$("#referral-org-name-other").val('').attr("disabled", true).hide();
		}
	});	
	{/jQueryReady}
	<tr class="form-header-row">
		<td>Admit From:</td>
		<td>Case Manager</td>
		<td>Case Manager Phone</td>
	</tr>
	<tr>
		<td>
			{if $data.admit_from != ''}
				{$hospital = CMS_Hospital::generate()}
				{$hospital->load($data.admit_from)}
			{/if}
			<input type="text" id="admit-from-search" style="width: 232px;" size="30" value="{if $data.admit_from != ''}{$hospital->name}{else}{$data.referral_org_name}{/if}" /><a href="{$SITE_URL}/?page=hospital&action=add&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/add.png" class="new-admit-add-item" /></a>
			<input type="hidden" name="admit_from" id="admit-from" />
			<!-- <select class="referral-org" id="referral-org-name" name="referral_org_name">
			{$referralOrgs = CMS_Patient_Admit::referralOrgs()}
				<option value="">Select...</option>
				<option value="__OTHER__">Other organization (not listed here)</option>
				<option value=""></option>
			{foreach $referralOrgs as $org}
				<option value="{$org->referral_org_name}"{if $data.referral_org_name == $org->referral_org_name} selected{/if}>{$org->referral_org_name}</option>
			{/foreach}
			</select>
			<input type="text" disabled id="referral-org-name-other" name="referral_org_name_OTHER" value="{$data.referral_org_name_OTHER}"{if $data.referral_org_name_OTHER == ''} style="display: none;"{/if} />
 -->		</td>
		<td>
			{if $data.case_manager_id != ''}
				{$caseManager = CMS_Case_Manager::generate()}
				{$caseManager->load($data.case_manager_id)}
			{/if}
			<input type="hidden" id="case-manager" name="case_manager_id" value="{$caseManager->id}" />
			<input type="text" id="case-manager-search" value="{if $data.case_manager_id != ""}{$caseManager->last_name}, {$caseManager->first_name}{else}""{/if}" />{if $data.case_manager_id != ""}<a href="{$SITE_URL}/?page=caseManager&action=edit&case_manager={$caseManager->pubid}&isMicro=1" rel="shadowbox;width=425;height=425"><img src="{$SITE_URL}/images/edit.png" class="edit-item"></a>{/if}<a rel="shadowbox;width=425;height=425" href="{$SITE_URL}/?page=caseManager&action=add&isMicro=1"><img src="{$SITE_URL}/images/add.png" class="add-item" /></a>
		</td>
		<td><p id="cm-phone">{if $data.case_manager_id != "" && $caseManager->phone != ""}{$caseManager->phone}{/if}</p></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	
	
	
	
	
	<!-- Patient Information -->
	<tr> 
		<th colspan="3">Patient Information</th> 
	</tr>
	<tr class="form-header-row">
		<td>
			First: <br />
			<input type="text" name="first_name" id="first_name" value="{$data.first_name}" style="width: 232px;" />
		</td>
		<td>
			Middle: <br />
			<input type="text" name="middle_name" value="{$data.middle_name}" style="width: 232px;" /> 
		</td>
		<td>
			Last:<br />
			<input type="text" name="last_name" value="{$data.last_name}" style="width: 232px;" />
		</td> 
	</tr>
	<tr class="form-header-row"> 
		<td colspan="3">Street Address:<br /> 
			<input type="text" name="address" style="width: 232px;" value="{$data.address}" /> 
		</td> 
	</tr>
	<tr class="form-header-row">							
		<td>City:<br /> 
			<input type="text" name="city" style="width: 232px;" value="{$data.city}" /> 
		</td> 
	
		<td>State<br />
		<input type="text" id="state-search" value="{$data.state}" />
		<input type="hidden" name="state" id="state" value="{$data.state}" />
		</td> 
		<td> 
			Zip<br /> 
			<input type="text" name="zip" style="width: 50px" value="{$data.zip}" /> 
		</td> 
		
	</tr> 
	
	<tr class="form-header-row"> 
		<td valign="top">Phone Number:<br /> 
			<input type="text" class="phone" name="phone" id="phone" style="width: 100px" value="{$data.phone}" />
			<select name="phone_type">
				<option value=''></option>
				<option value="HOME"{if $data.phone_type == "HOME"} selected{/if}>HOME</option>
				<option value="CELL"{if $data.phone_type == "CELL"} selected{/if}>CELL</option>
				<option value="WORK"{if $data.phone_type == "WORK"} selected{/if}>WORK</option>
			</select>
		</td> 
		<td valign="top">Phone Number (secondary):<br /> 
			<input type="text" class="phone" name="phone_alt" id="phone_alt" style="width: 100px" value="{$data.phone_alt}" /> 
			<select name="phone_alt_type">
				<option value=''></option>
				<option value="HOME"{if $data.phone_alt_type == "HOME"} selected{/if}>HOME</option>
				<option value="CELL"{if $data.phone_alt_type == "CELL"} selected{/if}>CELL</option>
				<option value="WORK"{if $data.phone_alt_type == "WORK"} selected{/if}>WORK</option>
			</select>
		</td> 
		
		<td valign="top" width="30" >Date of Birth:<br /> 
			<input type="text" name="birthday" id="birthday" class="date" style="width: 80px" value="{$data.birthday|strtotime|date_format:"%m/%d/%Y"|default:""}" /> 
		</td> 
		
	</tr>
	
	<tr class="form-header-row">
		
		<td valign="top">Age:<br /> 
			<span id="age" class="normal-font"></span> 
		</td> 

		<td colspan="1">Sex:<br /> 
			<span class="normal-font">
			<input type="radio" name="sex" value="Male"{if $data.sex == "Male"} checked{/if} />Male<br /> 
			<input type="radio" name="sex" value="Female"{if $data.sex == "Female"} checked{/if} />Female
			</span>
		</td> 
		<td> 
			Social Security Number:<br /> 
			<input type="text" name="ssn" id="ssn" value="{$data.ssn}" /> 
		</td> 
	</tr> 
	
	<tr class="form-header-row"> 
		<td valign="top"> Ethnicity:<br /> 
			<select name="ethnicity" style="width: 125px"> 
				<option value=""></option>
				{foreach $availOptions.ethnicities as $e}
				<option value="{$e}"{if $data.ethnicity == $e} selected{/if}>{$e}</option>
				{/foreach}
			</select> 
		</td> 
		
		<td valign="top">Marital Status:<br /> 
			<select name="marital_status" style="width: 100px"> 
				<option value=""></option> 
				{foreach $availOptions.maritalStatus as $s}
				<option value="{$s}"{if $data.marital_status == $s} selected{/if}>{$s}</option>
				{/foreach}
			</select> 
		</td> 
		
		<td valign="top">Religion:<br /> 
			<input name="religion" type="text" value="{$data.religion}" style="width: 232px;" /><br /> 
		</td> 
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>	
	
	
	
	
	
	
	
	<!-- Hospital Info -->
	<tr>
		<th colspan="3">Hospital, Physician and Insurance Info</th>
	</tr>
	<tr class="form-header-row"> 
		<td valign="top">Hospital<br /> 
			{if $data.hospital_id != ''}
				{$h = CMS_Hospital::generate()}
				{$h->load($data.hospital_id)}
			{/if}
			<input type="text" id="hospital-search" style="width: 232px;" size="30" value="{$h->name}" /><a href="{$SITE_URL}/?page=hospital&action=add&type=Hospital&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/add.png" class="new-admit-add-item" /></a>
			<input type="hidden" name="hospital_id" id="hospital" />
		</td> 

		{if $data.physician_id != ''}
			{$physician = CMS_Physician::generate()}
			{$physician->load($data.physician_id)}
		{/if}
		<td>Attending Physician:<br /> 
			{if $user == true}
			<input type="text" id="physician-search" value="{if $data.physician_id != ''}{$physician->last_name}, {$physician->first_name} M.D.{elseif $data.physician_name != ''}{$data.physician_name}{/if}" size="28" valign="top" />{if $data.physician_id != ""}<a href="{$SITE_URL}/?page=physician&action=edit&physician={$physician->pubid}&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/edit.png" class="edit-item" /></a>{/if}<a href="{$SITE_URL}/?page=physician&action=add&type=physician&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/add.png" class="add-item" /></a>
			<input type="hidden" name="physician" id="physician" />
			{else}
			{if $data.physician_id != ''}{$physician->last_name}, {$physician->first_name} M.D.{elseif $data.physician_name != ''}{$data.physician_name}{/if}
			{/if}
		</td> 

		{if $data.ortho_id != ''}
			{$ortho = CMS_Physician::generate()}
			{$ortho->load($data.ortho_id)}
		{/if}
		<td>Orthopedic Surgeon:<br /> 
				<input type="text" id="ortho-search" size="28" value="{if $data.ortho_id != ''}{$ortho->last_name}, {$ortho->first_name} M.D.{elseif $data.surgeon_name != ''}{$data.surgeon_name}{/if}" />{if $data.ortho_id != ""}<a href="{$SITE_URL}/?page=physician&action=edit&physician={$ortho->pubid}&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/edit.png" class="edit-item" /></a>{/if}<a href="{$SITE_URL}/?page=physician&action=add&type=surgeon&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/add.png" class="add-item" /></a>
				<input type="hidden" name="ortho" id="ortho" />
 		</td> 
	</tr> 
	
	<tr class="form-header-row"> 
		{if $data.doctor_id != ''}
			{$doctor = CMS_Physician::generate()}
			{$doctor->load($data.doctor_id)}
		{/if}
		<td>Primary Doctor:<br /> 
			<input valign="top" type="text" id="doctor-search" value="{if $data.doctor_id != ''}{$doctor->last_name}, {$doctor->first_name} M.D.{elseif $data.doctor_name != ''}{$data.doctor_name}{/if}" size="28" />{if $data.doctor_id != ""}<a href="{$SITE_URL}/?page=physician&action=edit&physician={$doctor->pubid}&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/edit.png" class="edit-item" /></a>{/if}<a href="{$SITE_URL}/?page=physician&action=add&type=doctor&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/add.png" class="add-item" /></a>
			<input type="hidden" name="doctor" id="doctor"
		</td> 
		<td>Primary Doctor Phone:<br />
			<span class="normal-font">
			{if $doctor}{$doctor->phone}{/if}
			</span>
		</td>
		<td>Primary Doctor Fax:<br />
			<span class="normal-font">
			{if $doctor}{$doctor->fax}{/if}
			</span>
		</td>
	</tr>
	<tr class="form-header-row">
		<td>Hospital Room number:<br /> 
			<input valign="top" type="text" name="hospital_room" style="width:40px" value="{$data.hospital_room}" /> 
		</td> 
		<td colspan="2">Pharmacy:<br />
			{if $data.pharmacy_id != ''}
				{$pharmacy = CMS_Pharmacy::generate()}
				{$pharmacy->load($data.pharmacy_id)}
			{/if}
			<input type="text" id="pharmacy-search" value="{if $data.pharmacy_id != ''}{$pharmacy->name}{/if}" style="width: 232px;" valign="top" />{if $data.pharmacy_id != ""}<a href="{$SITE_URL}/?page=pharmacy&action=edit&pharmacy={$pharmacy->pubid}&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/edit.png" class="edit-item" /></a>{/if}<a href="{$SITE_URL}/?page=pharmacy&action=add&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/add.png" class="add-item" /></a>
			<input type="hidden" name="pharmacy" id="pharmacy" />
		</td>
		
	</tr> 
	
	<tr class="form-header-row"> 
		<td valign="top">Hospital Stay Dates:<br /> 
			<span class="normal-font">
			From:<br /><input type="text" name="hospital_date_start" class="date-picker date" value="{$data.hospital_date_start|date_format:"%m/%d/%Y"}" style="width: 232px;" /><br /> 
			To:<br /><input type="text" name="hospital_date_end" class="date-picker date" style="width: 232px;" value="{$data.hospital_date_end|date_format:"%m/%d/%Y"}" /> 
			</span>
		</td> 
		
		<td colspan="1">Billing Info:<br /> 
			<span class="normal-font">
			<input type="radio" name="paymethod" class="paymethod" id="paymethod-medicare" value="Medicare"{if $data.paymethod == 'Medicare'} checked{/if} />Medicare<br /> 
			<input type="radio" name="paymethod" class="paymethod" value="HMO"{if $data.paymethod == 'HMO'} checked{/if} />HMO<br /> 
			<input type="radio" name="paymethod" class="paymethod" value="Rugs"{if $data.paymethod == 'Rugs'} checked{/if} />Rugs<br /> 
			<input type="radio" name="paymethod" class="paymethod" value="Private"{if $data.paymethod == 'Private'} checked{/if} />Private Pay<br /> 
			</span>
		</td> 
		
		<td valign="top" colspan="2">3 Night Hospital Stay?<br /> 
			<span class="normal-font">
			<input type="radio" name="three_night" value="1"{if $data.three_night == 1} checked{/if} />Yes<br /> 
			<input type="radio" name="three_night" value="0"{if $data.three_night === 0} checked{/if} />No<br />	
			</span>
		</td> 
	</tr> 
	<tr class="form-header-row">
<!--
 		<td>ICD-9 Code:<br />
			{if $data.icd9_id != ''}
				{$codes = CMS_Icd9_Codes::generate()}
				{$codes->load($data.icd9_id)}
			{/if}
	 		<input type="text" style="width: 232px;" id="icd9-code-search" value="{$codes->desc}"} />
	 		<input type="hidden" name="icd9_code" id="icd9_code" />
 		</td>
-->
		<td>Chest X-Rays<br />
		<span class="normal-font">
		<input type="radio" name="x_rays_received" value="0"{if $data.x_rays_received == 0} checked{/if} /> Not Received
		<br />
		<input type="radio" name="x_rays_received" value="1"{if $data.x_rays_received == 1} checked{/if} /> Received
		</span>
		</td>
		<td>Toured<br />
		<span class="normal-font">
		<input type="radio" name="toured" value="0"{if $_history.toured == 0} checked{/if} /> No
		<br />
		<input type="radio" name="toured" value="1"{if $data.toured == 1} checked{/if} /> Yes
		</span>
		</td>
		<td>Medicare ID:<br /> 
			<input type="text" name="medicare_number" id="medicare_number" class="medicare-field" style="width: 232px;" value="{$data.medicare_number}" /> 
		</td> 
		<!--<td colspan="1">Medicare Days Used:<br /> 
			<input type="text" name="medicare_days_used" class="medicare-field" style="width: 30px" value="{$data.medicare_days_used}" /> 
		</td> 
		
		<td valign="top" colspan="2">Medicare Days Available:<br /> 
			<input type="text" name="medicare_days_available" class="medicare-field" style="width: 30px" value="{$data.medicare_days_available}" /> 
		</td>  removed by kwh 2012-06-21 -->						
	</tr> 
	<tr  class="form-header-row">
		<td colspan="1">Supplemental Ins.:<br /> 
			<input type="text" name="supplemental_insurance_name" style="width: 232px;" value="{$data.supplemental_insurance_name}" /> 
		</td> 
		<td colspan="1">Supplemental Ins. ID:<br /> 
			<input type="text" name="supplemental_insurance_number" style="width: 150px" value="{$data.supplemental_insurance_number}" /> 
		</td> 
		<td></td>
	</tr>
	<tr class="form-header-row"> 
		<td>HMO / Insurance:<br /> 
			<input type="text" name="hmo_name" style="width: 232px;" value="{$data.hmo_name}" /> 
		</td> 
		
		<td>Authorization #:<br /> 
			<input type="text" name="hmo_auth_number" style="width: 232px;" value="{$data.hmo_auth_number}" /> 
		</td> 
		
		<td>HMO ID#:<br /> 
			<input type="text" name="hmo_number" style="width: 232px;" value="{$data.hmo_number}" /> 
		</td> 
	</tr> 
	<tr class="form-header-row">
		<td>Patient Type:<br /><br />
			<input type="radio" name="patient_type" value="0" style="font-weight: normal" {if $schedule->long_term == 0} checked{elseif $schedule->long_term == ''} checked{/if} /><span class="normal-font">Short Term</span> &nbsp;&nbsp;
			<input type="radio" name="patient_type" value="1"  {if $schedule->long_term == 1} checked{/if} /><span class="normal-font">Long Term</span>
		</td>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	
	
	
	
	
	<!-- Emergency Contact Info -->
	<tr>
		<th colspan="3">Emergency Contact &amp; Private Guarantor Info</th>
	</tr>
	<tr class="form-header-row"> 
		<td colspan="3"><strong>Emergency Contact #1:</strong></td>
	</tr>
	<tr class="form-header-row">
		<td>
			Name:<br /> 
			<input type="text" name="emergency_contact_name1" style="width: 232px;" value="{$data.emergency_contact_name1}" /> 
		</td> 
		
		<td>Relationship:<br /> 
			<input type="text" name="emergency_contact_relationship1" style="width: 232px;" value="{$data.emergency_contact_relationship1}" /> 
		</td> 
		<td>Address:<br /> 
			<input type="text" name="emergency_contact_address1" style="width: 232px;" value="{$data.emergency_contact_address1}" /> 
		</td> 
	</tr>
	<tr>
		<td>Phone (primary):<br /> 
			<input type="text" class="phone" name="emergency_contact_phone1" id="emergency_contact_phone1" style="width: 150px" value="{$data.emergency_contact_phone1}" /> 
		</td> 
		<td>Phone (secondary):<br /> 
			<input type="text" class="phone" name="emergency_contact_phone_alt1" id="emergency_contact_phone_alt1" style="width: 150px" value="{$data.emergency_contact_phone_alt1}" /> 
		</td> 
		<td></td>
		<td></td>
	</tr> 
	<tr class="form-header-row"> 
		<td colspan="3"><strong>Emergency Contact #2:</strong></td>
	</tr>
	<tr class="form-header-row">
		<td>
			Name:<br /> 
			<input type="text" name="emergency_contact_name2" style="width: 232px;" value="{$data.emergency_contact_name2}" /> 
		</td> 
		
		<td>Relationship:<br /> 
			<input type="text" name="emergency_contact_relationship2" style="width: 232px;" value="{$data.emergency_contact_relationship2}" /> 
		</td> 
		<td>Address:<br /> 
			<input type="text" name="emergency_contact_address2" style="width: 232px;" value="{$data.emergency_contact_address2}" /> 
		</td> 
	</tr>
	<tr class="form-header-row">
		<td>Phone (primary):<br /> 
			<input type="text" class="phone" name="emergency_contact_phone2" id="emergency_contact_phone2" style="width: 150px" value="{$data.emergency_contact_phone2}" /> 
		</td> 
		<td>Phone (secondary):<br /> 
			<input type="text" class="phone" name="emergency_contact_phone_alt2" id="emergency_contact_phone_alt2" style="width: 150px" value="{$data.emergency_contact_phone_alt2}" /> 
		</td> 
		<td></td>
		<td></td>
	</tr> 
	<tr class="form-header-row"> 
		<td colspan="3"><strong>Private Pay Guarantor:</strong></td>
	</tr>
	<tr class="form-header-row">
		<td>Name: <br />
			<input type="text" name="private_pay_guarantor_name" style="width: 232px;" value="{$data.private_pay_guarantor_name}" /> 
		</td> 
		
		<td>Relationship<br /> 
			<input type="text" name="private_pay_guarantor_relationship" style="width: 232px;" value="{$data.private_pay_guarantor_relationship}" /> 
		</td> 

		<td>Address<br /> 
			<input type="text" name="private_pay_guarantor_address" style="width: 232px;" value="{$data.private_pay_guarantor_address}" /> 
		</td> 
	</tr>
	<tr class="form-header-row">
		<td>Phone<br /> 
			<input type="text" class="phone" name="private_pay_guarantor_phone" style="width: 232px;" value="{$data.private_pay_guarantor_phone}" /> 
		</td> 
		<td></td>
		<td></td>
	</tr> 
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	
	
	
	
	
	<tr>
		<th colspan="3">Admission Info &amp; Discharge Plan</th>
	</tr>
	
	<tr class="form-header-row"> 
		<td colspan="3">Admission Diagnosis:<br /> 
			<textarea name="other_diagnosis" rows="5" cols="80">{$data.other_diagnosis}</textarea><br /> 
		</td> 
	</tr>
	<tr>
		<td><input type="checkbox" id="elective" name="elective" value="1"{if $schedule->elective == 1} checked{/if} /> Patient is an elective surgery</td>
	</tr>
	<tr>
		<tr class="form-header-row">
		<td colspan="3">Discharge Plan:<br /> 
			<textarea name="discharge_plan" rows="5" cols="80">{$data.discharge_plan}</textarea><br /> 
		</td> 
	</tr> 
	<tr>
		<td style="padding-top: 20px;"><input type="checkbox" name="scheduled_home_health" id="scheduled-home-health" value="1"{if $patient->scheduled_home_health == 1} checked{/if}  />Pre-scheduled Home Health</td>
	</tr>
	<tr>
	{if $patient->scheduled_home_health}
		{$homeHealth = CMS_Hospital::generate()}
		{$homeHealth->load($schedule->home_health_id)}
	{/if}
		<td id="home-health-field" colspan="3">
			<input type="text" id="homehealth-search" style="width: 300px;" size="30" placeholder="Enter the name of the home health agency" value="{$homeHealth->name}" /><a href="{$SITE_URL}/?page=hospital&action=add&type=Home%20Health&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/add.png" class="new-admit-add-item" /></a>
			<input type="hidden" name="home_health" id="home-health" />
		</td>
	</tr>


	
	<tr class="form-header-row"> 
		<td colspan="3">Comments:<br /> 
			<textarea name="comments" rows="5" cols="80">{$data.comments}</textarea><br /> 
		</td> 
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	{if $schedule->elective}
	<tr>
		<td colspan="3" align="left">
			<input type="checkbox" name="confirmed" value="1"{if $schedule->confirmed == 1} checked{/if} /> Elective admit has been confirmed 
		</td>
	</tr>
	{/if}
	
	{jQueryReady}
		
		if ($("#discharge-summary").attr("checked")) {
			$("#discharge-summary-date-row").show();
		} else {
			$("#discharge-summary-date-row").hide();
		}
		
		$("#discharge-summary").change(function() {
			if ($("#discharge-summary").attr("checked")) {
				$("#discharge-summary-date-row").show();
			} else {
				$("#discharge-summary-date-row").hide();
			}
		});
		
		$(".schedule-datetime").datetimepicker({
			timeFormat: "hh:mm tt",
			stepMinute: 15,
			hour: 13,	
		});
	{/jQueryReady}
	<tr>
		<td><input type="checkbox" name="discharge_summary" id="discharge-summary" {if $data.datetime_dc_summary != ""} checked{/if} /> Discharge Summary received from Hospital</td>
	</tr>
	<tr id="discharge-summary-date-row">
		<td align="right"><strong>Date & Time Received:</strong> <input class="schedule-datetime" id="discharge-summary-date" name="datetime_dc_summary" value="{$data.datetime_dc_summary|date_format:"%m%d/%Y %I:%M %P"}" /></td>
	</tr>
	<tr>
		<td colspan="3" align="left">
			<input type="checkbox" name="referral" value="1"{if $data.referral == 1} checked{/if} /> Yes, referral was received from hospital 
		</td>
	</tr>
	<tr>
		<td colspan="3" align="left">
			<input type="checkbox" name="final_orders" value="1"{if $data.final_orders == 1} checked{/if} /> Yes, final orders have been received 
		</td>
	</tr>
<!-- 		<br />
		<br />
		<div style="float: right;"><input type="submit" value="Save" /></div> -->
	{if $mode == 'edit'}
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
	<tr> 
		<td colspan="3" style="text-align: right; margin-right: 5px;"> 
			<a href="{$SITE_URL}" style="margin-right: 8px;" class="button">Cancel</a> 
		</td> 
		<td colspan="2"> 
			<input type="submit" style="float: right" id="submit-button" value="Submit" /> 
		</td> 
	</tr>
	<tr> 
			</tr>  
	{/if}	
	</table> 
	
</form> 
