<style>
	table.diet-form{
		width:100%;
		margin:none;
	}

</style>
{literal}

<script>
	$(document).ready(function() {
		var snackTime = null;
		var thisFieldName = null;

		startTag = function(category){

			$("#" + category).tagit({
				fieldName: category + "[]",
				//availableTags: fetchOptions(fetchOption),
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

		var tagOptions = ["adaptEquip", "allergies", "dislikes", "breakfast_beverages", "lunch_beverages", "dinner_beverages", "supplements", "breakfast_specialrequest", "lunch_specialrequest", "dinner_specialrequest"];

		$.each(tagOptions, function(index, value) {
			startTag(value);
		});

		// for (category of tagOptions){
		// 		startTag(category);
		// }

		//startTag("adaptEquip");


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

	$("#adaptEquip").tagit({
		fieldName: "adaptEquip[]",
	    availableTags: fetchOptions("AdaptEquip"),
	    autocomplete: {delay: 0, minLength: 2},
        showAutocompleteOnFocus: false,
        caseSensitive: false,
        allowSpaces: true,

        beforeTagRemoved: function(event, ui) {
        // if tag is removed, need to delete from the db
        var patientId = $("#patient-id").val();
        var adaptEquipName = ui.tagLabel;
        $.post(SITE_URL, {
        	page: "PatientInfo",
        	action: "deleteItem",
        	patient: patientId,
        	name: adaptEquipName,
        	type: "adapt_equip"
        	}, function (e) {
        		console.log(e);
        	}, "json"
        );
	    }
    });


	$("#supplements").tagit({
		fieldName: "supplements[]",
	    availableTags: fetchOptions("Supplement"),
	    autocomplete: {delay: 0, minLength: 2},
        showAutocompleteOnFocus: false,
        caseSensitive: false,
        allowSpaces: true,

        beforeTagRemoved: function(event, ui) {
        // if tag is removed, need to delete from the db
        var patientId = $("#patient-id").val();
        var supplementName = ui.tagLabel;
        console.log(patientId);
        $.post(SITE_URL, {
        	page: "PatientInfo",
        	action: "deleteItem",
        	patient: patientId,
        	name: supplementName,
        	type: "supplement"
        	}, function (e) {
        		console.log(e);
        	}, "json"
        );
	    }
    });


    $("#breakfast_specialrequest").tagit({
    	fieldName: "breakfast_specialrequest[]",
    	availableTags: fetchOptions("SpecialReq"),
    	autocomplete: {delay:0, minLength: 2},
    	showAutocompleteOnFocus: false,
    	caseSensitive: false,
    	allowSpaces: true,
	    beforeTagRemoved: function(event, ui) {
	        // if tag is removed, need to delete from the db
	        var patientId = $("#patient-id").val();
	        var specialRequestName = ui.tagLabel;
	        $.post(SITE_URL, {
	        	page: "PatientInfo",
	        	action: "deleteItem",
	        	patient: patientId,
	        	name: specialRequestName,
	        	meal: 1,
	        	type: "special_request"
	        	}, function (e) {
	        		console.log(e);
	        	}, "json"
	        );
	    }

    });

    $("#lunch_specialrequest").tagit({
    	fieldName: "lunch_specialrequest[]",
    	availableTags: fetchOptions("SpecialReq"),
    	autocomplete: {delay:0, minLength: 2},
    	showAutocompleteOnFocus: false,
    	caseSensitive: false,
    	allowSpaces: true,
	    beforeTagRemoved: function(event, ui) {
	        // if tag is removed, need to delete from the db
	        var patientId = $("#patient-id").val();
	        var specialRequestName = ui.tagLabel;
	        $.post(SITE_URL, {
	        	page: "PatientInfo",
	        	action: "deleteItem",
	        	patient: patientId,
	        	name: specialRequestName,
	        	meal: 2,
	        	type: "special_request"
	        	}, function (e) {
	        		console.log(e);
	        	}, "json"
	        );
	    }

    });

    $("#dinner_specialrequest").tagit({
    	fieldName: "dinner_specialrequest[]",
    	availableTags: fetchOptions("SpecialReq"),
    	autocomplete: {delay:0, minLength: 2},
    	showAutocompleteOnFocus: false,
    	caseSensitive: false,
    	allowSpaces: true,
	    beforeTagRemoved: function(event, ui) {
	        // if tag is removed, need to delete from the db
	        var patientId = $("#patient-id").val();
	        var specialRequestName = ui.tagLabel;
	        $.post(SITE_URL, {
	        	page: "PatientInfo",
	        	action: "deleteItem",
	        	patient: patientId,
	        	name: specialRequestName,
	        	meal: 3,
	        	type: "special_request"
	        	}, function (e) {
	        		console.log(e);
	        	}, "json"
	        );
	    }

    });


    $("#breakfast_beverages").tagit({
    	fieldName: "breakfast_beverages[]",
    	availableTags: fetchOptions("Beverage"),
    	autocomplete: {delay:0, minLength: 2},
    	showAutocompleteOnFocus: false,
    	caseSensitive: false,
    	allowSpaces: true,
	    beforeTagRemoved: function(event, ui) {
	        // if tag is removed, need to delete from the db
	        var patientId = $("#patient-id").val();
	        var beverageName = ui.tagLabel;
	        $.post(SITE_URL, {
	        	page: "PatientInfo",
	        	action: "deleteItem",
	        	patient: patientId,
	        	name: beverageName,
	        	type: "beverage",
	        	meal: 1
	        	}, function (e) {
	        		console.log(e);
	        	}, "json"
	        );
	    }

    });

    $("#lunch_beverages").tagit({
    	fieldName: "lunch_beverages[]",
    	availableTags: fetchOptions("Beverage"),
    	autocomplete: {delay:0, minLength: 2},
    	showAutocompleteOnFocus: false,
    	caseSensitive: false,
    	allowSpaces: true,
	    beforeTagRemoved: function(event, ui) {
	        // if tag is removed, need to delete from the db
	        var patientId = $("#patient-id").val();
	        var beverageName = ui.tagLabel;
	        $.post(SITE_URL, {
	        	page: "PatientInfo",
	        	action: "deleteItem",
	        	patient: patientId,
	        	name: beverageName,
	        	type: "beverage",
	        	meal: 2
	        	}, function (e) {
	        		console.log(e);
	        	}, "json"
	        );
	    }

    });


    $("#dinner_beverages").tagit({
    	fieldName: "dinner_beverages[]",
    	availableTags: fetchOptions("Beverage"),
    	autocomplete: {delay:0, minLength: 2},
    	showAutocompleteOnFocus: false,
    	caseSensitive: false,
    	allowSpaces: true,
	    beforeTagRemoved: function(event, ui) {
	        // if tag is removed, need to delete from the db
	        var patientId = $("#patient-id").val();
	        var beverageName = ui.tagLabel;
	        $.post(SITE_URL, {
	        	page: "PatientInfo",
	        	action: "deleteItem",
	        	patient: patientId,
	        	name: beverageName,
	        	type: "beverage",
	        	meal: 3
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


<h1>Edit Diet <span class="text-24">for</span> {$patient->fullName()}</h1>


<form class="form-inline" action="{$SITE_URL}" method="post">
	<input type="hidden" name="page" value="PatientInfo" />
	<input type="hidden" name="action" value="saveDiet" />
	<input type="hidden" id="patient-id" name="patient" value="{$patient->public_id}" />
	<input type="hidden" name="path" value="{$current_url}" />


	<!-- Patient Info Section -->
	<div class="form-header">
		Patient Info
	</div>
	<div class="form-group">
		<label for="first-name" class="col-form-label col-2">First Name:</label>
		<input type="text" id="first-name" class="form-control" name="first_name" value="{$patient->first_name}">
	</div>

	<div class="form-group">
		<label for="middle-name" class="col-form-label col-2">Middle Name:</label>
		<input type="text" id="middle-name" class="form-control" size="10" name="middle_name" value="{$patient->middle_name}">
	</div>
	<div class="form-group">
		<label for="last-name" class="col-form-label col-2">Last Name:</label>
		<input type="text" id="last-name" class="form-control" name="last_name" value="{$patient->last_name}">
	</div>
	<div class="form-group">
		<label for="last-name" class="col-form-label col-2">Birthdate:</label>
		<input type="text" class="form-control datepicker" size="10" name="date_of_birth" value="{display_date($patient->date_of_birth)}" />
	</div>


	<!-- Diet Info Section -->
	<div class="form-header">
		Diet Info
	</div>
	<div class="form-group">
		<label for="food-allergies">Food Allergies:</label>
		<ul id="allergies">
			{if $allergies}
				{foreach from=$allergies item=allergy}
				<li>{$allergy->name}</li>
				{/foreach}
			{/if}
		</ul>
	</div>

	<!-- Food Dislikes or Intolerances section -->
	<div class="form-group">
		<label for="food-dislikes">Food dislikes or intolerances:</label>
		<ul id="dislikes">
			{if $dislikes}
				{foreach from=$dislikes item=dislike}
				<li value="{$dislike->id}">{$dislike->name}</li>
				{/foreach}
			{/if}
		</ul>
	</div>

	<!-- Adaptive Equipment Section -->
	<div class="form-group">
		<label for="adaptive-equipment">Adaptive Equipment:</label>
		<ul id="adaptEquip">
			{if $adaptEquip}
				{foreach from=$adaptEquip item=equip}
				<li value="{$equip->id}">{$equip->name}</li>
				{/foreach}
			{/if}
		</ul>
	</div>

	<!-- Supplements Section -->
	<div class="form-group">
		<label for="supplements">Supplements:</label>
		<ul id="supplements">
			{if $supplements}
				{foreach from=$supplements item=supplement}
				<li value="{$supplement->id}">{$supplement->name}</li>
				{/foreach}
			{/if}
		</ul>
	</div>


	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<!-- Special Requests Section -->
		<div class="panel panel-default">
	    	<div class="panel-heading" role="tab" id="headingOne">
	    		<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="text-decoration:none;color:#000">
	      			<h4 class="panel-title">Special Requests</h4>
	      		</a>
	    	</div>
	    	<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
	      		<div class="panel-body">
	        		<div class="col-md-1">Breakfast:</div>
	        		<div class="col-md-3">
						<ul id="breakfast_specialrequest">
							{if $breakfast_spec_req}
							{foreach from=$breakfast_spec_req item=req}
							<li value="{$req->id}">{$req->name}</li>
							{/foreach}
							{/if}
						</ul>
	        		</div>
	        		<div class="col-md-1">Lunch:</div>
	        		<div class="col-md-3">
						<ul id="lunch_specialrequest">
							{if $lunch_spec_req}
							{foreach from=$lunch_spec_req item=req}
							<li value="{$req->id}">{$req->name}</li>
							{/foreach}
							{/if}
						</ul>
	        		</div>
	        		<div class="col-md-1">Dinner:</div>
	        		<div class="col-md-3">
						<ul id="dinner_specialrequest">
							{if $dinner_spec_req}
							{foreach from=$dinner_spec_req item=req}
							<li value="{$req->id}">{$req->name}</li>
							{/foreach}
							{/if}
						</ul>
	        		</div>
	      		</div>
	    	</div>


	    <!-- Beverages Section -->

		<!-- Beverages Section -->
		<div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingTwo">
		    	<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" style="text-decoration:none;color:#000">
		      		<h4 class="panel-title">
		      			Beverages
		      		</h4>
		      	</a>
		    </div>
		    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		      <div class="panel-body">
		        <div class="col-md-1">
		        	Breakfast:
		        </div>
		        <div class="col-md-3">
					<ul id="breakfast_beverages">
						{if $breakfast_beverages}
							{foreach from=$breakfast_beverages item=beverage}
							<li>{$beverage->name}</li>
							{/foreach}
						{/if}
					</ul>
		        </div>
		        <div class="col-md-1">
		        	Lunch:
		        </div>
		        <div class="col-md-3">
					<ul id="lunch_beverages">
						{if $lunch_beverages}
							{foreach from=$lunch_beverages item=beverage}
							<li>{$beverage->name}</li>
							{/foreach}
						{/if}
					</ul>
		        </div>
		        <div class="col-md-1">
		        	Dinner:
		        </div>
		        <div class="col-md-3">
					<ul id="dinner_beverages">
						{if $dinner_beverages}
							{foreach from=$dinner_beverages item=beverage}
							<li>{$beverage->name}</li>
							{/foreach}
						{/if}
					</ul>
		        </div>
		    </div>
	  	</div>

		<!-- Snacks drop down section -->
	  	<div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingThree">
		    	<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="text-decoration:none;color:#000">
		    	<h4 class="panel-title">Snacks
		    	</h4>
		     	</a>
		    </div>
		    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		      <div class="panel-body">
		      	<div class="col-md-1">
		      		AM
	      		</div>
	      		<div class="col-md-3">
					<ul id="snackAM">
						{if $am_snacks}
							{foreach from=$am_snacks item=snack}
							<li>{$snack->name}</li>
							{/foreach}
						{/if}
					</ul>
	      		</div>
	      		<div class="col-md-1">
	      			PM
	      		</div>
	      		<div class="col-md-3">
					<ul id="snackPM">
						{if $pm_snacks}
							{foreach from=$pm_snacks item=snack}
							<li>{$snack->name}</li>
							{/foreach}
						{/if}
					</ul>
	      		</div>
	      		<div class="col-md-1">
	      			Bedtime
	      		</div>
	      		<div class="col-md-3">
					<ul id="snackBedtime">
						{if $bedtime_snacks}
							{foreach from=$bedtime_snacks item=snack}
							<li>{$snack->name}</li>
							{/foreach}
						{/if}
					</ul>
	      		</div>
		      </div>
		    </div>
	  	</div>
	</div>
</div>


	<!-- Diet Order Section-->
	<div class="form-header2">Diet Order</div>
	<div class="checkbox">
		<label for="regular" class="checkbox-label">
			<input id="regular" class="checkbox" type="checkbox" name="diet_order[]" value="Regular" {if in_array("Regular", $dietOrder['standard'])} checked{/if}>
			Regular
		</label>
		<label for="aha-cardiac" class="checkbox-label">
			<input id="aha-cardiac" type="checkbox" name="diet_order[]" value="AHA/Cardiac" {if in_array("AHA/Cardiac", $dietOrder['standard'])} checked{/if}>
			AHA/Cardiac
		</label>
		<label class="checkbox-label">
			<input type="checkbox" name="diet_order[]" value="No Added Salt" {if in_array("No Added Salt", $dietOrder['standard'])} checked{/if}>
			No Added Salt
		</label>
		<label class="checkbox-label">
			<input type="checkbox" name="diet_order[]" value="Renal" {if in_array("Renal", $dietOrder['standard'])} checked{/if}>
			Renal
		</label>
		<label class="checkbox-label">
			<input type="checkbox" name="diet_order[]" value="2 gram Na" {if in_array("2 gram Na", $dietOrder['standard'])} checked{/if}>
			2 gram Na
		</label>
		<label class="checkbox-label">
			<input type="checkbox" name="diet_order[]" value="Fortified/High Calorie" {if in_array("Fortified/High Calorie", $dietOrder['standard'])} checked{/if}>
			Fortified/High Calorie
		</label>
		<label class="checkbox-label">
			<input type="checkbox" name="diet_order[]" value="RCS" {if in_array("RCS", $dietOrder['standard'])} checked{/if}>
			RCS
		</label>
		<input type="text" name="diet_order[]" class="other-input checkbox-input" placeholder="Enter other diet orders..." style="width: 350px" value="{$dietOrder['other']}">
	</div>


	<!-- Texture Section -->
	<div class="form-header2">Texture</div>
	<div class="checkbox">
		<label for="" class="checkbox-label">
			<input type="checkbox" name="texture[]" value="Regular" {if in_array('Regular', $textures['standard'])} checked{/if}>
			Regular
		</label>
		<label for="" class="checkbox-label">
			<input type="checkbox" name="texture[]" value="Mechanical Soft" {if in_array('Mechanical Soft', $textures['standard'])} checked{/if}>
			Mechanical Soft
		</label>
		<label for="" class="checkbox-label">
			<input type="checkbox" name="texture[]" value="Puree" {if in_array('Puree', $textures['standard'])} checked{/if}>
			Puree
		</label>
		<label for="" class="checkbox-label">
			<input type="checkbox" name="texture[]" value="Tube Feeding" {if in_array('Tube Feeding', $textures['standard'])} checked{/if}>
			Tube Feeding
		</label>
		<label for="liquid" class="checkbox-label">Liquid:</label>
		<select name="texture[]" id="">
			<option value="">Select Liquid Type...</option>
			<option value="Nectar Thick Liquids" {if in_array("Nectar Thick Liquids", $textures['standard'])} selected{/if}>Nectar Liquid</option>
			<option value="Honey Thick Liquids" {if in_array("Honey Thick Liquids", $textures['standard'])} selected{/if}>Honey Liquid</option>
			<option value="Pudding Thick Liquids" {if in_array("Pudding Thick Liquids", $textures['standard'])} selected{/if}>Pudding Liquid</option>
			<option value="Clear Liquid" {if in_array("Clear Liquid", $textures['standard'])} selected{/if}>Clear Liquid</option>
			<option value="Full Liquid" {if in_array("Full Liquid", $textures['standard'])} selected{/if}>Full Liquid</option>
			<option value="Fluid Restriction" {if in_array("Fluid Restriction", $textures['standard'])} selected{/if}>Fluid Restriction</option>
		</select>
		<input type="text" maxlength="25" name="texture[]" size="50" class="other-input" placeholder="Enter other texture info... (25 character limit)" value="{$textures['other']}">
	</div>

	<!-- Other Section -->
	<div class="form-header2">Other</div>
		<label for="" class="checkbox-label">
			<input type="checkbox" name="other[]" value="Isolation" {if in_array("Isolation", $other['standard'])} checked{/if}>
			Isolation
		</label>
		<label for="" class="checkbox-label">
			<input type="checkbox" name="other[]" value="Fluid Restriction" {if in_array("Fluid Restriction", $other['standard'])} checked{/if}>
			Fluid Restriction
		</label>
		<label for="" class="checkbox-label">
			<input type="text" name="other[]" class="other-input" placeholder="Enter other order info..." value="{$other['other']}">
		</label>

	<!-- Portion Size Section -->
		<label for="" class="checkbox-label">
			<input type="radio" name="portion_size" value="Small" {if $patientInfo->portion_size == "Small"} checked{/if}>
			Small
		</label>
		<label for="" class="checkbox-label">
			<input type="radio" name="portion_size" value="Regular" {if $patientInfo->portion_size == "Regular"} checked{elseif $patientInfo->portion_size == "Medium"} checked{elseif !isset($patientInfo->portion_size)} checked{/if}>
			Regular
		</label>
		<label for="" class="checkbox-label">
			<input type="radio" name="portion_size" value="Large" {if $patientInfo->portion_size == "Large"} checked{/if}>
			Large
		</label>
	</div>

	<br>
	<br>
	<div class="text-right">
		<input type="submit" class="btn btn-info" value="Save">
	</div>

</form>


<!-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script> -->
