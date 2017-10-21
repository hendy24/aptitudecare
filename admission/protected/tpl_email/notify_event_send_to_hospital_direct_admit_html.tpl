<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>

<p>This email is intended for: {$recip_list}</p>

<table width="90%" border="0">

<tr>
	<td width="100%" style="padding: 10px 0 10px 20px;">

        A patient at {$facility->name} has been directly admitted to the hospital by {$trigger_user->getFullName()}:<br />
		{$ahr = $atHospitalRecord}
			<table width="100%">
				<tr>
					<td valign="top"><strong><u>Admitted to AHC</u></strong></td>
		<td valign="top">{$schedule->datetime_admit|strtotime|date_format:"%m/%d/%Y %I:%M %P"}</td>
</tr>
<tr>
		<td valign="top"><strong><u>Room</u></strong></td>
		<td valign="top">{$schedule->getRoom()->number|default:"<i>Unspecified</i>"}</td>
</tr>
<tr>
		<td valign="top"><strong><u>Entered by:</u></strong></td>
		<td valign="top">{$ahr->dischargeNurse()->getFullName()}</td>
</tr>
<tr>
		<td valign="top"><strong><u>Sent at:</u></strong></td>
		<td valign="top">{$ahr->datetime_sent|strtotime|date_format:"%m/%d/%Y %I:%M %P"}</td>
</tr>
<tr>
		<td valign="top"><strong><u>Hospital:</u></strong></td>
		<td valign="top">{$ahr->hospital_name|default:"<i>Not specified</i>"}</td>
</tr>
<tr>
		<td valign="top"><strong><u>Hospital Contact:</u></strong></td>
		<td valign="top">{if $ahr->hospital_contact_name != '' || $ahr->hospital_contact_phone != ''}
		{$ahr->hospital_contact_name} {$ahr->hospital_contact_phone}
		{else}
		<i>Not specified</i>
		{/if}
		</td>
</tr>
<tr>
		<td valign="top"><strong><u>Reason</u></strong></td>
		<td valign="top">{$ahr->comment|default:"<i>Unspecified</i>"}</td>
</tr>
<tr>
		<td valign="top"><strong><u>Bed-Hold?</u></strong>
		<td valign="top">{if $ahr->bedhold_offered == 1}Will discharge from AHC at {$ahr->datetime_bedhold_end|strtotime|date_format:"%m/%d/%Y %I:%M %P"}{else}<i>No</i>{/if}</td>
</tr>
<tr>
		<td valign="top"><strong><u>Admitted?</u></strong>
		<td valign="top">{if $ahr->was_admitted == 1}Yes{else}No{/if}</td>
</tr>
<tr>
		<td valign="top"><strong><u>Updated</u></strong>
		<td valign="top">{$ahr->datetime_updated|strtotime|date_format:"%m/%d/%Y %I:%M %P"}</td>
</tr>				
</table>
        
        <br />
        Please click for full details:<br />
        {$url = "{$SITE_URL}/?page=facility&action=sendToHospital&schedule={$schedule->pubid}&ahr={$ahr->pubid}"}
		<a href="{$url}">{$url}</a>
        
        <br />

	</td>
</tr>

</table>

</body>
</html>