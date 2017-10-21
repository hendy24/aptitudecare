{jQueryReady}
	$("#cancel").click(function() {
		var answer = confirm("Are you sure you want to cancel this pending transfer?  This will remove the patient from this page.");
		if (answer) {
			document.messages.submit();
		}
		
		return false;
	});
{/jQueryReady}

<h1 class="text-center">Pending Transfers</h1>

<table cellpadding="5" cellspacing="0">
	<tr>
		<th>Patient Name</th>
		<th>&nbsp;</th>
		<th>Admission Date</th>
		<th>Transfer From</th>
		<th>Transfer To</th>
		<th>Phone</th>
<!-- 		<th>&nbsp;</th> -->
		<th>&nbsp;</th>
	</tr>
	<tr>
		{foreach $pendingTransfers as $pending}
			{foreach $pending as $p}
			{$schedule = CMS_Schedule::generate()}
			{$schedule->load($p->pubid)}
			<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
				<td>{$p->last_name}, {$p->first_name}</td>
				<td>{scheduleMenu schedule=$schedule}</td>
				<td>{$p->datetime_admit|date_format: 'm/d/Y'}</td>
				<td>{$p->transfer_from}</td>
				<td>{$p->transfer_to}</td>
				<td>{$p->phone}</td>
<!-- 				<td><a href="{$SITE_URL}/?page=coord&action=approve_transfer&schedule={$p->pubid}" class="button">Approve</a></td> -->
				<td><a href="{$SITE_URL}/?page=coord&action=cancel_transfer&schedule={$p->pubid}" id="cancel" class="button">Cancel Transfer Request</a></td>
			</tr>
			{/foreach}
		{/foreach}
	</tr>