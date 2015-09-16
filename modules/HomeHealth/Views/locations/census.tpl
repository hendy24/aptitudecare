<script>
	$(document).ready(function() {
		var url = SITE_URL + "/?module=HomeHealth&page=locations&action=census&location=" + $("#location option:selected").val() + "&area=" + $("#area option:selected").val();

		$('#area').change(function() {
			window.location = "/?module=HomeHealth&page=locations&action=census&location=" + $("#location").val() + "&area=" + $(this).val();
		});

		$("#patient-name").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=patient_name";
			window.location.href = redirectTo;
		});

		$("#referral-date").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=admit_date";
			window.location.href = redirectTo;
		});

		$("#start-of-care").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=start_of_care";
			window.location.href = redirectTo;
		});

		$("#discharge-date").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=discharge_date";
			window.location.href = redirectTo;
		});

		$("#referral-source").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=referral_source";
			window.location.href = redirectTo;
		});

		$("#phone").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=phone";
			window.location.href = redirectTo;
		});

		$("#zip").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=zip";
			window.location.href = redirectTo;
		});

		$("#pcp").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=pcp";
			window.location.href = redirectTo;
		});


		$("#search-patient-name").click(function() {
			window.location.href = SITE_URL + "/?module={$this->getModule()}&page=patients&action=searchPatients&term=" + $("#name-to-search").val();
		});

	});
</script>

{$this->loadElement("homeHealthHeader")}


<h1 style="font-weight: normal">Census</h1>

<div id="sub-header">
	<div id="patient-search">
		<input type="text" placeholder="Type patient name (last, first or first last)" id="name-to-search">
		<input type="button" value="Search" id="search-patient-name">
	</div>
		<div id="download-links">
		<a href="{$current_url}&amp;export=excel"><img src="{$FRAMEWORK_IMAGES}/icons/excel-xls-icon.png" alt=""></a>
	</div>
</div>

<div id="patient-count">
	<strong>Total # of Patients:</strong> {$numOfPatients}
</div>

<table class="view">
	<tr>
		<th><a href="" id="patient-name">Patient Name</a></th>
		<th></th>
		<th><a href="" id="referral-date">Referral Date<br>Start of Care</a></th>
		<th><a href="" id="discharge-date">Discharge Date</a></th>
		<th><a href="" id="referral-source">Referral Source</a></th>
		<th>Address</th>
		<th><a href="" id="pcp">Following Physician</a></th>
	</tr>
	{foreach $patients as $patient}
	<tr {if $patient->datetime_discharge != ""}class="background-red"{/if}>
		<td style="width:20%">{$patient->fullName()}</td>
		<td>{$patientMenu->menu($patient)}</td>
		<td>
			{display_datetime($patient->referral_date)}<br>
			{display_datetime($patient->start_of_care)}
		</td>
		<td>{display_date($patient->datetime_discharge)|default: ""}</td>
		<td>{$patient->referral_source}</td>
		<td style="width:19%" class="text-center">{$patient->fullAddress() nofilter}</td>
		<td>{$patient->physician_name}</td>
	</tr>
	{/foreach}
</table>
