{$atHospitalRecord = $schedule->atHospitalRecord()}
{$codes = CMS_Icd9_Codes::generate()}
{$codes->load($atHospitalRecord->icd9_id)}
{setTitle title="Hospital Stay for {$schedule->getPatient()->fullName()}"}
{$hospital = CMS_Hospital::generate()}
{$hospital->load($atHospitalRecord->hospital)}
{jQueryReady}
$("#hospital-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term}, function (json) {
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
		console.log(ui.item);
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


$("input[name=affirm]").click(function(e) {
	// hide all
	$(".affirm-details").slideUp();
	
	$("#" + $(this).val() + "-details").slideDown();
});

$(".schedule-datetime").datetimepicker({
	buttonImageOnly: true,
	timeFormat: "hh:mm tt",
	stepMinute: 15,
	hour: 11
	
});



$(".phone").mask("(999) 999-9999");
{/jQueryReady}

{if $atHospitalRecord == false}
<h1 class="text-center">Initiate hospital visit for <i>{$schedule->getPatient()->fullName()}</i></h1>
{else}
<h1 class="text-center">Update hospital visit for <i>{$schedule->getPatient()->fullName()}</i></h1>
{/if}
<br />
<form method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="facility" />
	<input type="hidden" name="action" value="submitSendtoHospital" />
	<input type="hidden" name="schedule" value="{$schedule->pubid}" />	
	<input type="hidden" name="_path" value="{$path|default:urlencode(currentURL())}" />
	{if $atHospitalRecord != false}
	<input type="hidden" name="id" value="{$atHospitalRecord->pubid}" />
	{/if}
	<table id="form-table" cellpadding="5" cellspacing="0">
		<tr>
			<td valign="top"><strong>Hospital</strong></td>
			<td valign="top"><strong>Hospital Phone Number</strong></td>
		</tr>
		<tr>
			<td valign="top">
				<input type="text" id="hospital-search" value="{$hospital->name}" size="60" />
				<input type="hidden" name="hospital" id="hospital" value="{$hospital->id}" />
			</td>
			<td id="hospital-phone"></td>
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
<!-- 				<strong>ICD-9 Code</strong><br />
				<input type="text" id="code-search"  value="{if $codes->short_desc != ''}{$codes->short_desc} [{$codes->code}]{/if}" style="width: 250px;" />
				<input type="hidden" name="icd9" id="icd9" value="{$codes->id}" /><br />
				<br />
 -->			
 				<input type="radio" name="visit_type" value="0"{if $atHospitalRecord->scheduled_visit == 0} checked{/if} />Unscheduled<br />
				<input type="radio" name="visit_type" value="1"{if $atHospitalRecord->scheduled_visit == 1} checked{/if} />Scheduled<br />				
			</td>
		</tr>
		<tr>
			<td valign="top"><strong>When was he/she sent to the hospital?</strong></td>
		</tr>
		<tr>
			<td valign="top" colspan="2">
				<input type="text" name="datetime_sent" class="schedule-datetime"{if $atHospitalRecord->datetime_sent != ''} value="{$atHospitalRecord->datetime_sent|strtotime|date_format:"%m/%d/%Y %I:%M %P"}"{else} value="{time()|date_format:"%m/%d/%Y %I:%M %P"}"{/if} />
			</td>
		</tr>
		<tr>
			<td valign="top">
				{if $atHospitalRecord == ''}
				<input type="checkbox" name="direct_admit" value="1" /> <strong>Direct Admit Patient to hospital</strong><br />
				{/if}
<!-- 				{if $atHospitalRecord !== false} -->
				<br />
<!-- 				{if $schedule->datetime_discharge == ''} -->
<!-- 				<strong>Continue Tracking this patient:</strong><br /> -->
				
				{if $atHospitalRecord->datetime_sent == ''}
				<ul style="list-style: none;">
					<li>
						<input type="radio" name="affirm" value="admitted"{if $atHospitalRecord->was_admitted == 1} checked{/if} /> Patient was admitted to the hospital.<br />
						<div class="affirm-details" id="admitted-details" style="display: none; margin-left: 20px;">
							<table>
								<tr>
									<td><strong>Discharge as of:</strong></td>
									<td><input type="text" name="datetime_discharge" class="schedule-datetime" value="{$schedule->datetime_discharge|date_format:"%m/%d/%Y %I:%M %P"}" /></td>
								</tr>
			 				</table>
						</div>
					</li>
					<li>
						<input type="radio" name="affirm" value="under-observation" /> Patient is still under observation.
					</li>
				</ul>
				{/if}
<!-- 			<strong>Stop Tracking this patient:</strong><br /> -->
<!--
			<ul style="list-style: none;">
				{if $schedule->datetime_discharge == ''}
				<li>
					<input type="radio" name="affirm" value="not-admitted" /> Patient was not admitted to the hospital and has returned to the facility.  
				</li>
				{else}
				<li><input type="radio" name="affirm" value="discharged_home" /> Patient was discharged from the hospital and went home.</li>
				<li><input type="radio" name="affirm" value="discharged_other" /> Patient was discharged from the hospital and went to another location.</li>
				<li><input type="radio" name="affirm" value="dischared_expired" /> Patient expired while in the hospital.</li>
				{/if}
			</ul>
-->
			
<!-- 			{/if} -->
				<input type="checkbox" name="bedhold_offered" value="1"{if $atHospitalRecord->bedhold_offered == 1} checked{/if} />
				<strong>Patient accepted a bed hold.</strong>
			</td>
		</tr>
		<tr>
			<td valign="top">
				
				<div id="bedhold-input-details"{if $atHospitalRecord->bedhold_offered != 1} style="display: none;"{/if}>
					<strong>Hold bed until:</strong> <input type="text" name="datetime_bedhold_end" class="datetime-picker"{if $schedule->datetime_discharge_bedhold_end}value="{$schedule->datetime_discharge_bedhold_end|strtotime|date_format:"%m/%d/%Y %I:%M %P"}"{elseif $atHospitalRecord->datetime_bedhold_end != ''} value="{$atHospitalRecord->datetime_bedhold_end|strtotime|date_format:"%m/%d/%Y %I:%M %P"}"{else} value="{strtotime("+ 1 day 11:00 am")|date_format:"%m/%d/%Y %I:%M %P"}"{/if} />
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="submit" value="Save" />
			</td>
		</tr>
	</div>
	</table>
</form>