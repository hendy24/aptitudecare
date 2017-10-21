<div id="reports">
	{if $type == ''}
	<h1 class="text-center">AHC Reports</h1>
	{/if}
	<br />
		
	{jQueryReady}
				
		if ($("#start-date").val() == '' && $("#end-date").val() == '' && $('#view option:selected').val() == '' && $('#year option:selected').val() == '') {
			$('#input1').hide();
			$('#input2').hide();
		} else if ($("#start-date").val() != '' || $("#end-date").val() != '' && $('#view option:selected').val() == '' && $('#year option:selected').val() == '') {
			$('#input1').show();
			$('#input2').show();
			$('other-types').show();
			$('.length-of-stay').hide();
		} else if ($("#start-date").val() == '' && $("#end-date").val() == '' && $('#view option:selected').val() != '' || $('#year option:selected').val() != '') {
			$('#input1').show();
			$('#input2').show();
			$('.length-of-stay').show();
			$('.other-types').hide();
		}
		
		
		var reportType = $('#report-type');	
		var redirectURL = function() {
			if (reportType.val() == "discharge_history") {
				return SITE_URL + '/?page=report&action=' + $("#report-type option:selected").val() + '&facility=' + $("#facility option:selected").val() + '&week_start={"last Sunday - 1 week"|date_format: "Y-m-d"}';
			}
			if (reportType.val() == "length_of_stay" || reportType.val() == "discharge_type" || reportType.val() == "discharge_service" || reportType.val() == "adc") {
				return SITE_URL + '/?page=report&action=' + $("#report-type option:selected").val() + '&facility=' + $("#facility option:selected").val() + '&view=' + $('#view option:selected').val() + '&year=' + $('#year option:selected').val() + '&orderby={$orderby}';
			} if (reportType.val() == "discharge_calls") {
				return SITE_URL + '/?page=report&action=' + $("#report-type option:selected").val() + '&facility=' + $("#facility option:selected").val();
			} else {
			return SITE_URL + '/?page=report&action=' + $("#report-type option:selected").val() + '&facility=' + $("#facility option:selected").val() + '&start_date=' + $("#start-date").val() + '&end_date=' + $("#end-date").val() + '&orderby=' + $("#orderby").val() + '&filterby=' + $("#filterby").val() + '&viewby={$viewby}&summary={$summary}';
			}
		}
				
	
		$("#report-search").click(function(e) {
			window.location.href = redirectURL();		
		});
	
		$("#facility").change(function(e) {	
			if ($("#report-type").val() != '' && ($("#start-date").val() != '' && $("#end-date").val() != '') || ($('#view option:selected').val() != '' && $('#year option:selected').val() != '')) {
				window.location.href = redirectURL();
			}	
		});
		
		reportType.change(function(e) {
			if (reportType.val() != "") {
				if (reportType.val() == "discharge_history") {
					$(".length-of-stay").hide();
					$(".other-types").hide();
					window.location.href = redirectURL();
				}
				else if (reportType.val() == "length_of_stay" || reportType.val() == "discharge_type" || reportType.val() == "discharge_service" || reportType.val() == "adc") {
					$(".length-of-stay").show();
					$(".other-types").hide();
					if ($('#facility').val() != '' && $('#view option:selected').val() != '' && $('#year option:selected').val() != '') {
						window.location.href = redirectURL();	
					}	
				} 
				else if (reportType.val() == "discharge_calls") {
					$(".length-of-stay").hide();
					$(".other-types").hide();
					if ($('#facility').val() != '') {
						window.location.href = redirectURL();
					}
				
				} else {
					$(".other-types").show();
					$(".length-of-stay").hide();
					if ($("#facility").val() != '' && $("#start-date").val() != '' && $("#end-date").val() != '') {
						window.location.href = redirectURL();	
					}	
				}
				
				$("#input1").show();
				$("#input2").show();
			}
		
			
		});
		
		$("#normal-view").click(function(e) {
			window.location.href = redirectURL() + '&filterby=&summary=0';
		});
		
		$("#start-date").change(function(e) {
			if ($("#facility").val() != '' && $("#report-type").val() != '' && $("#end-date").val() != '') {
				window.location.href = redirectURL();	
			}	
		});
		
		$("#end-date").change(function(e) {
			if ($("#facility").val() != '' && $("#report-type").val() != '' && $("#start-date").val() != '') {
				window.location.href = redirectURL();	
			}	
		});
		
		
		$("#orderby").change(function(e) {
			window.location.href = redirectURL();
		});
		
		$("#filterby").change(function(e) {
			window.location.href = redirectURL() + "&filterby=" + $("#filterby option:selected").val() + '&viewby=' + '&summary=1';
		});
		
		$("#view-by").hide();
		
		$("#viewby").change(function(e) {
			window.location.href = redirectURL() + "&viewby=" + $("#viewby option:selected").val();
		});
				
		$('#view').change(function(e) {
			if ($('#year option:selected').val() != '') {
				window.location.href = redirectURL();
			}
		});
		
		$('#year').change(function(e) {
			if ($('#view option:selected').val() != '') {
				window.location.href = redirectURL();
			}
		});
		
		$('#readmit-type').change(function(e) {
			window.location.href = redirectURL() + '&readmit_type=' + $('#readmit-type option:selected').val();
		});
		
		
	{/jQueryReady}
	
	{if $filterby != ''}
		{jQueryReady}
			$("#view-by").show();
		{/jQueryReady}
	{/if}
	
	{$facilities = $auth->getRecord()->getFacilities()}
	
	<table id="select-report-info" cellpadding="5">
		<tr>
			<td align="top">
				<strong>Run report for</strong><br />
					<select id="facility">
						<option value="">Select a facility...&nbsp;&nbsp;</option>
						{foreach $facilities as $f}
	    					<option value="{$f->pubid}"{if $f->pubid == $facility->pubid} selected{/if}>{$f->name}</option>
						{/foreach}
					</select>
			</td>
			<td>
				<strong>Type of Report</span><br />
				<select id="report-type">
					<option value="">Select the type of report...</option>
					{foreach $reportTypes as $k => $v}
					<option value="{$k}"{if $type == $k} selected{/if}>{$v}</option>
					{/foreach}
				</select>
			</td>
			<td width="150px" valign="top">
				<div id="input1">
					<div class="length-of-stay">
						<strong>View:</strong><br />
						<select id="view">
							<option value="">Select an option...</option>
							{foreach $viewOpts as $k => $v}
								<option value="{$k}"{if $view == $k} selected{/if}>{$v}</option>
							{/foreach}
						</select>
					</div>
					<div class="other-types"><strong>Start Date:</strong><br /><input type="text" id="start-date" class="date-picker" value="{$dateStart|date_format: "%m/%d/%Y"}" /></div>
						
				</div>
			</td>
			<td width="150px" valign="top">
				<div id="input2">
					<div class="length-of-stay">
						<strong>Year:</strong><br />
						<select id="year">
							<option value="">Select year...</option>
							{foreach $yearOpts as $k => $v}
								<option value="{$k}"{if $year == $k} selected{/if}>{$v}</option>
							{/foreach}
						</select>
					</div>
					<div class="other-types"><strong>End Date: </strong><br /><input type="text" id="end-date" class="date-picker" value="{$dateEnd|date_format: "%m/%d/%Y"}" /></div>
					
				</div>
			</td>
		</tr>
	<!--
		<tr>
			<td colspan="4" align="right"><input type="button" value="Search" id="report-search" /></td>
		</tr>
	-->
	</table>
</div>