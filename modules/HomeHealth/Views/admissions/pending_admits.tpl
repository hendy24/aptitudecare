<script>
	$(document).ready(function() {
		$('#area').change(function() {
			window.location = "?module=HomeHealth&page=admissions&action=pending_admits&location=" + $("#location").val() + "&area=" + $(this).val();
		});

		$("#location").change(function() {
			window.location = "?module=HomeHealth&page=admissions&action=pending_admits&location=" + $(this).val();
		});
	});
</script>

{include file="$views/elements/search_bar.tpl"}
<h2>Pending Admissions</h2>

<table class="view">
	<tr>
		<th>Patient Name</th>
		<th></th>
		<th>Admission Date</th>
		<th>Admission Location</th>
		<th>Primary Care Physician</th>
	</tr>
	{foreach $admits as $a}
	<tr>
		<td>{$a->fullName()}</td>
		<td>{$patientTools->menu($a)}</td>
		<td>{display_date($a->referral_date)}</td>
		<td>{$a->location_name}</td>
		<td>{$a->physician_name|default: "Not Entered"}</td>
	</tr>
	{/foreach}
</table>