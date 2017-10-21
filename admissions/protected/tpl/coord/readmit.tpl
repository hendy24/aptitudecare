{setTitle title="Re-Admit Patient"}
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
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#hospital").val(ui.item.value);
		e.target.value = ui.item.label;	
		console.log("hello");
		console.log(ui.item.value);	
	}
	
});


{/jQueryReady}

{$facilities = $auth->getRecord()->getFacilities()}
{$hospital = CMS_Hospital::generate()}
{$hospital->load($atHospitalRecord->hospital)}
{$patient = CMS_Patient_Admit::generate()}
{$patient->load($schedule->patient_admit)}

<h1 class="text-center">Re-Admit {$schedule->getPatient()->first_name} {$schedule->getPatient()->last_name} from the Hospital</h2>
<br />
<form name="readmit" method="post" action="{$SITE_URL}" id="readmit-form"> 
	<input type="hidden" name="page" value="patient" />
	<input type="hidden" name="action" value="submitReadmit" />
	<input type="hidden" name="id" value="{$schedule->patient_admit}" />
	<input type="hidden" name="schedule" value="{$schedule->pubid}" />
	<input type="hidden" name="patient_id" value="{$patient->id}" />
	<input type="hidden" name="_path" value="{urlencode(currentURL())}" />
	<table cellspacing="5" cellpadding="3">
			<tr>
				<td><strong>Re-Admit Date:</strong></td>
			</tr>
			<tr>
				<td><input type="text" name="datetime_admit" class="datetime-picker" id="admit-request-date-admit" value="{$datetimeAdmitDefault|date_format:"%m/%d/%Y %I:%M %P"}" /></td>
			</tr>
			<tr>
				<td><strong>Facility:</strong></td>
			</tr>
			<tr>
				<td>
					<select name="facility" id="admit-request-facility">
						<option value=""></option>
						{foreach $facilities as $f}
						<option value="{$f->pubid}">{$f->getTitle()}&nbsp;&nbsp;</option>
						{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td><strong>Re-Admit From:</strong></td>
			</tr>			
			<tr>
				<td colspan=3>
					<input type="text" id="hospital-search" style="width: 300px;" size="30" value="{$hospital->name}" />
					<input type="hidden" name="hospital" id="hospital" value="{$hospital->id}" />
				</td>
			</tr>
			<tr>
				<td valign="bottom"><strong>Patient Name:&nbsp;&nbsp;</strong></td>
			</tr>
			<tr>
				<td><span class="text-16">{$schedule->getPatient()->fullName()}</span></td>
				<input type="hidden" name="patient" id="patient" value="{$patient->person_id}" />
			</tr>
			<tr>
				<td><strong>Admission Diagnosis</strong></td>
			</tr>
			<tr>
				<td><textarea cols="40" rows="6" name="other_diagnosis" id="other_diagnosis"></textarea></td>
			</tr>
			<tr>
				<td colspan="3" align="right"><input type="submit" value="Re-Admit" id="readmit-form" style="margin-top: 20px;" /></td>		
			</tr>	
	</table>
</form>