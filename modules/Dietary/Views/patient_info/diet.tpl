{literal}
<script>
	$(document).ready(function() {
		var snackTime = null;
		var thisFieldName = null;

		// $(".other-input").hide();

		startTag = function(category){
			$("#" + category).tagit({
				fieldName: category + "[]",
				availableTags: fetchOptions(category),
				autocomplete: {delay: 0, minLength: 2},
				showAutocompleteOnFocus: false,
				caseSensitive: false,
				allowSpaces: true,
				beforeTagRemoved: function(event, ui){
					var patientId = $("patient-id").val();
					var Name = ui.tagLabel;
					$.post(SITE_URL, {
						page: "PatientInfo",
						action: "deleteItem",
						patient: patientId,
						name: name,
						type: category
						}, function (e) {
							console.log(e);
					}, "json");
				}
			});
		}

		var tagOptions = ["adaptEquip", "allergies", "dislikes"]
/*		for (category of tagOptions){
				startTag(category);
		}*/

		startTag("adaptEquip");


		$("#allergies").tagit({
			fieldName: "allergies[]",
		    availableTags: fetchOptions("Allergy"),
		    autocomplete: {delay: 0, minLength: 2},
            showAutocompleteOnFocus: false,
            caseSensitive: false,
            allowSpaces: true,

            beforeTagRemoved: function(event, ui) {
		        // if tag is removed, need to delete from the db
		        var patientId = $("#patient-id").val();
		        var allergyName = ui.tagLabel;
		        $.post(SITE_URL, {
		        	page: "PatientInfo",
		        	action: "deleteItem",
		        	patient: patientId,
		        	name: allergyName,
		        	type: "allergy"
		        	}, function (e) {
		        		console.log(e);
		        	}, "json"
		        );
		    }
      });

        $("#dislikes").tagit({
        	fieldName: "dislikes[]",
        	availableTags: fetchOptions("Dislike"),
        	autocomplete: {delay:0, minLength: 2},
        	showAutocompleteOnFocus: false,
        	caseSensitive: false,
        	allowSpaces: true,

            beforeTagRemoved: function(event, ui) {
		        // if tag is removed, need to delete from the db
		        var patientId = $("#patient-id").val();
		        var dislikeName = ui.tagLabel;
		        $.post(SITE_URL, {
		        	page: "PatientInfo",
		        	action: "deleteItem",
		        	patient: patientId,
		        	name: dislikeName,
		        	type: "dislike"
		        	}, function (e) {
		        		console.log(e);
		        	}, "json"
		        );
		    }

        });

        $("#snackAM").tagit({
        	fieldName: "am[]",
        	availableTags: fetchOptions("Snack"),
        	autocomplete: {delay:0, minLength: 2},
        	showAutocompleteOnFocus: false,
        	caseSensitive: false,
        	allowSpaces: true,

            beforeTagRemoved: function(event, ui) {
		        // if tag is removed, need to delete from the db
		        var patientId = $("#patient-id").val();
		        var snackName = ui.tagLabel;
		        $.post(SITE_URL, {
		        	page: "PatientInfo",
		        	action: "deleteItem",
		        	patient: patientId,
		        	name: snackName,
		        	type: "snack",
		        	time: "am"
		        	}, function (e) {
		        		console.log(e);
		        	}, "json"
		        );
		    }
        });

        $("#snackPM").tagit({
        	fieldName: "pm[]",
        	availableTags: fetchOptions("Snack"),
        	autocomplete: {delay:0, minLength: 2},
        	showAutocompleteOnFocus: false,
        	caseSensitive: false,
        	allowSpaces: true,

            beforeTagRemoved: function(event, ui) {
		        // if tag is removed, need to delete from the db
		        var patientId = $("#patient-id").val();
		        var snackName = ui.tagLabel;
		        $.post(SITE_URL, {
		        	page: "PatientInfo",
		        	action: "deleteItem",
		        	patient: patientId,
		        	name: snackName,
		        	type: "snack",
		        	time: "pm"
		        	}, function (e) {
		        		console.log(e);
		        	}, "json"
		        );
		    }
        });

        $("#snackBedtime").tagit({
        	fieldName: "bedtime[]",
        	availableTags: fetchOptions("Snack"),
        	autocomplete: {delay:0, minLength: 2},
        	showAutocompleteOnFocus: false,
        	caseSensitive: false,
        	allowSpaces: true,

            beforeTagRemoved: function(event, ui) {
		        // if tag is removed, need to delete from the db
		        var patientId = $("#patient-id").val();
		        var snackName = ui.tagLabel;
		        $.post(SITE_URL, {
		        	page: "PatientInfo",
		        	action: "deleteItem",
		        	patient: patientId,
		        	name: snackName,
		        	type: "snack",
		        	time: "bedtime"
		        	}, function (e) {
		        		console.log(e);
		        	}, "json"
		        );
		    }
        });


        function fetchOptions(type){
        	var choices = "";
        	var array = [];
        	var runLog = function() {
        		array.push(choices);
        	};

        	var options = $.get(SITE_URL, {
        		page: "PatientInfo",
        		action: "fetchOptions",
        		type: type
        		}, function(data) {
        			$.each(data, function(key, value) {
        				choices = value.name;
        				runLog();
        			});
        		}, "json"
        	);

        	return array;
        }



	});
</script>
{/literal}


<h1>Edit Diet for {$patient->fullName()}</h1>

<form action="{$SITE_URL}" method="post" id="edit-diet">
	<input type="hidden" name="page" value="PatientInfo" />
	<input type="hidden" name="action" value="saveDiet" />
	<input type="hidden" id="patient-id" name="patient" value="{$patient->public_id}" />
	<input type="hidden" name="path" value="{$current_url}" />

	<br>
	<table class="diet-form">
		<tr>
			<th colspan="4">Patient Info</th>
		</tr>
		<tr>
			<td><strong>First:</strong></td>
			<td><strong>Middle:</strong></td>
			<td><strong>Last:</strong></td>
			<td><strong>Birthdate:</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="first_name" value="{$patient->first_name}"></td>
			<td><input type="text" name="middle_name" value="{$patient->middle_name}"></td>
			<td><input type="text" name="last_name" value="{$patient->last_name}" size="35"></td>
			<td><input type="text" class="datepicker" name="date_of_birth" value="{display_date($patient->date_of_birth)}" /></td>
		</tr>
		<!-- <tr>
			<td><strong>Birthdate:</strong></td>
			<td><strong>Height:</strong></td>
			<td><strong>Weight:</strong></td>
		</tr>
		<tr>
			<td><input type="text" class="datepicker" name="date_of_birth" value="{display_date($patient->date_of_birth)}" /></td>
			<td><input type="text" name="height" value="{$patientInfo->height}"  size="8"></td>
			<td><input type="text" name="weight" value="{$patientInfo->weight}" size="8"></td>
		</tr> -->
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<th colspan="4">Diet Info</th>
		</tr>
		<tr>
			<td><strong>Food Allergies:</strong></td>
			<td colspan="3" class="text-right">
				<ul id="allergies">
					{if $allergies}
						{foreach from=$allergies item=allergy}
						<li value="{$allergy->id}">{$allergy->name}</li>
						{/foreach}
					{/if}
				</ul>
			</td>
		</tr>
		<tr>
			<td><strong>Food dislikes or intolerances:</strong></td>
			<td colspan="3" class="text-right">
				<ul id="dislikes">
					{if $dislikes}
						{foreach from=$dislikes item=dislike}
						<li>{$dislike->name}</li>
						{/foreach}
					{/if}
				</ul>
			</td>
		</tr>
		<tr>
			<td><strong>Adaptative Equipment:</strong></td>
			<td colspan="3" class="text-right">
				<ul id="adaptEquip">
					{if $adaptEquip}
						{foreach from=$adaptEquip item=equip}
						<li>{$equip->name}</li>
						{/foreach}
					{/if}
				</ul>
			</td>
		</tr>

		<tr class="padding-top">
			<td colspan="4"><strong>Diet Info:</strong></td>
		</tr>
		<tr>
		{foreach from=$dietOrder item="diet" name="dietItem"}
			<td {if $diet->name == "Other"}colspan="3"{/if}>
				<input type="checkbox" name="diet_info[]" value="{$diet->name}" {if $diet->patient_id}checked{/if}>{$diet->name}
				{if $diet->name == "Other"}
					<input type="text" name="other_diet_info" class="other-input" placeholder="Enter other diet info..." style="width: 500px" value="{$patientInfo->diet_info_other}">
				{/if}
			</td>
		{if $smarty.foreach.dietItem.iteration is div by 4}
		</tr>
		<tr>
		{/if}
		{/foreach}
		</tr>


		<tr class="padding-top">
			<td colspan="4"><strong>Texture:</strong></td>
		</tr>
		<tr>
			{foreach from=$textures item="texture" name="textureItem"}
				<td {if $texture->name == "Other"}colspan="3"{/if}>
					<input type="checkbox" name="texture[]" value="{$texture->name}" {if $texture->patient_id}checked{/if}>{$texture->name}
					{if $texture->name == "Other"}
					<input type="text" name="other_texture_info" class="other-input" placeholder="Enter other texture info..." style="width: 500px" value="{$patientInfo->texture_other}">
					{/if}
				</td>
			{if $smarty.foreach.textureItem.iteration is div by 4}
			</tr>
			<tr>
			{/if}
			{/foreach}
		</tr>


		<tr class="padding-top">
			<td colspan="4" ><strong>Orders:</strong></td>
		</tr>
		<tr>
			{foreach from=$orders item="order" name="orderItem"}
				<td {if $order->name == "Other"}colspan="3"{/if}>
					<input type="checkbox" name="orders[]" value="{$order->name}" {if $order->patient_id}checked{/if}>{$order->name}
					{if $order->name == "Other"}
					<input type="text" name="other_orders_info" class="other-input" placeholder="Enter other order info..." style="width: 500px" value="{$patientInfo->orders_other}">
					{/if}

				</td>
			{if $smarty.foreach.orderItem.iteration is div by 4}
			</tr>
			<tr>
			{/if}
			{/foreach}
		</tr>



		<tr class="padding-top">
			<td colspan="4"><strong>Lunch &amp; Dinner Portion Size:</strong></td>
		</tr>
		<tr>
			{foreach from=$portionSize item="diet" name="dietItem"}
				<td><input type="radio" name="portion_size" value="{$diet}" {if $patientInfo->portion_size == $diet}checked{/if}> {$diet}</td>
			{/foreach}
		</tr>
		<tr class="padding-top">
			<td><strong>Special Requests:</strong></td>
			<td colspan="4" class="text-right"><input type="text" name="special_requests" size="100" value="{$patientInfo->special_requests}"></td>
		</tr>

		<tr>
			<td><strong>AM Snack</strong></td>
			<td><strong>PM Snack</strong></td>
			<td><strong>Bedtime Snack</strong></td>
		</tr>
		<tr>
			<td>
				<ul id="snackAM">
					{if $am_snacks}
						{foreach from=$am_snacks item=snack}
						<li>{$snack->name}</li>
						{/foreach}
					{/if}
				</ul>
			</td>
			<td>
				<ul id="snackPM">
					{if $pm_snacks}
						{foreach from=$pm_snacks item=snack}
						<li>{$snack->name}</li>
						{/foreach}
					{/if}
				</ul>
			</td>
			<td>
				<ul id="snackBedtime">
					{if $bedtime_snacks}
						{foreach from=$bedtime_snacks item=snack}
						<li>{$snack->name}</li>
						{/foreach}
					{/if}
				</ul>
			</td>
		</tr>

		<tr>
			<td colspan="4" class="text-right"><input type="submit" value="Save"></td>
		</tr>
	</table>

</form>
