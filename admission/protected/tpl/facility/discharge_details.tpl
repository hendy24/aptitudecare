{setTitle title="Complete Discharge Disposition"}
{jQueryReady}

$("#facility").change(function(e) {
	location.href = SITE_URL + '/?page=facility&action=discharge&facility=' + $("option:selected",this).val();
});

{if !($schedule->flag_readmission)}
	$(".flag-reason").hide();
{/if}

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
			$( "#discharge-state" ).val( ui.item.value );
			return false;
		},
		select: function( event, ui ) {
			$( "#state-search" ).val( ui.item.label );
			$( "#discharge-state" ).val( ui.item.value );
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.label + "</a>" )
		.appendTo( ul );
	};

<!-- Discharge Type -->
$("#discharge-to").change(function(e) {
	if ($("option:selected", this).val() == "General Discharge") {
		$("#discharge-disposition").show();
	} else {
		$("#discharge-disposition").hide();
	}
	
	
	if ($("option:selected", this).val() == "Transfer to another AHC facility") {
		$("#transfer-facility").show();
		$("#transfer-datetime-box").show();
		$("#service-disposition").hide();
		$("#discharge-location-name").hide();
		$(".discharge-address-select").hide();
		$("#home-health-org").hide();
		$("#transfer-datetime").val('{$datetime|date_format:"%m/%d/%Y %I:%M %P"}');
	} else {
		$("#transfer-facility").hide();
		$("#transfer-datetime-box").hide();
	}

	if ($("option:selected", this).val() == "Against Medical Advice" || $("option:selected", this).val() == "Insurance Denial") {
		$(".discharge-address-select").show();
	} else {
		$(".discharge-address-select").hide();
	}	
	
	if ($("option:selected", this).val() == "In-Patient Hospice" || $("option:selected", this).val() == "Transfer to other facility") {
		$("#discharge-location-name").show();
		$("#service-disposition").show();
	} else {
		$("#discharge-location-name").hide();
	}
	if ($("option:selected", this).val() == "Expired") {
		$("#service-disposition").hide();
	}
}).trigger("change");


$("input[name=discharge_address_checkbox]").click(function(e) {
	if ($(this).attr("checked") == "checked") {
		$(".address-info").show();
	} else {
		$(".address-info").hide();
	}
});

<!-- Discharge Disposition -->
$("#discharge-disposition").change(function(e) {
	if ($("option:selected", this).val() == "Home") {
		$("#service-disposition").show();
		$(".discharge-address-select").show();
	} else {
		$(".discharge-address").hide();
	}
	if ($("option:selected", this).val() == "Group Home" || $("option:selected", this).val() == "Assisted Living" || $("option:selected", this).val() == "SNF") {
		$("#service-disposition").show();
		$("#discharge-location-name").show();
		$(".discharge-address-select").hide();
	} else {
		$("#discharge-location-name").hide();
	}
	if ($("option:selected", this).val() == "Hospice") {
		$("#discharge-location-name").show();
		$(".discharge-address-select").hide();
		$("#service-disposition").hide();
	} 
		
}).trigger("change");

<!-- Service Disposition -->
$("#service-disposition").change(function() {
	if ($("#service option:selected").val() == "Home Health") {
		$("#home-health-org").show();
	} else {
		$("#home-health-org").hide();
	}
}).trigger("change");

$(".phone").mask("(999)-999-9999");

$("#facility-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', test: 'test', term: req.term, facility: $("#facility").val()}, function (json) {
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
		$("#discharge_location").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

$("#home-health-search").autocomplete({
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
		$("#home_health_org").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

	
	$("#cancel-discharge").click(function() {
		var answer = confirm("Are you sure you want to cancel this discharge?  The patient's scheduled discharge will be cleared and the patient will return to a current patient status.");
		
		console.log ($('input[name="schedule"]').val());
		
		if (answer) {
			window.location.href = SITE_URL + "/?page=facility&action=cancelDischarge&schedule=" + $('input[name="schedule"]').val();
		}
		return false;
	});
	
	
	$("#datetime").datetimepicker({
		timeFormat: "hh:mm tt",
		stepMinute: 15, 
		hour: 11		
	});
	
	$("input[name=flag_readmission]").click(function(e) {
	if ($(this).attr("checked") == "checked") {
		$(".flag-reason").show();
	} else {
		$(".flag-reason").hide();
	}
});


{/jQueryReady}







<h1 class="text-center">Complete Discharge Disposition<br /><span class="text-14">for</span> <span class="text-18">{$patient->fullName()}</span></h1>
<br />
<br />
{if $schedule->discharge_site_user_modified != ""}
	{$site_user = CMS_Site_User::generate()}
	{$site_user->load($schedule->discharge_site_user_modified)}
	<br />
	<div class="text-center">This discharge was last updated on <strong>{$schedule->discharge_datetime_modified|date_format: "%B %e, %Y at %l:%M %P"}</strong> by <strong>{$site_user->first} {$site_user->last}</strong></div>
{/if}


<form method="post" action="{$SITE_URL}" id="discharge-details-form">
	<input type="hidden" name="page" value="facility" />
	<input type="hidden" name="action" value="submitDischargeRequest" />
	<input type="hidden" name="schedule" value="{$schedule->pubid}" />
	<input type="hidden" id="facility" name="facility" value="{$schedule->facility}" />
	<input type="hidden" id="state" name="state" value="{$facility->state}" />
	<input type="hidden" name="_path" value="{urlencode(currentURL())}" />
	
	<table id="discharge" cellpadding="5px" style="width: 60%">
					<tr>
						
						<td style="text-align: right;"><strong>Time of Admission</strong>:</td>
						<td>{$schedule->datetime_admit|datetime_format}</td>				
					</tr>
					<tr>
						<td style="width: 150px; text-align: right;"><strong>Time of Discharge</strong>:</td>
						<td><input type="text" size="20" name="datetime" id="datetime" value="{$schedule->datetime_discharge|date_format:"%m/%d/%Y %I:%M %P"}"  /></td>
					</tr>
					<tr>
						<td style="text-align: right;"><strong>Discharge Type:</strong></td>
						<td>
							<select name="discharge_to" id="discharge-to">
								<option value="">Select discharge type...</option>
								{foreach $dischargeToOptions as $option}
									{if $facility->id == 1}
										{if $option != 'Co-Pay' && $option != 'Insurance Denial'}
										<option value="{$option}"{if $schedule->discharge_to == $option} selected{/if}>{$option}</option>
										{/if}
									{else}
										<option value="{$option}"{if $schedule->discharge_to == $option} selected{/if}>{$option}</option>
									{/if}
								{/foreach}
							</select>
						</td>
					</tr>
					<tr id="transfer-datetime-box" class="hidden">
						<td></td>
						<td>
							<select id="transfer-facility" name="transfer_facility">
								<option value="">Select facility...</option>
							{foreach $transferFacilities as $f}
								{if $f->id != $facility->id}
								<option value="{$f->pubid}"{if $f->id == $transfer_schedule->facility} selected{/if}>{$f->getTitle()}</option>
								{/if}
							{/foreach}
							</select>
						</td>
					</tr>

					<tr id="discharge-disposition" class="hidden">
						<td style="text-align: right;"><strong>Discharge Disposition:</strong></td>
						<td colspan="2">
							<select id="selected-discharge-disposition" name="discharge_disposition">
								<option value="">Select...&nbsp;&nbsp;</option>
								{foreach $dischargeDispositionOptions as $option}
								<option value="{$option}"{if $schedule->discharge_disposition == $option} selected{/if}>{$option}</option>
								{/foreach}
							</select>
						</td>
					</tr>
					{$dl = CMS_Hospital::generate()}
					{$dl->load($schedule->discharge_location_id)}
					{if ($schedule->discharge_location_id != '')}
						{jQueryReady}
							$("#discharge-location-name").show();
						{/jQueryReady}
					{/if}

					<tr id="discharge-location-name" {if ($schedule->discharge_location_id == '')} class="hidden"{/if}>
						<td style="text-align: right;"><strong>Discharge Facility Name:</strong></td>
						<td colspan="2">
							<input type="text" id="facility-search" style="width: 300px;" size="30" value="{$dl->name}" /><a href="{$SITE_URL}/?page=hospital&action=add&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/add.png" class="new-admit-add-item" /></a>
							<input type="hidden" name="discharge_location_id" id="discharge_location" value="{$dl->id}" />
						</td>
					</tr>
					<tr id ="service-disposition" class="hidden">
						<td style="text-align: right;"><strong>Service Disposition:</strong></td>
						<td colspan="2">
							<select id="service" name="service_disposition">
								<option value="">Select...&nbsp;&nbsp;</option>
								{foreach $serviceDisposition as $sd}
									<option value="{$sd}"{if $schedule->service_disposition == $sd} selected{/if}>{$sd}</option>
								{/foreach}
							</select>
						</td>
					</tr>
					
					<tr id="home-health-org" class="hidden">
						<td style="text-align: right;"><strong>Home Health:</strong></td>
						<td colspan="2">
							{if $schedule->home_health_id != ''}
								{$home_health = CMS_Hospital::generate()}
								{$home_health->load($schedule->home_health_id)}
							{/if}
							<input type="text" id="home-health-search" style="width: 300px;" size="30" value="{$home_health->name}" /><a href="{$SITE_URL}/?page=hospital&action=add&type=Home%20Health&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/add.png" class="new-admit-add-item" /></a>
							<input type="hidden" name="home_health_org" id="home_health_org" value="{$home_health->id}" />
						</td>
					</tr>
	
					<div id="discharge-address">
						<tr class="discharge-address-select">
							<td>&nbsp;</td>
							<td  colspan="2" id="discharge-address-checkbox"><input type="checkbox" name="discharge_address_checkbox" value="1" /> Patient will be discharged to a different address.</td>
						</tr>
						<tr class="address-info"> 
							<td></td>
							<td colspan="2">Street Address:<br /> 
								<input type="text" name="discharge_address" style="width: 232px;" value="{$data.address}" /> 
							</td> 
						</tr>
						<tr class="address-info">
							<td></td>							
							<td colspan="2">City:<br /> 
								<input type="text" name="discharge_city" style="width: 232px;" value="{$data.city}" /> 
							</td> 
						</tr>
						<tr class="address-info">	
							<td></td>
							<td>State<br />
							<input type="text" id="state-search" value="{$data.state}" />
							<input type="hidden" name="discharge_state" id="discharge-state" value="{$data.state}" />
							</td> 
							<td> 
								Zip<br /> 
								<input type="text" name="discharge_zip" style="width: 50px" value="{$data.zip}" /> 
							</td> 
						</tr> 
						<tr class="address-info">
							<td></td>
							<td>Phone Number<br />
								<input type="text" name="discharge_phone" class="phone" size="15" value="" />
							</td>
						</tr>
						<tr class="address-info">
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</div>
					<tr>
						<td style="vertical-align: top; text-align: right;"><strong>Comment:</strong></td>
						<td  colspan="2"><textarea name="discharge_comment" cols="60" rows="5">{$schedule->discharge_comment}</textarea></td>
					</tr>
					<tr>
						<td class="text-right" id="flag"><input type="radio" value="1" {if $schedule->flag_readmission == 1} checked{/if} name="flag_readmission" /></td>
						<td>Flag this patient for re-admission <a class="tooltip"><img src="{$SITE_URL}/images/icons/information.png" /><span>When selected this patient will be flagged<br /> for review prior to re-admission.</span></a></td>
					</tr>
					
					{foreach $userRoles as $role}
					{if $role->name == "facility_administrator"}
					<tr class="deny-admit">
						<td class="text-right" id="flag"><input id="deny" type="radio" name="flag_readmission" value="2" {if $schedule->flag_readmission == 2} checked{/if}></td>
						<td>Deny re-admission for this patient<a class="tooltip"><img src="{$SITE_URL}/images/icons/information.png" /><span>If this flag is set this patient will not be able to be re-admitted.</span></a></td>
					</tr>
					{/if}
					{/foreach}
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><a href="{$SITE_URL}/?page=facility&amp;action=cancelDischarge&amp;schedule={$schedule->pubid}" id="cancel-discharge" class="button">Cancel Discharge</a></td>
						<td align="right"><input type="submit" value="Submit" id="submit-discharge-request" /></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><a href="{$SITE_URL}/?page=facility&amp;action=manage_discharges" style="margin-right: 8px;">Cancel</a></td>
					</tr>
				</table>
				
