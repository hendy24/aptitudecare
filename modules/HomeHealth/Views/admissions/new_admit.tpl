<script>
	$(document).ready(function() {
		// $('#new_admission').validate();
		$('#admit-request-phone').mask("(999) 999-9999");
		$('#admit-request-zip').mask("99999");
		$("#referral-individual").hide();
		$("#referral-source-name").hide();
		$("#physician-referral-name").hide();
		$(".hidden-title").hide();
		var $clone = "";
		var location = "";
		var admitDate = "";
		var admitFrom = "";
		var referredBy = "";
		var referredByType = "";
		var phone = "";
		var zip = "";
		var locationId = $("#admit-request-location option:selected").val();


		$("#referral-source-type").change(function(){
			if ($(this).val() == "physician") {
				$(".hidden-title").show();
				$("#physician-referral-name").show();
				$("#referral-source-name").hide();
				$("#referral-individual").hide();
			} else {
				$(".hidden-title").show();
				$("#referral-source-name").show();
				$("#referral-individual").show();
				$("#physician-referral-name").hide();

			}
		});

		$("#admit-request-location").change(function() {
			$("#admit-request-area option").remove();
			locationId = $("option:selected", this).val();
			$("#admit-from-location").val(locationId);
			//  Get the areas based on the selected location
			$.post(SITE_URL, { page: "locations", action: "fetchAreas", location: locationId }, function (e) { 
				$.each(e, function (i, v) {
					$("#admit-request-area").append("<option value=\"" + v.public_id + "\">" + v.name + "</option>");
					
				});
			},
			'json'
			);
		});

		$("#admit-request-location").on("change", function() {
			$("#admit-from-search").autocomplete({
				serviceUrl: SITE_URL,
				params: { 
					page: 'HealthcareFacilities',
					action: 'searchFacilityName',
					location: $("option:selected", this).val()
				}, minChars: 3,
				width: "300",
				onSelect: function (suggestion) {
					$("#admit-from").val(suggestion.data);
				}

			});

			// Duplicated to work on change, not just on page load
			$(".healthcare-facility-search").autocomplete({
				serviceUrl: SITE_URL,
				params: {
					module: "HomeHealth",
					page: "HealthcareFacilities",
					action: "searchFacilityName",
					location: $("#admit-request-location option:selected").val(),
				}, minChars: 4,
				width: "300",
				onSelect: function (suggestion) {
					$(this).next(".healthcare-facility-id").val(suggestion.data);
					$(this).val(suggestion.value);
				}
			});

			$(".physician-search").autocomplete({
				serviceUrl: SITE_URL,
				params: {
					page: "Physicians",
					action: "searchPhysicians",
					location: $("#admit-request-location option:selected").val(),
					referral_type: $("#referral-source-type option:selected").val()
				}, minChars: 4,
				width: "300",
				onSelect: function (suggestion) {
					$(this).next(".physician-id").val(suggestion.data);
					$(this).val(suggestion.value);
				}
			});

		});


		$(".healthcare-facility-search").autocomplete({
			serviceUrl: SITE_URL,
			params: {
				module: "HomeHealth",
				page: "HealthcareFacilities",
				action: "searchFacilityName",
				location: $("#admit-request-location option:selected").val(),
			}, minChars: 4,
			width: "300",
			onSelect: function (suggestion) {
				$(this).next(".healthcare-facility-id").val(suggestion.data);
				$(this).val(suggestion.value);
			}
		});


		$(".physician-search").autocomplete({
			serviceUrl: SITE_URL,
			params: {
				page: "Physicians",
				action: "searchPhysicians",
				location: $("#admit-request-location option:selected").val(),
				referral_type: $("#referral-source-type option:selected").val()
			}, minChars: 4,
			width: "300",
			onSelect: function (suggestion) {
				$(this).next(".physician-id").val(suggestion.data);
				$(this).val(suggestion.value);
			}
		});



		$("#new-admission").validate({
			submitHandler: function(form) {
				var $patientDiv = $(".patient-search-result");
				if ($clone != '') {
					$("#patient-results").empty();
					$clone.empty();
					$clone = '';
				}

				$.post(SITE_URL, {
					module: $("#module").val(),
					page: $("#page").val(),
					action: "searchPrevPatients",
					last_name: $("#admit-request-last-name").val(),
					first_name: $("#admit-request-first-name").val(),
					middle_name: $("#admit-request-middle-name").val()
					}, function (data) {
						$.each(data, function(i,e) {
							
							if (e.datetime_discharge != null) {
								var date = new Date(e.datetime_discharge);
							} else {
								var date = "No discharge date";
							}
							$clone = $patientDiv.clone();

							$clone.find("input.previous-patient-id:hidden").val(e.public_id);
							$clone.find(".previous-patient-name").append(e.first_name + " " + e.last_name);
							$clone.find(".previous-patient-dob").append(e.date_of_birth);
							$clone.find(".previous-patient-location").append(e.location_name);
							$clone.find(".previous-discharge-date").append(date);
							if (e.status == "Pending") {
								$(".admit-previous-patient").empty();
							} else {
								$clone.find(".previous-admit-status").append(e.status);
							}
							
							$clone.appendTo($("#patient-results"));

						});
						$("#patient-results").find("div").first().remove();
						$("#patient-results").show();
						$("#submit-new-patient").show();
					},
					"json"
				);
			}

		});

		
		$("#submit-new-patient").click(function() {
			//  Save new patient to db
			var patientData = $("#new-admission").serialize();
			$.post(SITE_URL, patientData, function (response) {
					window.location.href = response.url;
				}, "json"
			);


		});


		$("#patient-results").on("click", ".admit-previous-patient", function() {
			var patientId = $(".patient-search-result").find("input.previous-patient-id:hidden").val();
			var patientData = $("#new-admission").serializeArray();
			$.each(patientData, function(index, data) {
				if (data.name == "referral_date") {
					referralDate = data.value;
				}
				if (data.name == "location") {
					location = data.value;
				}
				if (data.name == "area") {
					area = data.value;
				}
				if (data.name == "admit_from") {
					admitFrom = data.value;
				}
				if (data.name == "referral_source") {
					referralSource = data.value;
				}
				if (data.name == "referred_by_type") {
					referredByType = data.value;
				}
				if (data.name == "phone") {
					phone = data.value;
				}
				if (data.name == "zip") {
					zip = data.value;
				}
			});

			if ($(".physician-id").val() != "") {
				referredBy = $(".physician-id").val();
			} else if ($(".healthcare-facility-id").val() != "") {
				referredBy = $(".healthcare-facility-id").val();
			}

			$.post(SITE_URL, { 
				module: "HomeHealth",
				page: "Admissions",
				action: "submitPrevPatient",
				patient_id: patientId,
				referral_date: referralDate,
				location: location,
				area: area,
				admit_from: admitFrom,
				referral_source: referralSource,
				referred_by_id: referredBy,
				referred_by_type: referredByType,
				phone: phone,
				zip: zip
				}, function (response) {
					window.location.href = response.url;
				}, "json"
			);
		});

	});
</script>





<h1>New Admission Request</h1>

<form name="new_admission" id="new-admission" method="post" action="{$SITE_URL}">
	<input type="hidden" id="module" name="module" value="HomeHealth" />
	<input type="hidden" id="page" name="page" value="admissions" />
	<input type="hidden" name="action" value="submitNewAdmit" />
	<input type="hidden" name="submit" value="true">
	<input type="hidden" name="path" value="{$current_url}">
	<input type="hidden" name="submit" value="true" />
	<table class="form">
		<tr>
			<td colspan="3" class="title"><strong>Admit Date:</strong></td>
			
		</tr>
		<tr>
			<td colspan="3"><input type="text" class="datepicker" name="referral_date" value="" required /></td>
		</tr>
			
		<tr>	
			<td class="title"><strong>Location:</strong></td>
			<td class="title" colspan="2"><strong>Area:</strong></td>
		</tr>
		<tr>
			<td>
				<select name="location" id="admit-request-location">
					{foreach $locations as $location}
					<option value="{$location->public_id}"{if $location->id == $selectedLocation->id} selected{/if}>{$location->name}</option>
					{/foreach}
				</select>
			</td>
			<td colspan="2">
				<select name="area" id="admit-request-area">
					{foreach $areas as $area}
					<option value="{$area->public_id}">{$area->name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class="title"><strong>Patient Location:</strong></td>
			<td class="title"><strong>Referral Source:</strong></td>
			<td class="title hidden-title"><strong>Referral Source Name:</strong></td>
		</tr>
		<tr>
			<td style="width: 275px">
				<input type="text" class="healthcare-facility-search" style="width: 250px" required />
				<input type="hidden" name="admit_from" class="healthcare-facility-id" />
				<a href="/?page=healthcare_facilities&amp;action=add&amp;isMicro=1" rel="shadowbox;width=800;height=550">
					<img src="{$FRAMEWORK_IMAGES}/add-black-bkgnd.png" class="add-button" alt="">
				</a>
			</td>
			<td>
				<select name="referral_source" id="referral-source-type">
					<option value="">Select...</option>
					<option value="3">Hospital</option>
					<option value="4">Assisted/Independent Living</option>
					<option value="physician">Physician</option>
					<option value="5">Group Home</option>
					<option value="1">Skilled Nursing Facility</option>
				</select>
			</td>
			<td id="referral-source-name">
				<input type="text"  class="healthcare-facility-search" name="referral_name" style="width: 250px">
				<input type="hidden" name="referred_by_id" class="healthcare-facility-id" />
			</td>
			<td id="physician-referral-name">
				<input type="text"  class="physician-search" name="referral_name" style="width: 250px">
				<input type="hidden" name="physician_id" class="physician-id" />
			</td>
		</tr>

		<tr id="referral-individual">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="location" colspan="2">
				<strong>Referring Individual</strong><br>
				<input type="text" id="referral-source-search" style="width: 250px" />
				<input type="hidden" id="referral-source" name="referred_by_id" />
				<input type="hidden" id="referral-source-type" name="referred_by_type" />
			</td>
		</tr>


		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td class="section-title" colspan="3"><strong>Patient Info:</strong></td>
		</tr>
		<tr>
			<td class="title">Last Name</td>
			<td class="title">First Name</td>
			<td class="title">Middle Name</td>
		</tr>
		<tr>
			<td><input type="text" id="admit-request-last-name" name="last_name" style="width:250px;" required  /></td>
			<td><input type="text" id="admit-request-first-name" name="first_name" style="width:150px;" required /></td>
			<td><input type="text" id="admit-request-middle-name" name="middle_name" /></td>
		</tr>
		<tr>
			<td class="title">Phone</td>
			<td class="title">Zip</td>
		</tr>
		<tr>
			<td><input type="text" id="admit-request-phone" name="phone" required /></td>
			<td><input type="text" id="admit-request-zip" name="zip" required /></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align: right;"><input type="submit" value="Search" id="admit-request-search" /></td>
		</tr>
	</table>
</form>




<!-- Hidden divs for previous patient info -->
<div id="patient-results">
	<h2>Previous Patient Search Results</h2>
	<div class="patient-search-result">
		<h2 class="previous-patient-name"></h2>
		<p class="previous-patient-dob"><strong>Date of Birth:</strong>&nbsp; </p>
		<p class="previous-patient-location"><strong>Location:</strong>&nbsp; </p>
		<p class="previous-discharge-date"><strong>Discharge Date:</strong>&nbsp; </p>
		<p class="previous-admit-status"><strong>Admission Status:</strong>&nbsp; </p>
		<input type="hidden" class="previous-patient-id" value="" />
		<input type="button" class="admit-previous-patient right" value="Admit" />
	</div>
	
</div>
<div id="submit-new-patient">
	<input type="button" id="submit-new-patient" class="right" value="This is a new Patient">
</div>
<div class="clear"></div>

