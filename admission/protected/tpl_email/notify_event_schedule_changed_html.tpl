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

        The details of a scheduling has been modified by {$trigger_user->getFullName()}.<br />
		
		<table width="50%">
			<tr><td></td><td><strong>Before</strong></td><td><strong>After</strong></tr>
			
			{if $schedule->facility != $schedule_before->facility}
			<tr>
				<td nowrap><strong>Facility:</strong></td>
				<td nowrap>{$schedule_before->getFacility()->name}</td>
				<td nowrap>{$schedule->getFacility()->name}</td>
			</tr>
			{/if}
			{if $schedule->datetime_admit != $schedule_before->datetime_admit}
			<tr>
				<td nowrap><strong>Date/Time of Admission:</strong></td>
				<td nowrap>{$schedule_before->admitDatetimeFormatted()}</td>
				<td nowrap>{$schedule->admitDatetimeFormatted()}</td>
			</tr>
			{/if}
			
		</table>
        
        <br />
        Please click for full details:<br />
        {$url = "{$SITE_URL}/?page=patient&action=inquiry&schedule={$schedule->pubid}"}
		<a href="{$url}">{$url}</a>
        
        <br />

	</td>
</tr>

</table>

</body>
</html>