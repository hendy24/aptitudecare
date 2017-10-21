{setTitle title="Schedule Discharge"}
{jQueryReady}

$("#facility").change(function(e) {
	location.href = SITE_URL + '/?page=facility&action=discharge&facility=' + $("option:selected",this).val();
});

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
	if ($("option:selected", this).val() == "Transfer to other facility") {
		$("#discharge-location-name").show();
	} else {
		$("#discharge-location-name").hide();
	}
	if ($("option:selected", this).val() == "Against Medical Advice" || $("option:selected", this).val() == "Insurance Denial") {
		$(".discharge-address-select").show();
	} else {
		$(".discharge-address-select").hide();
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
	if ($("option:selected", this).val() == "Group Home" || $("option:selected", this).val() == "Assisted Living") {
		$("#service-disposition").show();
		$("#discharge-location-name").show();
		$(".discharge-address-select").hide();
	} else {
		$("#discharge-location-name").hide();
	}
	if ($("option:selected", this).val() == "Hospice") {
		$(".discharge-address-select").show();
		$("#service-disposition").hide();
	} 
	
}).trigger("change");

<!-- Service Disposition -->
$("#service-disposition").change(function() {
	if ($("#service option:selected").val() == "Other Home Health") {
		$("#home-health-org").show();
	} else {
		$("#home-health-org").hide();
	}
}).trigger("change");

$(".phone").mask("(999)-999-9999");

$("#facility-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term}, function (json) {
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
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term}, function (json) {
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

{/jQueryReady}
<div id="manage-discharges">
	<h1 class="text-center">{if $schedule != ''}Manage Discharge <br /><span class="text-16">for {$schedule->getPatient()->fullName()}</span> {else}Schedule a Discharge{/if}</h1>
	<br />
	
	{if $schedule != '' && $schedule->datetime_discharge != ''}
		<div id="discharge-note" class="background-red">
			<strong>NOTE:</strong> This patient is currently scheduled for discharge on {$schedule->datetime_discharge|datetime_format}.  You may change the details of this dischage below, or <a href="{$SITE_URL}/?page=facility&amp;action=cancelDischarge&amp;schedule={$schedule->pubid}" id="cancel-discharge">cancel it entirely</a>.  
			<br />
			{$atHospitalRecord = $schedule->atHospitalRecord()}
			{if $atHospitalRecord != false}
				<br />
				<strong>HOSPITAL VISIT:</strong> This patient is currently has a hospital visit being tracked on the return to hospital page.  <a href="{$SITE_URL}/?page=facility&amp;action=sendToHospital&amp;schedule={$schedule->pubid}">Click here</a> to view details.
			{/if}
		</div>
	{/if}
	
	{if $schedule->discharge_site_user_modified != ""}
		{$site_user = CMS_Site_User::generate()}
		{$site_user->load($schedule->discharge_site_user_modified)}
		<br />
		<div class="text-center">This discharge was last updated on <strong>{$schedule->discharge_datetime_modified|date_format: "%B %e, %Y at %l:%M %P"}</strong> by <strong>{$site_user->first} {$site_user->last}</strong></div>
		<br />
	{/if}
	 
			<form method="post" action="{$SITE_URL}" id="discharge-request-form">
			<input type="hidden" name="page" value="facility" />
			<input type="hidden" name="action" value="submitDischargeRequest" />
			<input type="hidden" name="_path" value="{urlencode(currentURL())}" />		
			
				<table id="discharge" cellpadding="5px">
					{if $schedule != ''}
					<tr>
						<input type="hidden" name="schedule" value="{$schedule->pubid}" />
						<td style="text-align: right;"><strong>Time of Admission</strong>:</td>
						<td>{$schedule->datetime_admit|datetime_format}</td>				
					</tr>
					<tr>
						<td style="text-align: right;"><strong>Room #</strong></td>
						<td>{$schedule->related("room")->number}</td>
					</tr>
					{/if}
					<tr>
						<td style="width: 150px; text-align: right;"><strong>Time of Discharge</strong>:</td>
						<td><input type="text" size="20" name="datetime" value="{$datetime|date_format:"%m/%d/%Y %I:%M %P"}" class="datetime-picker" /></td>
					</tr>
					<tr>
						<td style="text-align: right;"><strong>Facility:</strong></td>
						<td>
							<select id="facility" name="facility">
								<option value="">Select facility...</option>
							{foreach $facilities as $f}
								<option value="{$f->pubid}"{if $f->id == $facility->id} selected{/if}>{$f->getTitle()}</option>
							{/foreach}
							</select>
						</td>
					</tr>
					{if $facility != ''}
					<tr>
						<td style="text-align: right;"><strong>Discharge Type:</strong></td>
						<td>
							{if $schedule == '' && count($rooms) == 0}
								There are no patients scheduled who are eligible for discharge from {$facility->name} at {$datetime|datetime_format}.  
							{else}
							<select name="discharge_to" id="discharge-to">
								<option value="">Select discharge type...</option>
								{foreach $dischargeToOptions as $option}
									{if $option != "Transfer to another AHC facility"}
									<option value="{$option}"{if $schedule->discharge_to == $option} selected{/if}>{$option}</option>
									{/if}
								{/foreach}
							</select>
						</td>
					</tr>
					<tr id="transfer-datetime-box" class="hidden">
						<td></td>
						<td style="text-align: right;">
							<select id="transfer-facility" name="transfer_facility">
								<option value="">Select facility...</option>
							{foreach $transferFacilities as $f}
								{if $f->id != $facility->id}
								<option value="{$f->pubid}"{if $f->id == $transfer_schedule->facility} selected{/if}>{$f->getTitle()}</option>
								{/if}
							{/foreach}
							</select>
						</td>
						<td>To admit at: <input type="text" size="18" id="transfer-datetime" class="datetime-picker"  name="datetime_discharge_transfer" value="{$datetime|date_format:"%m/%d/%Y %I:%M %P"}" /></td>
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
							<input type="text" id="facility-search" style="width: 300px;" size="30" value="{$dl->name}" />
							<input type="hidden" name="discharge_location_id" id="discharge_location" />
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
							<input type="text" id="home-health-search" style="width: 300px;" size="30" />
							<input type="hidden" name="home_health_org" id="home_health_org" />
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
							<input type="hidden" name="discharge_state" id="state" value="{$data.state}" />
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
					</div>
					<tr>
						<td style="vertical-align: top; text-align: right;"><strong>Comment:</strong></td>
						<td  colspan="2"><textarea name="discharge_comment" cols="50" rows="5">{$schedule->discharge_comment}</textarea></td>
					</tr>
				</table>
	
				{* Layout re-formatted 2012-02-28 by kwh *}
					
				{if $schedule == ''}
				<br />
				<br />
				<br />
				<br />
				<h2 class="text-center">Select Patient to be discharged:</h2>
				<br />
				<br />

	
				<!--table to display all current patients -->
				<table id="census-report-table" cellpadding="5" cellspacing="0">
					<tr>
						<th></th>
						<th>Room</th>
						<th>Patient Name</th>
						<th>&nbsp;</th>
						<th>Admission Date</th>
						<th>Discharge Date</th>
						<th>Physician</th>
					</tr>
						{foreach $rooms as $room}
							{$occupant = CMS_Patient_Admit::generate()}
							{$occupant->load($room->patient_admit_pubid)}
							{$occupantSchedule = CMS_Schedule::generate()}
							{$occupantSchedule->load($room->schedule_pubid)}
							<tr {if $room->datetime_discharge != ''}bgcolor="#FF6A6A"{elseif $room->is_complete == 0 && $room->is_complete != null && $room->datetime_discharge < $datetime} bgcolor="#A65878" {else} bgcolor="{cycle values="#d0e2f0,#ffffff"}"{/if}>
								<td><input type="radio" name="schedule" value="{$room->schedule_pubid}" checked /></td>
								<td class="text-center">{$room->number}</td>
								<td style="text-align: left;">{$occupant->fullName()}</td>
								<td align="left">{scheduleMenu schedule=$occupantSchedule}</td>
								<td>{$room->datetime_admit|date_format: "%m/%d/%Y"}</td>
								<td>{$room->datetime_discharge|date_format: "%m/%d/%Y"}</td>
								{if $occupant->physician_id != ''}
								{$physician = CMS_Physician::generate()}
								{$physician->load($occupant->physician_id)}
								<td style="text-align: left;">Dr. {$physician->last_name}</td>
								{elseif $occupant->physician_name != ''}
								<td style="text-align: left;">{$occupant->physician_name}</td>
								{else}
								<td></td>
								{/if}
								<td></td>
							</tr>
						{/foreach}
					{/if}
				</table>
				<input type="submit" style="float: right; margin: 20px 50px 30px 0; clear: both;" value="Submit" id="submit-discharge-request" />
			</form>
		{/if}
	{/if}
</div>
