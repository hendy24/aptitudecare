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

        A patient has been scheduled for admission to {$facility->name} by {$trigger_user->getFullName()}.<br />
        {$room = $schedule->getRoom()}
        {$hospitalName = $schedule->getPatient()->hospitalName()}
        {$transProvider = $schedule->getPatient()->trans_provider}
		<strong>Who:</strong> {$schedule->getPatient()->fullName()}<br />
        <strong>Room:</strong> {if $room->valid() == false}No room scheduled yet.{else}{$room->number}{/if}<br />
        <strong>When:</strong> {$schedule->admitDatetimeFormatted()}<br />
        <strong>To be admitted from:</strong> {$hospitalName|default:"Not specified"}<br />
        <strong>Transportation will be provided by:</strong> {$transProvider|default:"Not specified"}<br />
        
        <br />
        Please click for full details:<br />
        {$SITE_URL}/?page=patient&action=inquiry&schedule={$schedule->pubid}
        
        <br />

	</td>
</tr>

</table>

</body>
</html>