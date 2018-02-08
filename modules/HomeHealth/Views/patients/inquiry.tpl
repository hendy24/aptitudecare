<script>
	$(document).ready(function() {
		$("#phone").mask("(999) 999-9999");

		{if $patient->date_of_birth == ''}
		$("#dob").mask("99/99/9999");
		{/if}

		$(".secondary-diagnosis-fields").hide();

		$("#add-diagnosis").click(function() {
			$(".secondary-diagnosis-fields").show();
			$(this).hide();
		});

		$("#secondary-ins-fields").hide();

		$("#add-insurance").click(function() {
			$(this).hide();
			$("#secondary-ins-fields").show();
		});

		if ($("#dob").val() != '') {
			$("#age").html(getAge($("#dob").val()));
		}

		$("#dob").blur(function() {	
			$("#age").html(getAge($(this).val()));
		});

		function getAge(date) {
			var now = new Date();
			var past = new Date(date);
			var nowYear = now.getFullYear();
			var pastYear = past.getFullYear();
			var age = nowYear - pastYear;
			return age
		}

		$("#admit-from-search").autocomplete({
			serviceUrl: SITE_URL,
			params: {
				module: 'HomeHealth',
				page: 'HealthcareFacilities',
				action: 'searchFacilityName',
				location: $("#location").val()
			}, minChars: 3,
			width: "300",
			onSelect: function (suggestion) {
				$("#admit-from").val(suggestion.data);
			}
		});


		$(".physician-search").autocomplete({
			serviceUrl: SITE_URL,
			params: {
				page: 'Physicians',
				action: 'searchPhysicians',
				location: $("#location").val()
			}, minChars: 3,
			onSelect: function (suggestion) {
				$(this).parent().find("input:hidden").val(suggestion.data);
			}
		});

		{$states = getUSAStates()}
		var states = [
		{foreach $states as $abbr => $state}
		{if $state != ''}
			{
				value: "{$state} ({$abbr})",
				data: "{$abbr}"
			}
			{if $state@last != true},{/if}
		{/if}
		{/foreach}
		];

		$("#state").autocomplete({
			lookup: states,
			onSelect: function (suggestion) {
				$(this).val(suggestion.data);
			}
		});


		$("private-pay-state").autocomplete({
			lookup: states,
			onSelect: function (suggestion) {
				$(this).val(suggestion.data);
			}
		});

	});
</script>





<h1>Pre-Admission Inquiry Record<br>
<span class="text-14">for</span> <br><span class="text-20">{$patient->first_name} {$patient->last_name}</span></h1>

<div id="sub-header">
		<div id="download-links">
		<a href="{$current_url}&amp;isMicro=true"><img src="{$FRAMEWORK_IMAGES}/icons/printer.png" alt=""></a>
	</div>
</div>


<form action="{$SITE_URL}" name="inquiry" method="post" id="inquiry-form">
	<input type="hidden" name="module" value="HomeHealth" />
	<input type="hidden" name="page" value="patients" />
	<input type="hidden" name="action" value="inquiry" />
	<input type="hidden" name="patient" value="{$patient->public_id}" />
	<input type="hidden" id="location" name="location_state" value="{$location->public_id}">
	<input type="hidden" name="submit" value="true" />
	<input type="hidden" name="path" value="{$currentUrl}" />
	<table class="form">
			
		<!-- Patient Information -->
		<tr> 
			<th colspan="3">Patient Info</th> 
		</tr>
		<tr class="form-header-row">
			<td>
				<strong>First:</strong><br>
				<input type="text" name="first_name" id="first-name" value="{$patient->first_name}" style="width: 232px;" />
			</td>
			<td>
				<strong>Middle:</strong><br>
				<input type="text" name="middle_name" id="middle-name" value="{$patient->middle_name}" style="width: 200px;" />
			</td>
			<td>
				<strong>Last:</strong><br>
				<input type="text" name="last_name" id="last-name" value="{$patient->last_name}" style="width: 232px;" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<strong>Address:</strong><br>
				<input type="text" name="address" id="address" value="{$patient->address}" style="width: 500px;" />
			</td>
			<td>
				<strong>Phone:</strong><br>
				<input type="text" name="phone" id="phone" value="{$patient->phone}" />
			</td>
		</tr>
		<tr>
			<td>
				<strong>City:</strong><br>
				<input type="text" name="city" id="city" value="{$patient->city}" />
			</td>
			<td>
				<strong>State:</strong><br>
				<input type="text" name="state" id="state" value="{$patient->state}" />
			</td>
			<td>
				<strong>Zip:</strong><br>
				<input type="text" name="zip" id="zip" value="{$patient->zip}" />
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top">
				<strong>Date of Birth:</strong><br>	
				<input type="text" name="date_of_birth" id="dob" value="{$patient->date_of_birth|date_format:'%m/%d/%Y'}" />	

			</td>
			<td>
				<strong>Age:</strong>
				<div id="age"></div><br>
			</td>
			<td>
				<strong>Sex:</strong><br>
				<input type="radio" name="sex" value="Male" {if $patient->sex == "Male"} checked{/if}>Male<br>
				<input type="radio" name="sex" value="Female" {if $patient->sex == "Female"} checked{/if}>Female
			</td>
		</tr>
		<tr>
			<td>
				<strong>Ethnicity:</strong><br>
				<select name="ethnicity" id="ethnicity">
					<option value="">Select...</option>
					{foreach $ethnicities as $e}
					<option value="{$e}" {if $patient->ethnicity == $e} selected{/if}>{$e}</option>
					{/foreach}
				</select>
			</td>
			<td>
				<strong>Language:</strong><br>
				<select name="language" id="language">
					<option value="">Select...</option>
					{foreach $languages as $language}
					<option value="{$language}" {if $patient->language == $language} selected{/if}>{$language}</option>
					{/foreach}
				</select>
			</td>
			<td>
				<strong>Marital Status:</strong><br>
				<select name="marital_status" id="marital-status">
					<option value="">Select...</option>
					{foreach $maritalStatuses as $ms}
					<option value="{$ms}" {if $patient->marital_status == $ms} selected{/if}>{$ms}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>Religion:</strong><br>
				<input type="text" name="religion" value="{$patient->religion}" id="religion">
			</td>
			<td>
				<strong>Emergency Contact:</strong><br>
				<input type="text" name="emergency_contact" id="emergency-contact" value="{$patient->emergency_contact}" style="width: 200px" />
			</td>
			<td>
				<strong>Phone:</strong><br>
				<input type="text" name="emergency_phone" id="emergency-phone" value="{$patient->emergency_phone}" />
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>



		<!-- Clinical Info -->
		<tr class="form-header-row">
			<th colspan="3">Clinical Info</th>
		</tr>
		<tr>
			<td>
				<strong>Admitting From:</strong><br>
				<input type="text" id="admit-from-search" value="{$admit->name}" style="width:210px" />
				<a href="/?page=healthcare_facilities&amp;action=add&amp;isMicro=1" rel="shadowbox;width=800;height=550">
					<img src="{$FRAMEWORK_IMAGES}/add-black-bkgnd.png" class="add-button" alt="">
				</a>
				<input type="hidden" name="admit_from_id" id="admit-from" value="{$admit->id}" />
			</td>
			<td>
				<strong>Primary Care Physician:</strong><br>
				<input type="text" class="physician-search" {if isset($pcp->id)}value="{$pcp->fullName()}"{else}value=""{/if} style="width:200px" />
				<a href="/?page=physicians&amp;action=add&amp;isMicro=1" rel="shadowbox;width=800;height=550">
					<img src="{$FRAMEWORK_IMAGES}/add-black-bkgnd.png" class="add-button" alt="">
				</a>
				<input type="hidden" id="pcp" class="physician-id" name="pcp_id" {if isset($pcp->id)}value="{$pcp->id}" {else} value=""{/if} />
			</td>
			<td>
				<strong>Surgeon/Specialist:</strong><br>
				<input type="text" class="physician-search" style="width:200px" {if isset($specialist->id)}value="{$specialist->fullName()}"{else} value=""{/if} />
				<a href="/?page=physicians&amp;action=add&amp;isMicro=1" rel="shadowbox;width=800;height=550">
					<img src="{$FRAMEWORK_IMAGES}/add-black-bkgnd.png" class="add-button" alt="">
				</a>
				<input type="hidden" id="specialist" class="physician-id" name="specialist_id" {if isset($specialist->id)}value="{$specialist->id}" {else} value=""{/if} />
			</td>
		</tr>
		<tr>
			<td>
				<strong>Following Physician:</strong><br>
				<input type="text" class="physician-search" {if isset($followingPhysician->id)}value="{$followingPhysician->fullName()}"{else}value=""{/if} style="width:200px" />
				<a href="/?page=physicians&amp;action=add&amp;isMicro=1" rel="shadowbox;width=800;height=550">
					<img src="{$FRAMEWORK_IMAGES}/add-black-bkgnd.png" class="add-button" alt="">
				</a>
				<input type="hidden" id="following-physician" class="physician-id" name="following_physician_id" {if isset($followingPhysician->id)}value="{$followingPhysician->id}" {else} value=""{/if} />
			</td>
		</tr>
		<tr>
			<td colspan="3"><strong>In-Patient Diagnosis:</strong></td>
		</tr>
		<tr>
			<td colspan="3">
				<textarea name="inpatient_diagnosis" id="inpatient-diagnosis" cols="110" rows="8">{$schedule->inpatient_diagnosis}</textarea>
			</td>
		</tr>
		<tr>
			<td><strong>Primary Diagnosis:</strong></td>
			<td colspan="2"><strong>Date/Onset:</strong> <input type="text" class="datepicker" name="diagnosis1_onset_date" value="{$schedule->diagnosis1_onset_date}" style="width:75px" /></td>
		</tr>
		<tr>
			<td colspan="3">
				<textarea name="primary_diagnosis" id="primary-diagnosis" cols="110" rows="8">{$schedule->primary_diagnosis}</textarea>
			</td>
		</tr>
		<tr>
			<td colspan="3"><a href="#secondary-diagnosis" id="add-diagnosis" class="right link">Add additional diagnosis</a></td>
		</tr>


		<!-- These fields will initially be hidden -->
		<tr class="secondary-diagnosis-fields">
			<td><strong>Secondary Diagnosis:</strong></td>
			<td colspan="2">
				<strong>Date/Onset:</strong> <input type="text" class="datepicker" name="diagnosis2_onset_date" value="{$schedule->diagnosis2_onset_date}" style="width:75px" /></td>
		</tr>
		<tr class="secondary-diagnosis-fields">
			<td colspan="3">
				<textarea name="secondary_diagnosis" id="secondary-diagnosis" cols="110" rows="8">{$schedule->primary_diagnosis}</textarea>
			</td>
		</tr>

		<tr>
			<td>
				<strong>Diabetic:</strong><br>
				<input type="radio" name="diabetic" value="Yes" {if $patient->diabetic == 1} checked{/if}>Yes<br>
				<input type="radio" name="diabetic" value="No" {if $patient->diabetic == 0} checked{/if}>No
			</td>
			<td>
				<strong>IDDM:</strong><br>
				<input type="radio" name="iddm" value="Yes" {if $patient->iddm == 1} checked{/if}>Yes<br>
				<input type="radio" name="iddm" value="No" {if $patient->iddm == 0} checked{/if}>No
			</td>
			<td style="vertical-align: top">
				<strong>Allergies:</strong><br>
				<input type="text" name="allergies" id="allergies" style="width:225px" value="{$patient->allergies}" />
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top">
				<strong>DME:</strong><br>
					{foreach $dmEquipment as $dme}
					<input type="checkbox" name="dme[]" value="{$dme->id}"{foreach $patientDme as $pdme}{if $pdme->dme_id == $dme->id} checked{/if}{/foreach}>&nbsp;{$dme->description}<br>
					{/foreach}
				</select>
			</td>
			<td colspan="2" style="vertical-align: top">
				<strong>Special Instructions:</strong><br>
				<textarea name="special_instructions" id="special-instructions" cols="70" rows="9">{$schedule->special_instructions}</textarea>
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		
		<tr>
			<th colspan="3">Insurance Info</th>
		</tr>
		<tr>
			<td>
				<strong>Primary Insurance:</strong><br>
				<input type="text" name="primary_insurance" id="primary-insurance" value="{$schedule->primary_insurance}" style="width:220px" />
			</td>
			<td>
				<strong>Policy Number:</strong><br>
				<input type="text" name="primary_insurance_number" value="{$schedule->primary_insurance_number}" style="width:200px" />
			</td>
			<td>
				<strong>Group Number:</strong><br>
				<input type="text" name="primary_insurance_group" value="{$schedule->primary_insurance_group}" style="width:200px" />
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<a href="#secondary-ins" id="add-insurance" class="right link">Add additional Insurance</a>
			</td>
		</tr>

		<tr id="secondary-ins-fields">
			<td>
				<strong>Secondary Insurance:</strong><br>
				<input type="text" name="secondary_insurance" id="primary-insurance" value="{$schedule->secondary_insurance}" style="width:220px" />
			</td>
			<td>
				<strong>Policy Number:</strong><br>
				<input type="text" name="secondary_insurance_number" value="{$schedule->secondary_insurance_number}" style="width:200px" />
			</td>
			<td>
				<strong>Group Number:</strong><br>
				<input type="text" name="secondary_insurance_group" value="{$schedule->secondary_insurance_group}" style="width:200px" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<strong>Private Pay:</strong><br>
				<input type="text" name="private_pay_party" id="responsible-party" value="{$schedule->private_pay_party}" style="width: 450px" />
			</td>
			<td>
				<strong>Phone:</strong><br>
				<input type="text" name="private_pay_phone" id="private-pay-phone" value="{$schedule->private_pay_phone}" >
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<strong>
					Address:<br>
					<input type="text" name="private_pay_address" id="private-pay-address" value="{$schedule->private_pay_address}" style="width:450px" />
				</strong>
			</td>
		</tr>
		<tr>
			<td>
				<strong>City:</strong><br>
				<input type="text" name="private_pay_city" id="private-pay-city" value="{$schedule->private_pay_city}" />
			</td>
			<td>
				<strong>State:</strong><br>
				<input type="text" name="private_pay_state" id="private-pay-state" value="{$schedule->private_pay_state}" />
			</td>
			<td>
				<strong>Zip:</strong><br>
				<input type="text" name="private_pay_zip" id="private-pay-zip" value="{$schedule->private_pay_zip}" />
			</td>
		</tr>

		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="checkbox" name="f2f_received" value="true"{if $schedule->f2f_received == true} checked{/if} /> Face to Face Confirmed</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="checkbox" name="clinicians_assigned" value="true"{if $schedule->clinicians_assigned == true} checked{/if} /> Clinicians Assigned</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="checkbox" name="insurance_verified" value="true"{if $schedule->insurance_verified == true} checked{/if} /> Insurance Verified</td>
		</tr>

		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td><input type="button" value="Cancel" onclick="window.location='SITE_URL'"></td>
			<td colspan="2"><input class="right" type="submit" value="Save"></td>
		</tr>
	</table>

</form>