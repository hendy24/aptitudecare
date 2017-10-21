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

        This message has been generated to notify you of a scheduled transfer of a patient from your AHC facility to another. The transfer has been scheduled by {$trigger_user->getFullName()}<br />
		<br />
		<strong>Who:</strong> {$schedule_after->getPatient()->fullName()}<br />
		<strong>When:</strong> {$schedule_after->admitDatetimeFormatted()}<br />
		<table width="50%">
			<tr><td></td><td><strong>From</strong></td><td><strong>To</strong></tr>
			
			<tr>
				<td nowrap><strong>Facility:</strong></td>
				<td nowrap>{$schedule_before->getFacility()->name}</td>
				<td nowrap>{$schedule_after->getFacility()->name}</td>
			</tr>
			<tr>
				<td nowrap><strong>Room:</strong></td>
				<td nowrap>{$schedule_before->getRoom()->number}</td>
				<td nowrap><i>To be assigned</i></td>
			</tr>
			
		</table>
        
        <br />
        Please click for full details:<br />
        {$url = "{$SITE_URL}/?page=facility&action=discharge&schedule={$schedule->pubid}"}
		<a href="{$url}">{$url}</a>
        
        <br />

	</td>
</tr>

</table>

</body>
</html>