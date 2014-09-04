<script>
	$(document).ready(function() {
		var url = SiteUrl + "/?module=HomeHealth&page=locations&action=census";

		$('#area').change(function() {
			window.location = url + "&location=" + $("#location").val() + "&area=" + $(this).val();
		});

		$("#patient-name").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=patient_name";
			window.location.href = redirectTo;
		});

		$("#admit-date").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=admit_date";
			window.location.href = redirectTo;
		});

		$("#discharge-date").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=discharge_date";
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
</script>

{include file="$views/elements/search_bar.tpl"}

<h1 style="font-weight: normal">Census</h1>
<div id="patient-search">
	Search: <input type="text" placeholder="Type patient name (last, first or first last)" id="search-patient-name" />
</div>

<br>
<table class="view">
	<tr>
		<th><a href="" id="patient-name">Patient Name</a></th>
		<th></th>
		<th><a href="" id="admit-date">Admission Date</a></th>
		<th><a href="" id="discharge-date">Discharge Date</a></th>
		<th><a href="" id="pcp">Primary Care Physician</a></th>
	</tr>
	{foreach $patients as $patient}
	<tr {if $patient->datetime_discharge != ""}class="background-red"{/if}>
		<td>{$patient->last_name}, {$patient->first_name}</td>
		<td>{$patientTools->menu($patient)}</td>
		<td>{display_date($patient->datetime_admit)}</td>
		<td>{display_date($patient->datetime_discharge)|default: ""}</td>
		<td>{$patient->physician_name}</td>
	</tr>
	{/foreach}
</table>