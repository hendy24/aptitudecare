{setTitle title="New Admit Request"}
{jQueryReady}

	var elective = 0;
	var flagReadmit = '';
	var flagValue = '';
	var flagPrompt = '';
	$("#org-field").hide();
	$("#physician-field").hide();
	$("#case-manager-field").hide();
	$("#other-field").hide();
	$("#home-health-field").hide();
	
	$("#referral-source").change(function() {
		if ($("#referral-source input:checked").val() == "org") {
			$("#org-field").show();
		} else {
			$("#org-field").hide();
		}
		
		if ($("#referral-source input:checked").val() == "doc") {
			$("#physician-field").show();
		} else {
			$("#physician-field").hide();
		}
		
		if ($("#referral-source input:checked").val() == "cm") {
			$("#case-manager-field").show();
		} else {
			$("#case-manager-field").hide();
		}
		
		if ($("#referral-source input:checked").val() == "other") {
			$("#other-field").show();
		} else {
			$("#other-field").hide();
		}
		
	});
	
	
	$("#scheduled-home-health").click(function() {
		if ($("#scheduled-home-health").attr('checked')) {
			$("#home-health-field").show();
		} else {
			$("#home-health-field").hide();
		}
	});


	$("#admit-request-search").click(function(e) {		
		$.getJSON(SITE_URL, { page: 'patient', action: 'searchPatientByName', last_name: $("#admit-request-last-name").val(), first_name: $("#admit-request-first-name").val(), middle_name: $("#admit-request-middle-name").val()}, function (json) {
			var suggest_html = '';
			if (json.length > 0) {
				suggest_html += "<h2>Are any of these the person you\'re looking for?</h2><br /><br />";
				$.each (json, function(i, val) {
					if (val.flag_readmission == 2) {
						flagValue = val.flag_readmission;
					}
					if (val.flag_readmission == 1) {
						flagReadmit = '<a class="tooltip"><img src=' + SITE_URL + '/images/icons/flag_yellow.png /><span>This patient has been flagged for re-admission.  Please discuss this admission<br />with the administrator prior to admission.</span></a>';
						flagPrompt = 'prompt-warning';
					} else if (val.flag_readmission == 2) {
						flagReadmit = '<a class="tooltip"><img src=' + SITE_URL + '/images/icons/flag_red.png /><span>This patient has been denied re-admission</span></a>';
						flagPrompt = 'prompt-warning';
					}
					suggest_html += '<br /><div class="patient-search-results"><strong>' + val.label + '</strong>&nbsp;&nbsp;' + flagReadmit + '<br />Birthdate: ' + val.birthday + '<br />Gender: ' + val.sex + '<br />Social Security #: ' + val.ssn + '<br />Previous Admission Date: ' + val.admit_date + '<br />Previous Discharge Date: ' + val.discharge_date + '<br /><br />';
					if (val.is_complete == false) {
						suggest_html += '<a class="hospital-visit" href="' + SITE_URL + '/?page=coord&amp;action=trackHospitalVisits"><button>Hospital Visit</button></a><br /></div>';
					} else if (val.flag_readmission != 2) {
						suggest_html += '<a class="admit-request-submit-existing ' + flagPrompt + '" href="' + SITE_URL + '/?page=patient&amp;action=submitAdmitRequestExistingPatient&amp;person_id=' + val.person_id + '"><button>Re-Admit this Patient</button></a><br /></div>';
					}				
				});
				if (json.length > 0 && flagValue != 2) {
					suggest_html += '<br /><br /><input type="button" id="admit-request-submit-as-new" value="This is a new patient." /><br /><br />';
				}
				$("#admit-request-suggestions").html(suggest_html);
			} else {
				var suggest_html = 'No match found.  <input type="button" id="admit-request-submit-as-new" value="This is a new patient." />';
				$("#admit-request-suggestions").html(suggest_html);
			}
		});
	});

	$("#elective-checkbox").change(function() {
		elective = this.checked ? this.value : 0;
	});

	$("#admit-request-submit-as-new").live("click", function(e) {
		e.preventDefault();
		if ($("#admit-request-date-admit").val() == '') {
			jAlert('You must select an admission date/time.');
			return false;
		}
		$.post(SITE_URL + "/", { 
			page: 'patient', 
			action: 'submitAdmitRequestNewPatient', 
			datetime_admit: $("#admit-request-date-admit").val(),
			last_name: $("#admit-request-last-name").val(), 
			first_name: $("#admit-request-first-name").val(), 
			middle_name: $("#admit-request-middle-name").val(),
			facility: $("#admit-request-facility option:selected").val(),
			admit_from: $("#admit-from").val(),
			hospital_id: $("#hospital").val(),
			physician_id: $("#physician").val(),
			case_manager_id: $("#case-manager").val(),
			other_name: $("#other-name").val(),
			other_phone: $("#other-phone").val(),
			home_health: $("#home-health").val(),
			other_diagnosis: $("#other-diagnosis").val(),
			elective: elective
		}, function(json) {
			if (json.status == true) {
				jAlert("Admit request has been saved.", "Success!", function(r) {
					window.parent.location.href = SITE_URL + '/?page=coord';
				});
			} else {
				var msg = '';
				$.each(json.errors, function(i, v) {
					msg = msg + v + '\n';
				});
				jAlert(msg, "Error");
			}
		}, "json");
			
	});
	
	
		
	$(".admit-request-submit-existing").live("click", function(e) {
		e.preventDefault();
		var url = $(this).attr("href");
		if ($("#admit-request-date-admit").val() == '') {
			jAlert('You must select an admit date.');
			return false;
		} else if ($(this).hasClass('prompt-warning')) {
			$("#confirm-review").dialog({
				dialogClass: "no-close",
				resizable: false,
				height: 140,
				modal: true,
				title: "Review Required",
				height: 170,
				buttons: {
					Cancel: {
						text: 'Cancel',
						'class': 'left',
						click: function () {
							$(this).dialog("close");
						}
					},
					Submit: {
						text: "Submit",
						'class': 'right',
						click: function () {
							location.href = url + '&datetime_admit=' + $("#admit-request-date-admit").val() + "&facility=" + $("#admit-request-facility option:selected").val() + "&admit_from=" + $("#admit-from").val() + "&readmit_type=" + $("#readmit-type :checked").val() + "&elective=" + elective + "&home_health=" + $("#home-health").val() + "&hospital_id=" + $("#hospital").val() + "&physician_id=" + $("#physician").val() + "&case_manager_id=" + $("#case-manager").val() + "&other_name=" + $("#other-name").val() + "&other_phone=" + $("#other-phone").val();
						}
					}
 				}
			});
		
		} else {
			location.href = url + '&datetime_admit=' + $("#admit-request-date-admit").val() + "&facility=" + $("#admit-request-facility option:selected").val() + "&admit_from=" + $("#admit-from").val() + "&readmit_type=" + $("#readmit-type :checked").val() + "&elective=" + elective + "&home_health=" + $("#home-health").val() + "&hospital_id=" + $("#hospital").val() + "&physician_id=" + $("#physician").val() + "&case_manager_id=" + $("#case-manager").val() + "&other_name=" + $("#other-name").val() + "&other_phone=" + $("#other-phone").val();
		}
	});
		
	
	$("#org-search").autocomplete({
		minLength: 4,
		source: function(req, add) {
			$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term, facility: $("#admit-request-facility option:selected").val()}, function (json) {
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
			$.getJSON(SITE_URL, { page: 'caseManager', action: 'searchCaseManagers', term: req.term, facility: $("#admit-request-facility option:selected").val()}, function (json) {
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
			e.target.value = ui.item.label;		
		}
	});
	
	$("#physician-search").autocomplete({
		minLength: 3,
		source: function(req, add) {
			$.getJSON(SITE_URL, { page: 'physician', action: 'searchPhysicians', term: req.term, facility: $("#admit-request-facility option:selected").val()}, function (json) {
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
	
	
	$("#hospital-search").autocomplete({
		minLength: 4,
		source: function(req, add) {
			$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term, facility: $("#admit-request-facility option:selected").val()}, function (json) {
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
	
	$("#homehealth-search").autocomplete({
		minLength: 4,
		source: function(req, add) {
			$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHomeHealth', term: req.term, facility: $("#admit-request-facility option:selected").val()}, function (json) {
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
	
	$("#code-search").autocomplete({
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
	
	
	$(".schedule-datetime").datetimepicker({
		timeFormat: "hh:mm tt",
		stepMinute: 15,
		hour: 13,	
	});	
	
	
{/jQueryReady}
{$facilities = $auth->getRecord()->getFacilities()}
<h1 class="text-center">New Admit Request</h1>
<br />
<table class="align-center" cellspacing="5" cellpadding="3">
		<tr>
			<td><strong>Admit Date:</strong></td>
		</tr>
		<tr>
			<td><input type="text" class="schedule-datetime" id="admit-request-date-admit" value="{$datetimeAdmitDefault|date_format:"%m/%d/%Y %I:%M %P"}" /></td>
		</tr>
		<tr>
			<td><strong>Facility:</strong></td>
		</tr>
		<tr>
			<td>
				<select name="facility" id="admit-request-facility">
					{foreach $facilities as $f}
					<option value="{$f->pubid}"{if $f->id == $auth->getRecord()->default_facility} selected{/if}>{$f->getTitle()}&nbsp;&nbsp;</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td><strong>Admit From:</strong></td>
		</tr>
		<tr>
			<td>
				<input type="text" id="hospital-search" style="width: 300px;" size="30" />
				<input type="hidden" name="admit_from" id="admit-from" />
				&nbsp;&nbsp;<a rel="shadowbox;width=550;height=450" href="{$SITE_URL}/?page=hospital&action=add&isMicro=1"><img src="{$SITE_URL}/images/add.png" class="new-admit-add-item" /></a>
			</td>
		</tr>
		
		<tr>
			<td><strong>Referral Source:</strong></td>
		</tr>
		<tr>
			<td id="referral-source">
				<input type="radio" name="referral_source" value="org"  /> Organization&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;				
				<input type="radio" name="referral_source" value="doc" /> Doctor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="referral_source" value="cm" /> Case Manager&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="referral_source" value="other" /> Other&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr >
			<input type="hidden" name="hospital_id" id="hospital" />
			<input type="hidden" name="physician_id" id="physician" />
			<input type="hidden" name="case_manager_id" id="case-manager" />
			<td id="org-field"><input type="text" id="org-search" size="50" placeholder="Enter the referring hospital or organization name" /><a href="{$SITE_URL}/?page=hospital&action=add&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/add.png" class="new-admit-add-item" /></a></td>
			<td id="physician-field"><input type="text" id="physician-search" size="50" placeholder="Enter the referring physician name" /><a href="{$SITE_URL}/?page=physician&action=add&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/add.png" class="new-admit-add-item" /></a></td>
			<td id="case-manager-field"><input type="text" id="case-manager-search" size="50" placeholder="Enter the referring case manager name" /><a href="{$SITE_URL}/?page=caseManager&action=add&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/add.png" class="new-admit-add-item" /></a></td>
			<td id="other-field"><input type="text" id="other-name" name="referred_by_name" size="50" placeholder="Enter the name of the referring person" /> &nbsp;&nbsp;<input type="text" id="other-phone" name="referred_by_phone" class="phone" placeholder="Enter the phone #" size="20" /></td>
		</tr>
		
		<tr>
			<td><input type="checkbox" name="scheduled_home_health" id="scheduled-home-health" value="1"  />Pre-scheduled Home Health</td>
		</tr>
		<tr>
			<td id="home-health-field">
				<input type="text" id="homehealth-search" style="width: 300px;" size="30" placeholder="Enter the name of the home health agency" /><a href="{$SITE_URL}/?page=hospital&action=add&type=Home%20Health&isMicro=1" rel="shadowbox;width=550;height=450"><img src="{$SITE_URL}/images/add.png" class="new-admit-add-item" /></a>
				<input type="hidden" name="home_health" id="home-health" />
			</td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td valign="bottom"><strong>Patient Name:&nbsp;&nbsp;</strong></td>
		</tr>
		<tr>
			<td valign="top">
				<table cellpadding="5" cellspacing="3">
					<tr>
						<td>Last</td>
						<td>First</td>
						<td>Middle</td>
					</tr>
					<tr>
						<td><input type="text" id="admit-request-last-name" name="last_name" size="35" />&nbsp;&nbsp;</td>
						<td><input type="text" id="admit-request-first-name" name="first_name" size="35" />&nbsp;&nbsp;</td>
						<td><input type="text" id="admit-request-middle-name" name="middle_name" size="25" /></td>
					</tr>
					<tr>
						<td><strong>Admission Diagnosis</strong></td>
<!-- 						<td><strong>Admission ICD-9 Code</strong></td>
 -->					</tr>
					<tr>
						<td colspan="2"><textarea cols="80" rows="6" name="other_diagnosis" id="other-diagnosis"></textarea></td>
<!-- 						<td valign="top">
							<input type="text" id="code-search"  style="width: 250px;" />
							<input type="hidden" name="icd9" id="icd9" />
						</td>
 -->				</tr>
 					<tr>
 						<td><input type="checkbox" id="elective-checkbox" name="elective-check" value="1" /> 
 							<input type="hidden" value="1" id="hdnElective" name="elective" />
 							Patient is an elective surgery
 						</td>
 					</tr>
					<tr>
						<td colspan="3" align="right"><input type="button" value="Search" id="admit-request-search" style="margin-top: 20px;" /></td>			
					</tr>
				</table>
			</td>
		</tr>
</table>
<div id="admit-request-suggestions"></div>	
<div id="confirm-review"><p>This patient has been flagged for re-admission.  You can read this reasons for the flag by hovering over the flag icon next to the patient name; please review this request with the facility administrator prior to re-admitting this patient.</p></div>
