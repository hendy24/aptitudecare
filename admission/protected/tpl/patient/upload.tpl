{setTitle title="Upload Patient Data"}

<div id="upload">
	<h1 class="text-center">Bulk Upload Patient Data</h1>
	<br />
	<br />
	<p>This page can be used to perfom a bulk upload of patient data.  The uploaded file must be in a Windows CSV format with as columns outlined below.  You can download the CSV Template file by clicking on the CSV icon below for use in entering your data.  A few of the columns including paymethod and long term need to be entered as described below or they will not correctly save.</p>
	<br />
	<a href="{$SITE_URL}/templates/patient_upload.csv"><img src="{$SITE_URL}/images/icons/csv.png" /></a>
	<br />
	<br />
	<table>
		<tr>
			<th colspan="2" class="text-14">The following fields are captured in the CSV file:</th>
		</tr>
		<tr>
			<td width="45%" valign="top">
				<ul>
					<li>Room Number</li>
					<li>Last Name</li>
					<li>First Name</li>
					<li>Middle Name</li>
					<li>Address</li>
					<li>City</li>
					<li>State (two letter state abbreviation)</li>
					<li>Zip</li>
				</ul>
			</td>
			<td valign="top">
				<ul>
					<li>Phone</li>
					<li>Birth Date</li>
					<li>Sex (Male/Female)</li>
					<li>Social Security Number</li>
					<li>Paymethod (options are Medicare, HMO, Rugs, or Private Pay)</li>
					<li>Medicare Number</li>
					<li>Long Term (enter 0 for short-term patients and 1 for long-term)</li>
				</ul>
			</td>
		</tr>
	</table>
	
	<br />
	<br />
	<div class="text-center">
		<form method="post" action="{$SITE_URL}" enctype="multipart/form-data">
			<input type="hidden" name="page" value="patient" />
			<input type="hidden" name="action" value="uploadData" />
			<input type="hidden" name="_path" value="{currentURL()}" />
			
			
			<select name="facility" id="facility">
				<option value="">Select a facility...&nbsp;&nbsp;</option>
				{foreach $facilities as $f}
					<option value="{$f->pubid}"{if $f->pubid == $facility->pubid} selected{/if}>{$f->name}</option>
				{/foreach}
			</select>
			<br />
			<br />
			<input type="file" name="patient_data" id="file" />
			<input type="submit" name="submit" value="Submit" />
		</form>
	</div>
</div>