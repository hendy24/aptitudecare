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

        Medical records have been uploaded for the patient {$schedule->getPatient()->fullName()}.<br />
        {$room = $schedule->getRoom()}
        <strong>Room:</strong> {if $room->valid() == false}Not specified.{else}{$room->number}{/if}<br />
        
        
        <br />
        Please click for full details:<br />
        {$SITE_URL}/?page=patient&action=notes&schedule={$schedule->pubid}
        
        <br />

	</td>
</tr>

</table>

</body>
</html>