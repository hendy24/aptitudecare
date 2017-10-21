{jQueryReady}
	$("#hospital-search").autocomplete({
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
			$("#hospital").val(ui.item.value);
			e.target.value = ui.item.label;		
		}
	});
	
	$(".phone").mask("(999) 999-9999");

{/jQueryReady}


<h1 class="text-center">Edit Case Manager</h1>

<form id="edit-case-manager" action="{$SITE_URL}" method="post">
	<input type="hidden" name="page" value="caseManager" />
	{if $isMicro}
		<input type="hidden" name="action" value="submitShadowboxEdit" />
	{else}
		<input type="hidden" name="action" value="submitEdit" />
	{/if}
	<input type="hidden" name="case_manager" value="{$cm->pubid}" />
	
	<table id="edit-data" cellpadding="5" cellspacing="5">
		<tr>
			<td><strong>First Name:</strong></td>
			<td><strong>Last Name:</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="first_name" id="first_name" value="{$cm->first_name}" /></td>
			<td><input type="text" name="last_name" id="last_name" value="{$cm->last_name}" /></td>
		</tr>
		<tr>
			<td colspan="2"><strong>Healthcare Facility</strong></td>
		</tr>
		<tr>
			{$hospital = CMS_Hospital::generate()}
			{$hospital->load($cm->hospital_id)}
			<td><input type="text" id="hospital-search" value="{$hospital->name}" style="width: 232px;" size="30" /></td>
			<input type="hidden" name="hospital" id="hospital" />
		</tr>
		<tr>
			<td><strong>Phone</strong></td>
			<td><strong>Fax</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="phone" class="phone" value="{$cm->phone}" /></td>
			<td><input type="text" name="fax" class="phone" value="{$cm->fax}" /></td>
		</tr>
		<tr>
			<td><strong>Email</strong></td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" name="email" size="50" value="{$cm->email}" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><a href="{$SITE_URL}/?page=caseManager&action=delete&case_manager={$cm->pubid}" id="deleteCM" class="button">Delete</a></td>
			<td><input type="submit" value="Save" class="right" /></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><a href="{$SITE_URL}/?page=caseManager&amp;action=manage" style="margin-right: 5px;">Cancel</a></td>
		</tr>
	</table>
</form>
