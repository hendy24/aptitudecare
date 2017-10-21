{$atHospitalRecord = $schedule->atHospitalRecord()}
{$codes = CMS_Icd9_Codes::generate()}
{$codes->load($atHospitalRecord->icd9_id)}
{setTitle title="Hospital Stay for {$schedule->getPatient()->fullName()}"}
{$hospital = CMS_Hospital::generate()}
{$hospital->load($atHospitalRecord->hospital)}
{$dischargedBy = CMS_Site_User::generate()}
{$dischargedBy->load($atHospitalRecord->discharge_nurse)}
{jQueryReady}
$("#hospital-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.name + " (" + val.state + ")";
				obj.phone = val.phone;
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#hospital").val(ui.item.value);
		$("#hospital-phone").html(ui.item.phone);
		e.target.value = ui.item.label;		
	}
});

$("#code-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'coord', action: 'searchCodes', term: req.term}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.short_desc + " (" + val.code + ")";
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


$("input[name=bedhold_offered]").click(function(e) {
	if ($(this).attr("checked") == "checked") {
		$("#bedhold-input-details").slideDown();
	} else {
		$("#bedhold-input-details").slideUp();
	}
});


if ($('input[name="datetime_discharge"]').val() == '') {
	$(".affirm-details").hide();
} else {
	$(".affirm-details").show();
}

$("input[name=affirm]").click(function(e) {
	// hide all
	if ($(this).attr("checked") == "checked" && $(this).val() == "admitted") {
		$(".affirm-details").slideDown();
	} else {
		$(".affirm-details").slideUp();
	}	
});

$(".schedule-datetime").datetimepicker({
	buttonImageOnly: true,
	timeFormat: "hh:mm tt",
	stepMinute: 15,
	hour: 11
	
});


$(".bedhold-datetime").datetimepicker({
	buttonImageOnly: true,
	timeFormat: "hh:mm tt",
	stepMinute: 15,
	hour: 13
	
});




$(".phone").mask("(999) 999-9999");
{/jQueryReady}

{if $atHospitalRecord == false}
<h1 class="text-center">Initiate Hospital Visit<br /><span class="text-14">for</span> {$schedule->getPatient()->fullName()}</h1>
{else}
<h1 class="text-center">Manage Hospital Visit<br /><span class="text-14">for</span> <span class="text-18">{$schedule->getPatient()->fullName()}</span></h1>
{/if}
<form method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="facility" />
	<input type="hidden" name="action" value="submitSendtoHospital" />
	<input type="hidden" name="schedule" value="{$schedule->pubid}" />	
	<input type="hidden" id="facility" name="facility" value="{$facility->pubid}" />
	<input type="hidden" name="_path" value="{$path|default:urlencode(currentURL())}" />
	{if $atHospitalRecord != false}
	<input type="hidden" name="id" value="{$atHospitalRecord->pubid}" />
	{/if}
	<table id="form-table" cellpadding="5" cellspacing="0">
		<tr>
			<th colspan="2">Hospital Visit Info</th>
		</tr>
		<tr>
			<td valign="top"><strong>Hospital</strong></td>
			<td valign="top"><strong>Hospital Phone Number</strong></td>
		</tr>
		<tr>
			<td valign="top">
				<input type="text" id="hospital-search" value="{$hospital->name}" size="60" />
				<input type="hidden" name="hospital" id="hospital" value="{$hospital->id}" />
			</td>
			<td id="hospital-phone">{$hospital->phone}</td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td valign="top"><strong>Reason Sent to Hospital</strong></td>
			<td><strong>Type of Hospital Visit</strong></td>
		</tr>
		<tr>
			<td width="250px">
				<textarea name="comment" rows="5" cols="65">{$atHospitalRecord->comment}</textarea>
			</td>
			<td valign="top">
 				<input type="radio" name="visit_type" value="0"{if $atHospitalRecord->scheduled_visit == 0} checked{/if} />Unscheduled<br />
				<input type="radio" name="visit_type" value="1"{if $atHospitalRecord->scheduled_visit == 1} checked{/if} />Scheduled<br />				
			</td>
		</tr>
		<tr>
			<td valign="top"><strong>Date &amp; Time Sent</strong></td>
			<td><strong>Discharged By:</strong></td>
		</tr>
		<tr>
			<td valign="top">
				<input type="text" name="datetime_sent" class="schedule-datetime"{if $atHospitalRecord->datetime_sent != ''} value="{$atHospitalRecord->datetime_sent|strtotime|date_format:"%m/%d/%Y %I:%M %P"}"{else} value="{time()|date_format:"%m/%d/%Y %I:%M %P"}"{/if} />
			</td>
			<td>{$dischargedBy->first} {$dischargedBy->last}</td>
		</tr>		
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td id="flag"><input type="radio" value="1" {if $schedule->flag_readmission == 1} checked{/if} name="flag_readmission" />
			Flag this patient for re-admission <a class="tooltip"><img src="{$SITE_URL}/images/icons/information.png" /><span>When selected this patient will be flagged<br /> for review prior to re-admission.</span></a></td>
		</tr>
		
		{foreach $userRoles as $role}
		{if $role->name == "facility_administrator"}
		<tr class="deny-admit">
			<td id="flag"><input id="deny" type="radio" name="flag_readmission" value="2" {if $schedule->flag_readmission == 2} checked{/if}>
			Deny re-admission for this patient<a class="tooltip"><img src="{$SITE_URL}/images/icons/information.png" /><span>If this flag is set this patient will not be able to be re-admitted.</span></a></td>
		</tr>
		{/if}
		{/foreach}
		{if $atHospitalRecord == ''}
			<tr>
				<td valign="top"><input type="checkbox" name="direct_admit" value="1" /> <strong>Direct Admit Patient to hospital</strong><br /></td>
			</tr>
		{else}
			<tr>
				<th colspan="2">Patient Status</th>
			</tr>
			<tr>
				<td colspan="2"><input type="radio" name="affirm" value="admitted" {if $atHospitalRecord->was_admitted == 1} checked{/if} /> Patient was admitted to the hospital.
					<div class="affirm-details" id="admitted-details" style="display: none; margin-left: 20px;">
						<strong>Discharge as of:</strong> &nbsp;<input type="text" name="datetime_discharge" class="schedule-datetime" value="{$schedule->datetime_discharge|date_format:"%m/%d/%Y %I:%M %P"}" />
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2"><input type="radio" name="affirm" value="under-observation" /> Patient is still under observation.</td>
			</tr>
			<tr>
				<td colspan="2"><input type="radio" name="affirm" value="not-admitted" /> Patient was not admitted to the hospital and has returned to the facility.</td>
			</tr>
		{/if}
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" align="right"><input type="checkbox" name="bedhold_offered" value="1"{if $atHospitalRecord->bedhold_offered == 1} checked{/if} /> <strong>Patient accepted a bed hold.</strong></td>
		</tr>
		<tr>
			<td valign="top" align="right" colspan="2">
				
				<div id="bedhold-input-details" {if $atHospitalRecord->bedhold_offered != 1} style="display: none;"{/if}>
					<strong>Hold bed until:</strong> <input type="text" name="datetime_bedhold_end" class="bedhold-datetime"{if $schedule->datetime_discharge_bedhold_end}value="{$schedule->datetime_discharge_bedhold_end|strtotime|date_format:"%m/%d/%Y %I:%M %P"}"{elseif $atHospitalRecord->datetime_bedhold_end != ''} value="{$atHospitalRecord->datetime_bedhold_end|strtotime|date_format:"%m/%d/%Y %I:%M %P"}"{/if} />
				</div>
			</td>
		</tr>
		<tr>
			{if $atHospitalRecord->pubid}
				<td><a href="{$SITE_URL}/?page=facility&action=delete&schedule_hospital={$atHospitalRecord->pubid}" class="button">Cancel Visit</a></td>
			{else}
				<td>&nbsp;</td>
			{/if}
			<td align="right">
				<input type="submit" value="Save" />
			</td>
		</tr>
	</div>
	</table>
</form>