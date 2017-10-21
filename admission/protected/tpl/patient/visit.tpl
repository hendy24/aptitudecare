{jQueryReady}

$("#physician-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'physician', action: 'searchPhysicians', term: req.term}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.last_name + ", " + val.first_name + " M.D. " + "(" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#physician").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

{/jQueryReady}

<h1 class="text-center">Record patient visit for {$patient->last_name}, {$patient->first_name}</h1>
<br />
<br />

<form name="visit" method="post" action="{$SITE_URL}" id="first-visit">
	<input type="hidden" name="page" value="patient" />
	<input type="hidden" name="action" value="submitVisit" />
	<input type="hidden" name="id" value="{$patient->pubid}" />
	<input type="hidden" name="schedule" value="{$schedule->pubid}" />
	<input type="hidden" name="_path" value="{urlencode(currentURL())}" />
	<table cellpadding="3" cellspacing="3">
		<tr>
			<td><strong>Admission Date:</strong></td>
			<td>{$schedule->datetime_admit|date_format}</td>
		</tr>
		<tr>
			<td><strong>Attending Physician:</strong></td>
			{if $patient->physician_id != ''}
				{$physician = CMS_Physician::generate()}
				{$physician->load($patient->physician_id)}
			{/if}
			{if $schedule->first_seen_by_id != ''}
				{$visitedBy = CMS_Physician::generate()}
				{$visitedBy->load($schedule->first_seen_by_id)}
			{/if}
			<td>{if $patient->physician_id != ''}{$physician->last_name}, {$physician->first_name} M.D.{else}{$patient->physician_name|Default:"Not Entered"}{/if}</td>
		</tr>
		<tr>
			<td><strong>Visited by:</strong></td>
			<td><input type="text" id="physician-search" value="{if $schedule->first_seen_by_id != ''}{$visitedBy->last_name}, {$visitedBy->first_name} M.D.{/if}" style="width: 232px;" valign="top" /> 
			<input type="hidden" name="physician" id="physician" />
</td>
		<tr>
			<td><strong>Date &amp; Time of First Visit:</strong></td>
			<td><input type="text" class="datetime-picker" name="datetime_first_seen" value="{$schedule->datetime_first_seen|date_format:"%D %I:%M %p"}" /></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><input type="submit" value="Submit" /></td>
		</tr>
	</table>
</form>