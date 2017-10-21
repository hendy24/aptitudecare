{setTitle title="Add a Case Manager"}
<script src="{$SITE_URL}/js/jquery-validation-1.12.0/dist/jquery.validate.min.js"></script>
<script src="{$SITE_URL}/js/form-validation.js"></script>

{jQueryReady}
	$("#hospital_name").autocomplete({
		minLength: 4,
		source: function(req, add) {
			$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term, state: $("#state").val()}, function (json) {
				var suggestions = [];
				$.each (json, function(i, val) {
					console.log($("#state").val());
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

<div>
	<h1 class="text-center">Add a new Case Manager</h1>
	<br />
	<br />
	<form name="case_manager" id="addItem" method="post" action="{$SITE_URL}">
		<input type="hidden" name="page" value="caseManager" />
		{if $isMicro}
			<input type="hidden" name="action" value="addShadowboxCaseManager" />
		{else}
			<input type="hidden" name="action" value="addCaseManager" />
		{/if}
		<table  id="edit-data" cellspacing="5" cellpadding="3">
			<tr id="add-cm">
				<td><label for="first_name">First Name</label></td>
				<td><label for="last_name">Last Name</label></td>
			</tr>
			
			<tr>
				<td><input type="text" name="first_name" id="first_name"/></td>
				<td><input type="text" name="last_name" id="last_name" /></td>
			</tr>
			<tr>
				<td><label for="hospital_name">Healthcare Facility</label></td>
			</tr>
			<tr>
				<td><input type="text" id="hospital_name" name="location_name" style="width: 232px;" size="30" /></td>
				<input type="hidden" name="state" id="state" value="{$state}" />
				<input type="hidden" name="hospital" id="hospital" />
			</tr>
			<tr>
				<td><label for="phone">Phone</label></td>
				<td><label for="fax">Fax</label></td>
			</tr>
			<tr>
				<td><input type="text" name="phone" class="phone" /></td>
				<td><input type="text" name="fax" class="phone" /></td>
			</tr>
			<tr>
				<td><label for="email">Email</email></td>
			</tr>
			<tr>
				<td><input type="text" name="email" /></td>
			</tr>
			<tr>
				<td colspan="3"><input type="submit" id="submit" value="Save" class="right" /></td>
			</tr>

		</table>
	</form>
</div>