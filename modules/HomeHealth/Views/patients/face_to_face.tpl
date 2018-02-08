<script>
	$(document).ready(function() {
		$("#physician-search").autocomplete({
			serviceUrl: SITE_URL,
			params: {
				page: 'Physicians',
				action: 'searchPhysicians',
				location: $("#location").val()
			}, minChars: 3,
			onSelect: function (suggestion) {
				$("#physician").val(suggestion.data);
			}
		});
	});
</script>


<h1 style="margin-bottom: 4px">Face to Face Encounter</h1>
<p class="text-18 text-center" style="margin: 0">for</p>
<h2 style="margin:0">{$patient->fullName()}</h2>

<form action="{$siteUrl}" name="face_to_face" id="face-to-face" method="post">
	<input type="hidden" name="page" value="patients" />
	<input type="hidden" name="action" value="face_to_face" />
	<input type="hidden" name="patient" value="{$patient->public_id}" />
	<input type="hidden" name="path" value="{$current_url}" /> 

	<div id="page-wrapper">
		<p><strong>Birthdate:</strong> {display_date($patient->date_of_birth)}</p>
		<p>I certify that this patient is under my care and that I, or a nurse practitioner or physician's assistant working with me, had a face-to-face encounter with {$patient->fullName()} on:
		<input type="text" class="datepicker" name="f2f_date" value="{display_date($f2f_form->f2f_date)}" style="width: 70px" required /></p>
		<p> The encounter with the patient was in whole, or in part, for the following medical condition, which is the primary reason for home health care (medical condition(s)):</p>
		<textarea name="medical_condition" id="" >{$f2f_form->medical_condition}</textarea>
		<p>I certify that, based on my finding, the following services are medically necessary home health services:</p>
		<textarea name="home_health_services" id="">{$f2f_form->home_health_services}</textarea>
		<p>My clinical findings support the need for the above because:</p>
		<textarea name="home_health_reasons" id="">{$f2f_form->home_health_reasons}</textarea>
		<p>Further, I certify that my clinical findings support that this patient is homebound because:</p>
		<textarea name="homebound_reason" id="">{$f2f_form->homebound_reason}</textarea>

		<p class="text-right">
			<strong>Completed by: &nbsp;</strong>
			<input type="text" id="physician-search" {if isset($f2f_form->physician_id)}value="{$f2f_form->last_name}, {$f2f_form->first_name}" {else}value=""{/if} style="width:200px" />
			<input type="hidden" id="physician" name="physician_id" {if isset($f2f_form->physician_id)}value="{$f2f_form->physician_id}" {else} value=""{/if} />
		</p>
		

		<br><br><br><br>
		<div class="left text-left">
			<input type="button" value="Cancel" onClick="history.go(-1);return true;">
		</div>
		<div class="right text-right">
			<input type="submit" name="submit" value="Save" />
		</div>
		
	</div>
</form>

