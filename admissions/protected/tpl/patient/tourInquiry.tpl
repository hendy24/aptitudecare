{setTitle title="Tour Inquiry"}
<h2>New Toured Patient Inquiry</h2>

<br />
<br />

<form method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="patient" />
	<input type="hidden" name="action" value="submitTourInquiry" />
	<input type="hidden" name="facility" value="{$facility->pubid}" />
	
	
<table>

	<tbody>
	<tr class="form-header-row">
		<td>Date</td>
		<Td>Toured By</Td>
	</tr>
	
	<tr>
	
	</tr>
	
	<tr class="form-header-row">
		<td colspan="2">Patient Name</td>
	</tr>
	
	<tr>
	
	</tr>
	
	<tr class="form-header-row">
		<td>Home Phone</td>
		<td>Cell Phone</td>
	</tr>
	
	<tr>
	
	</tr>
	
	<tr class="form-header-row">
		<td>Date &amp; Place of Scheduled Surgery</td>
		<td>Surgeon's Name</td>
	</tr>
	
	<tr>
	
	</tr>
	
	<tr class="form-header-row">
		<td>Type of Surgery or Procedure</td>
		<td>Do you have Medicare as Primary?</td>
	</tr>
	
	<tr>
		<td colspan="2">Have you been in a Skilled Nursing Facility in the past 60 days?</td>
		<td>
			<input type="radio" name="in_facility_60days" value="1" /> Yes
			<input type="radio" name="in_facility_60days" value="0" /> No
		</td>
	</tr>
	
	<tr class="form-header-row">
		<td>If YES, where and when?</td>
	</tr>
	
	<tr>
	
	</tr>
	
	<tr class="form-header-row">
		<td>Other Primary Insurance</td>
		<td>Supplemental Insurance</td>
	</tr>
	
	
	
	
	</tbody>
</table>
</form>
