<script>
	$(document).ready(function() {
		var url = SITE_URL + "/?module=HomeHealth&page=locations&action=recertification_list&location=" + $("#location option:selected").val() + "&area=" + $("#area option:selected").val();

		$('#area').change(function() {
			window.location = "/?module=HomeHealth&page=locations&action=recertification_list&location=" + $("#location").val() + "&area=" + $(this).val();
		});

		$("#patient-name").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=patient_name";
			window.location.href = redirectTo;
		});

		$("#start-of-care").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=start_of_care";
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

		$(".reCert").click(function(e) {
			e.preventDefault();

		});


	});
</script>

{$this->loadElement("homeHealthHeader")}

<h1>Re-Certification List</h1>

<table class="view">
	<tr>
		<th style="width:200px">Patient Name</th>
		<th style="width: 25px">&nbsp;</th>
		<th>Start of Care</th>
		<th>Status</th>
		<th>Phone Number</th>
		<th>Zip</th>
		<th style="width:90px">&nbsp;</th>
	</tr>
	{foreach $censusList as $list}
	<tr>
		<td>{$list->last_name}, {$list->first_name}</td>
		<td>{$patientTools->menu($list)}</td>
		<td>{display_date($list->start_of_care)}</td>
		<td>{$list->status}</td>
		<td>{$list->phone}</td>
		<td>{$list->zip}</td>
		<td><a href="#" class="button reCert">Re-Certify</a></td>
	</tr>
	{/foreach}
</table>
