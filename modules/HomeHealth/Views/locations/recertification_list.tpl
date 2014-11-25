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
