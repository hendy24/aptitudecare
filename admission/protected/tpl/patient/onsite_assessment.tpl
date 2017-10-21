{setTitle title="On-Site Assessment"}
{jQueryReady}
$("#discharge-facility-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.name + " (" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#discharge-location-id").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

$("#disposition").change(function() {
	var selected = $(this).val();
	if (selected == 'other_location') {
		$("#discharge-location").show();
	} else {
		$("#discharge-location").hide();
	}
});

{/jQueryReady}


{$options = [
	"admitting" => "Agreeable to admission",
	"other_location" => "Discharging to another facility",
	"home" => "Discharging home",
	"other" => "Other"
]}

<div class="on-site">
	<h1 class="text-center">On-Site Assessment<br />
	<span class="text-16">for {$patient->fullName()}</span></h1>
	
	<form name="onsite" method="post" action="{$SITE_URL}" id="inquiry-form"> 
		<input type="hidden" name="page" value="patient" />
		<input type="hidden" name="action" value="submitOnsiteAssessment" />
		<input type="hidden" name="id" value="{if $onsite_visit != ''}1{else}0{/if}" />
		<input type="hidden" name="patient_admit" value="{$patient->pubid}" />
		<input type="hidden" name="schedule" value="{$schedule->pubid}" />
		<input type="hidden" name="_path" value="{urlencode(currentURL())}" />
		<table cellpadding="5">
			<tr>
				<td><strong>Facility:</strong></td>
				{$facility = CMS_Facility::generate()}
				{$facility->load($schedule->facility)}
				<td>{$facility->name}</td>
			</tr>
			<tr>
				<td><strong>Patient Visited At:</strong></td>
				<td><input type="text" name="visit_location" /></td>
			</tr>
			<tr>
				<td><strong>Initial Visit:</strong></td>
				<td><input type="text" id="datetime_visit" name="datetime_visit" class="datetime-picker" /></td>
			</tr>
			<tr>
				<td><strong>Disposition:</strong></td>
				<td>
					<select id="disposition" name="disposition">
						<option value="">Select a disposition...</option>
						{foreach $options as $k => $o}
							<option value="{$k}">{$o}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			{jQueryReady}
				$("#discharge-location").hide();
			{/jQueryReady}
			
			<tr id="discharge-location">
				<td align="right"><strong>Facility Name:</strong></td>
				<td colspan="2">
					<input size="40px" type="text" id="discharge-facility-search" value="{$dl->name}" />
					<input type="hidden" name="discharge_location_id" id="discharge-location-id" />
				</td>
			</tr>
			<tr>
				<td valign="top"><strong>Notes:</strong></td>
				<td><textarea name="comments" rows="5" cols="80"></textarea></td>
			</tr>
			<tr>
				<td colspan="2" align="right"><input id="submit-button" type="submit" value="Submit" />
			</tr>
		</table>


		{if $onsite_visit != ''}
			{$d = 1}
			{foreach $onsite_visit as $ov}
				<br />
				<br />
				{if $d == 1}
					<h2>First Visit</h2>
				{/if}
				{if $d == 2}
					<h2>Second Visit</h2>
				{/if}
				{if $d == 3}
					<h2>Third Visit</h2>
				{/if}
				{if $d == 4}
					<h2>Fourth Visit</h2>
				{/if}
				<table>
					<tr>
						<td width="125px"><strong>Patient Visited At:</strong></td>
						<td>{$ov->visit_location}</td>
					</tr>
					<tr>
						<td><strong>Initial Visit:</strong></td>
						<td>{$ov->datetime_visit|date_format: "%m/%d/%Y %l:%m %P"}</td>
					</tr>
					<tr>
						<td><strong>Disposition:</strong></td>
						{foreach $options as $k => $o}
							{if $ov->disposition == $k}
								<td>{$o}</td>
							{/if}
						{/foreach}
					</tr>
					{if $ov->disposition == 'other_location'}
						{$loc = CMS_Hospital::generate()}
						{$loc->load($ov->discharge_location)}
						<tr>
							<td><strong>Discharge Facility: &nbsp;</strong></td>
							<td>{$loc->name}</td>
						</tr>
					{/if}
					<tr>
						<td valign="top"><strong>Notes:</strong></td>
						<td class="note">{$ov->comments}</td>
					</tr>
					<tr>
						<td><strong>Visited By:</strong></td>
						{$user = CMS_Site_User::generate()}
						{$user->load($ov->site_user_visited)}
						<td>{$user->fullName()}</td>
					</tr>
				</table>
				<span style="display: none;">{$d++}</span>
				<hr style="width: 75%;" />
			{/foreach}

	{/if}
</div>	