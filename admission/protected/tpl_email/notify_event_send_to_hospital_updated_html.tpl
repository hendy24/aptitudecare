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

        The hospital stay for a patient at {$facility->name} has been updated by {$trigger_user->getFullName()}:<br />
		{$ahr_before = $atHospitalRecord_before}
		{$ahr_after = $atHospitalRecord_after}
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
					<td valign="top"><strong><u>Discharge entered by:</u></strong></td>
					<td valign="top">{$ahr_before->dischargeNurse()->getFullName()}</td>
				</tr>
			</table>
			<table width="100%">
			<tr><td></td><td><strong>Before</strong></td><td><strong>After</strong></tr>
			<tr>
					<td valign="top"><strong><u>Sent at:</u></strong></td>
					<td valign="top">{$ahr_before->datetime_sent|strtotime|date_format:"%m/%d/%Y %I:%M %P"}</td>
					<td valign="top">{$ahr_after->datetime_sent|strtotime|date_format:"%m/%d/%Y %I:%M %P"}</td>
			</tr>
			<tr>
					<td valign="top"><strong><u>Hospital:</u></strong></td>
					<td valign="top">{$ahr_before->hospital_name|default:"<i>Not specified</i>"}</td>
					<td valign="top">{$ahr_after->hospital_name|default:"<i>Not specified</i>"}</td>
			</tr>
			<tr>
					<td valign="top"><strong><u>Hospital Contact:</u></strong></td>
					<td valign="top">{if $ahr_before->hospital_contact_name != '' || $ahr_before->hospital_contact_phone != ''}
					{$ahr_before->hospital_contact_name} {$ahr_before->hospital_contact_phone}
					{else}
					<i>Not specified</i>
					{/if}
					</td>
					<td valign="top">{if $ahr_after->hospital_contact_name != '' || $ahr_after->hospital_contact_phone != ''}
					{$ahr_after->hospital_contact_name} {$ahr_after->hospital_contact_phone}
					{else}
					<i>Not specified</i>
					{/if}
					</td>
			</tr>
			<tr>
					<td valign="top"><strong><u>Reason</u></strong></td>
					<td valign="top">{$ahr_before->comment|default:"<i>Unspecified</i>"}</td>
					<td valign="top">{$ahr_after->comment|default:"<i>Unspecified</i>"}</td>
			</tr>
			<tr>
					<td valign="top"><strong><u>Bed-Hold?</u></strong>
					<td valign="top">{if $ahr_before->bedhold_offered == 1}Will discharge from AHC at {$ahr_before->datetime_bedhold_end|strtotime|date_format:"%m/%d/%Y %I:%M %P"}{else}<i>No</i>{/if}</td>
					<td valign="top">{if $ahr_after->bedhold_offered == 1}Will discharge from AHC at {$ahr_after->datetime_bedhold_end|strtotime|date_format:"%m/%d/%Y %I:%M %P"}{else}<i>No</i>{/if}</td>
			</tr>
			<tr>
					<td valign="top"><strong><u>Admitted?</u></strong>
					<td valign="top">{if $ahr_before->was_admitted == 1}Yes{else}No{/if}</td>
					<td valign="top">{if $ahr_after->was_admitted == 1}Yes{else}No{/if}</td>
			</tr>
			<tr>
					<td valign="top"><strong><u>Updated</u></strong>
					<td valign="top">{$ahr_before->datetime_updated|strtotime|date_format:"%m/%d/%Y %I:%M %P"}</td>
					<td valign="top">{$ahr_after->datetime_updated|strtotime|date_format:"%m/%d/%Y %I:%M %P"}</td>
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