<script>
	$(document).ready(function() {
		var url = SiteUrl + "/?module=HomeHealth&page=locations&action=census&location=" + $("#location option:selected").val() + "&area=" + $("#area option:selected").val();

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


		$("#search-patient-name").keypress(function(e) {
			if (e.which == 13) {
				e.preventDefault();
				window.location.href = SiteUrl + "/?page=main_page&action=search_results&term=" + $(this).val();
			}
			
		});

	});

	$(document).ready(function() {
		$("#module").change(function() {
			var module = $("#module option:selected").val();
			if (module == "Admission") {
				window.location.href = SiteUrl + "/?module=Admission&user=" + User;
			} else {
				window.location.href = SiteUrl + "/?module=" + module;
			}

		});
	});
</script>

<div id="search-header">
	
	{if count($modules) > 1}
	<div id="modules">
		Module: <select name="module" id="module">
			
			{foreach $modules as $m}
				<option value="{$m->name}" {if $module == $m->name} selected{/if}>{$m->name}</option>
			{/foreach}
		</select>
	</div>
	{/if}
	
	<div id="locations">
		<select name="location" id="location">
			<div id="optgroup">
			{foreach $locations as $location}	
				<option value="{$location->public_id}" {if $location->public_id == $loc->public_id} selected{/if}><h1>{$location->name}</h1></option>
			{/foreach}
			</optgroup>
		</select>
	</div>
	
	
	<div id="areas">
		Area: <select name="areas" id="area">
			<option value="all">All</option>
			{foreach $areas as $area}
			<option value="{$area->public_id}" {if $selectedArea != 'all'}{if $area->public_id == $selectedArea->public_id} selected{/if}{/if}>{$area->name}</option>
			{/foreach}
		</select>
	</div>
</div>

<h1 style="font-weight: normal">Census</h1>
<div id="patient-search">
	Search: <input type="text" placeholder="Type patient name (last, first or first last)" id="search-patient-name" />
</div>

<br>
<table class="view">
	<tr>
		<th><a href="" id="patient-name">Patient Name</a></th>
		<th></th>
		<th><a href="" id="referral-date">Referral Date</a></th>
		<th><a href="" id="start-of-care">Start of Care</a></th>
		<th><a href="" id="discharge-date">Discharge Date</a></th>
		<th><a href="" id="referral-source">Referral Source</a></th>
		<th><a href="" id="phone">Phone Number</a></th>
		<th><a href="" id="zip">Zip</a></th>
		<th><a href="" id="pcp">Primary Care Physician</a></th>
	</tr>
	{foreach $patients as $patient}
	<tr {if $patient->datetime_discharge != ""}class="background-red"{/if}>
		<td>{$patient->fullName()}</td>
		<td>{$patientTools->menu($patient)}</td>
		<td>{display_date($patient->referral_date)}</td>
		<td>{display_date($patient->start_of_care)}</td>
		<td>{display_date($patient->datetime_discharge)|default: ""}</td>
		<td>{$patient->referral_source}</td>
		<td>{$patient->phone}</td>
		<td>{$patient->zip}</td>
		<td>{$patient->physician_name}</td>
	</tr>
	{/foreach}
</table>