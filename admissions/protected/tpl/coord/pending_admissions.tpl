{setTitle title="Pending Admissions"}

{jQueryReady}
$("#facility").change(function(e) {
	window.location.href = SITE_URL + '/?page=coord&action=pending_admissions&facility=' + $("option:selected", this).val() + '&status={$selectedStatus}';
});

$(".pending-facility").change(function(e) {
	$.getJSON(SITE_URL , { page: 'coord', action: 'setScheduleFacility', schedule: $(this).attr("rel"), facility: $("option:selected", this).val() }, function(json) {
		if (json.status == true) {
			jAlert("The patient has been successfully moved", "Success!", function(r) {
				window.parent.location.href = SITE_URL + '/?page=coord&action=pending_admissions&facility={$facility->pubid}';
			});
		} else {
			var msg = '';
			$.each(json.errors, function(i, v) {
				msg = msg + v + '\n';
			});
			jAlert(msg, "Error");
		}
	}, "json");
		//
});

$("#status").change(function(e) {
	window.location.href = SITE_URL + '/?page=coord&action=pending_admissions&facility={$facility->pubid}&status=' + $("option:selected", this).val();
});

$("#view-time").change(function(e) {
	window.location.href = SITE_URL + '/?page=coord&action=pending_admissions&facility={$facility->pubid}&status={$status}&timeframe=' + $("option:selected", this).val();
});

$(".schedule-datetime").datetimepicker({
	showOn: "button",
	buttonImage: "{$ENGINE_URL}/images/icons/calendar.png",
	buttonImageOnly: true,
	ampm: true,
	hour: 12,
	minute: 45,
	onClose: function(dateText, inst) {
		location.href = SITE_URL + '/?page=coord&action=setScheduleDatetimeAdmit&id=' + inst.input.attr("rel") + '&datetime=' + dateText + '&path={urlencode(currentURL())}';
	}
	
});



{/jQueryReady}


{$status = [
	'Under Consideration' => "Under Consideration",
	'Approved' => "Approved",
	'Cancelled' => "Cancelled"
]}
{if ($selectedStatus == 'Under Consideration' || $selectedStatus == '')}
	<h1 class="text-center">Pending Admissions {if $facility != ''}<br /><span class="text-16">for {$facility->name}</span>{/if}</h1>
{elseif $selectedStatus == 'Approved'}
	<h1 class="text-center">Approved Admissions {if $facility != ''}<br /><span class="text-16">for {$facility->name}</span>{/if}</h1>
{elseif $selectedStatus == 'Cancelled'}
	<h1 class="text-center">Cancelled Inquiries {if $facility != ''}<br /><span class="text-16">for {$facility->name}</span>{/if}</h1>
{/if}



<select id="facility">
	<option value="">Please Select a facility&nbsp;&nbsp;</option>
	{foreach $facilities as $f}
		<option value="{$f->pubid}"{if $f->id == $facility->id} selected{/if}>{$f->name}</option>
	{/foreach}
</select>
{if $facility != ''}
	<div class="sort-right">
		<select id="status">
			<option value="">Filter by Patient Status:</option>
			{foreach $status as $k => $s}
				<option value="{$k}"{if $selectedStatus == $k} selected{/if}>{$s}</option>
			{/foreach}
		</select>
	</div>
{/if}
<br />
<br />
{if $selectedStatus == 'Under Consideration'}
	<p class="text-14 text-center">There are <strong>{$countAdmits}</strong> currently <strong>pending admissions</strong></p>
{elseif $selectedStatus == 'Approved'}
	<p class="text-14 text-center">There were <strong>{$countAdmits}</strong> admissions within the last 30 days</p>
{elseif $selectedStatus == 'Cancelled'}
	<p class="text-14 text-center">There were <strong>{$countAdmits}</strong> cancelled inquiries within the last 30 days</p>
{/if}

<div id="pending-admissions">
	{if $facility == ''}
		<p class="text-center text-14">Select a facility from the drop-down above to view the pending admissions for that location.</p>
	{else}
		<table id="under-consideration-table" cellpadding="0" cellspacing="0">
			<tr>
				<th style="text-align: center;">Name</th>
				<th style="text-align: center;">Date to Admit</th>
				<th style="text-align: center;">Facility</th>
				<th style="text-align: center;">Admit From</th>
				<th style="text-align: center;">On-Site Assessment</th>
				<th style="text-align: center;">Assessment By</th>
				<th></th>
				<th></th>
			</tr>
		
			{foreach $pendingAdmits as $pa}
				{$admitFrom = CMS_Hospital::generate()}
				{$admitFrom->load($pa->admit_from)}
				{$hospital = CMS_Hospital::generate()}
				{$hospital->load($pa->hospital_id)}
				{$onsiteVisit = CMS_Onsite_Visit::generate()}
				{$onsite = $onsiteVisit->fetchVisitInfo($pa->id)}
				{$msg = array()}
				<tr bgcolor="{if !$pa->referral}{cycle values="#d1d1d1,#f1f1f1"}{else}{cycle values="#d0e2f0,#ffffff"}{/if}">
					<td id="patient-name-{$pa->pubid}"><a href="{$SITE_URL}/?page=patient&amp;action=inquiry&amp;schedule={$pa->pubid}&amp;mode=edit">{$pa->getPatient()->fullName()}</a></td>
					<td>{$pa->datetime_admit|date_format:"%m/%d/%Y %I:%M %P"} {if $selectedStatus == 'Under Consideration'}<input type="hidden" class="schedule-datetime" rel="{$pa->pubid}" value="{$pa->datetime_admit|date_format:"%m/%d/%Y %I:%M %P"}" />{/if}</td>
					<td>
					<select class="pending-facility" rel="{$pa->pubid}">
					{foreach $facilities as $f}
						<option value="{$f->pubid}"{if $f->id == $pa->facility} selected{/if}>{$f->getTitle()}</option>
					{/foreach}
					{* {$pa->getFacility()->getTitle()}*}
					</td>
					<td>{if $pa->hospital_id != ''}{$hospital->name}{else}{$admitFrom->name}{/if}</td>
					<td style="text-align: center;">
						{foreach $onsite as $o}
							{if $o->id != ''}<img src="{$PUBLIC_URL}/images/icons/check.png" style="height: 18px;" />{/if}
						{/foreach}
					</td>
					<td>
						{foreach $onsite as $o}
							{$user = CMS_Site_User::generate()}
							{$user->load($o->site_user_visited)}
							{$user->fullName()}
						{/foreach}
					</td>
					<td>{scheduleMenu schedule=$pa}</td>
				</tr>
			{/foreach}
		
		</table>
	{/if}
</div>

