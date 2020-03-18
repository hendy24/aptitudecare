<div class="container">
	<h1>Edit Diet <span class="text-24">for</span> {$patient->fullName()}</h1>


	<form class="form" action="{$SITE_URL}" method="post">
		<input type="hidden" name="page" value="PatientInfo" />
		<input type="hidden" name="action" value="saveDiet" />
		<input type="hidden" id="patient-id" name="patient" value="{$patient->public_id}" />
		<input type="hidden" name="path" value="{$current_url}" />

		<div class="data-block">
			<div class="row">
				<h2>Patient Info</h2>
			</div>
			<div class="row">
				<div class="form-group col-md-3 col-sm-6">
					<label for="first-name">First Name:</label>
					<input type="text" id="first-name" class="form-control" name="first_name" value="{$patient->first_name}">
				</div>
				<div class="form-group col-md-3 col-sm-6">
					<label for="middle-name">Middle Name:</label>
					<input type="text" id="middle-name" class="form-control" name="middle_name" value="{$patient->middle_name}">
				</div>
				<div class="form-group col-md-4 col-sm-9">
					<label for="last-name"">Last Name:</label>
							<input type=" text" id="last-name" class="form-control" name="last_name" value="{$patient->last_name}">
				</div>
				<div class="form-group col-md-2 col-sm-3">
					<label for="last-name">Birthdate:</label>
					<input type="text" class="form-control datepicker" name="date_of_birth"
						value="{display_date($patient->date_of_birth)}" />
				</div>
			</div>
		</div>



		<!-- Diet Info Section -->
		<div class="data-block">
			<div class="row">
				<h2>Diet Info</h2>
			</div>
			<div class="form-group">
				<label for="food-allergies">Food Allergies:</label>
				<ul id="allergies">
					{if $allergies}
					{foreach from=$allergies item=allergy}
					<li>{$allergy->name}</li>
					{/foreach}
					{/if}
		<div class="form-header">
			Patient Info
		</div>

		<!-- Patient Info Section -->
		<div class="form-row">
			<div class="form-group col-lg-4 col-md-4 col-sm 12">
				<label for="first-name">First Name</label>
				<input type="text" id="first-name" class="form-control" name="first_name" value="{$patient->first_name}" placeholder="First Name">
			</div>
			<div class="form-group col-lg-6 col-md-6 col-sm-12">
				<label for="last-name">Last Name</label>
				<input type="text" id="last-name" class="form-control" name="last_name" value="{$patient->last_name}" placeholder="Last Name">
			</div>
			<div class="form-group col-lg-2 col-md-2 col-sm-12">
				<label for="birthdate">Birthdate</label>
				<input type="text" id="birthdate" class="form-control datepicker" size="10" name="date_of_birth" value="{display_date($patient->date_of_birth)}" />
			</div>
		</div>


		<!-- Diet Info Section -->
		<div class="form-header">
			Diet Info
		</div>
		<div class="form-row">
			<div class="form-group col-lg-6 col-md-12">
				<label for="food-allergies">Food Allergies:</label>
				<ul id="allergies">
					{if $allergies}
						{foreach from=$allergies item=allergy}
						<li>{$allergy->name}</li>
						{/foreach}
					{/if}
				</ul>
			</div>
			<div class="form-group col-lg-6 col-md-12">
				<label for="food-dislikes">Food dislikes or intolerances:</label>
				<ul id="dislikes">
					{if $dislikes}
						{foreach from=$dislikes item=dislike}
						<li value="{$dislike->id}">{$dislike->name}</li>
						{/foreach}
					{/if}
				</ul>
			</div>
		</div>


		<!-- Food Dislikes or Intolerances section -->

		<!-- Adaptive Equipment Section -->
		<div class="form-row">
			<div class="form-group col-lg-6 col-md-12">
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
			<div class="form-group col-lg-6 col-md-12">
				<label for="supplements">Supplements:</label>
				<ul id="supplements">
					{if $supplements}
						{foreach from=$supplements item=supplement}
						<li value="{$supplement->id}">{$supplement->name}</li>
						{/foreach}
					{/if}
				</ul>
			</div>
				
		</div>


		<!-- Special Requests Section -->
		<div class="row">
			<h4>Special Requests</h4>
		</div>
		<div class="row">
			<div class="col-md-12 col-lg-4">
				<label for="">Breakfast</label>
				<ul id="breakfast_specialrequest">
					{if $breakfast_spec_req}
					{foreach from=$breakfast_spec_req item=req}
					<li value="{$req->id}">{$req->name}</li>
					{/foreach}
					{/if}
				</ul>
			</div>	
			<div class="col-md-12 col-lg-4">
				<label for="">Lunch</label>
				<ul id="lunch_specialrequest">
					{if $lunch_spec_req}
					{foreach from=$lunch_spec_req item=req}
					<li value="{$req->id}">{$req->name}</li>
					{/foreach}
					{/if}
				</ul>
			</div>	
			<div class="col-md-12 col-lg-4">
				<label for="">Dinner</label>
				<ul id="dinner_specialrequest">
					{if $dinner_spec_req}
					{foreach from=$dinner_spec_req item=req}
					<li value="{$req->id}">{$req->name}</li>
					{/foreach}
					{/if}
				</ul>
			</div>			
		</div>


		<!-- Beverages Section -->
		<div class="row">
			<h4>Beverages</h4>
		</div>

		<div class="row">
			<div class="col-md-12 col-lg-4">
				<label for="">Breakfast</label>
				<ul id="breakfast_beverages">
					{if $breakfast_beverages}
						{foreach from=$breakfast_beverages item=beverage}
						<li>{$beverage->name}</li>
						{/foreach}
					{/if}
				</ul>
			</div>						
			<div class="col-md-12 col-lg-4">
				<label for="">Lunch</label>
				<ul id="lunch_beverages">
					{if $lunch_beverages}
						{foreach from=$lunch_beverages item=beverage}
						<li>{$beverage->name}</li>
						{/foreach}
					{/if}
				</ul>
			</div>
			<div class="col-md-12 col-lg-4">
				<label for="">Dinner</label>
				<ul id="dinner_beverages">
					{if $dinner_beverages}
						{foreach from=$dinner_beverages item=beverage}
						<li>{$beverage->name}</li>
						{/foreach}
					{/if}
				</ul>
			</div>		
		</div>

		<!-- Snacks section -->
		<div class="row">
			<h4>Snacks</h4>
		</div>
		<div class="row">
			<div class="col-md-12 col-lg-4">
				<label for="">AM</label>
				<ul id="snackAM">
					{if $am_snacks}
						{foreach from=$am_snacks item=snack}
						<li>{$snack->name}</li>
						{/foreach}
					{/if}
				</ul>
			</div>						
	        <div class="col-md-12 col-lg-4">
	        	<label for="">PM</label>
				<ul id="snackPM">
					{if $pm_snacks}
						{foreach from=$pm_snacks item=snack}
						<li>{$snack->name}</li>
						{/foreach}
					{/if}
				</ul>
			</div>
			<div class="col-md-12 col-lg-4">
				<label for="">Bedtime</label>
				<ul id="snackBedtime">
					{if $bedtime_snacks}
						{foreach from=$bedtime_snacks item=snack}
						<li>{$snack->name}</li>
						{/foreach}
					{/if}
				</ul>
			</div>	

		</div>


		<!-- Diet Order Section-->
		<div class="form-header2">Diet Order</div>
		<div class="checkbox">
					<label for="regular" class="checkbox-label">
						<input id="regular" class="checkbox" type="checkbox" name="diet_order[]" value="Regular" {if in_array("Regular", $dietOrder['standard'])} checked{/if}> Regular </label> <label for="aha-cardiac" class="checkbox-label">
						<input id="aha-cardiac" type="checkbox" name="diet_order[]" value="AHA/Cardiac" {if in_array("AHA/Cardiac", $dietOrder['standard'])} checked{/if}> AHA/Cardiac </label> <label class="checkbox-label">
						<input type="checkbox" name="diet_order[]" value="No Added Salt" {if in_array("No Added Salt", $dietOrder['standard'])} checked{/if}> No Added Salt </label> <label class="checkbox-label">
						<input type="checkbox" name="diet_order[]" value="RCS" {if in_array("RCS", $dietOrder['standard'])} checked{/if}> RCS</label> 
						<label class="checkbox-label">
						<input type="checkbox" name="diet_order[]" value="2 gram Na" {if in_array("2 gram Na", $dietOrder['standard'])}
							checked{/if}> 2 gram Na </label> <label class="checkbox-label">
						<input type="checkbox" name="diet_order[]" value="Renal" {if in_array("Renal", $dietOrder['standard'])} checked{/if}>
							Renal </label> <label class="checkbox-label">
						<input type="checkbox" name="diet_order[]" value="Gluten Restricted" {if in_array("Gluten Restricted",
							$dietOrder['standard'])} checked{/if}> Gluten Restricted </label> <label class="checkbox-label">
						<input type="checkbox" name="diet_order[]" value="Fortified/High Calorie" {if in_array("Fortified/High Calorie",
							$dietOrder['standard'])} checked{/if}> Fortified/High Calorie </label> <input type="text" name="diet_order[]"
							class="other-input checkbox-input" placeholder="Enter other diet orders..." style="width: 350px"
							value="{$dietOrder['other']}">
		</div>


		<!-- Texture Section -->
		<div class="form-header2">Texture</div>
		<div class="checkbox">
			<label for="" class="checkbox-label">
				<input type="checkbox" name="texture[]" value="Regular" {if in_array('Regular', $textures['standard'])} checked{/if}>
				Regular
			</label>
			<label for="" class="checkbox-label">
					<input type="checkbox" name="texture[]" value="Easy to Chew" {if in_array('Easy to Chew', $textures['standard'])} checked{/if}>
					Easy to Chew
			</label>		
			<label for="" class="checkbox-label">
				<input type="checkbox" name="texture[]" value="Puree" {if in_array('Puree', $textures['standard'])} checked{/if}>
				Puree
			</label>
			<label for="" class="checkbox-label">
					<input type="checkbox" name="texture[]" value="Soft & Bite Sized" {if in_array('Soft & Bite Sized', $textures['standard'])} checked{/if}>
					Soft &amp; Bite Sized
			</label>
			<label for="" class="checkbox-label">
					<input type="checkbox" name="texture[]" value="Minced & Moist" {if in_array('Minced & Moist', $textures['standard'])} checked{/if}>
					Minced &amp; Moist
			</label>
			<label for="" class="checkbox-label">
					<input type="checkbox" name="texture[]" value="Chopped" {if in_array('Chopped', $textures['standard'])} checked{/if}>
					Chopped
			</label>
			<label for="" class="checkbox-label">
					<input type="checkbox" name="texture[]" value="Chopped Meat" {if in_array('Chopped Meat', $textures['standard'])} checked{/if}>
					Chopped Meat
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
				<option value="Liquidised" {if in_array("Liquidised", $textures['standard'])} selected{/if}>Liquidised</option>
				<option value="Slightly Thick" {if in_array("Slightly Thick", $textures['standard'])} selected{/if}>Slightly Thick</option>
				<option value="Mildly Thick" {if in_array("Mildly Thick", $textures['standard'])} selected{/if}>Mildly Thick</option>
				<option value="Moderately Thick" {if in_array("Moderately Thick", $textures['standard'])} selected{/if}>Moderately Thick</option>
				<option value="Extremely Thick" {if in_array("Extremely Thick", $textures['standard'])} selected{/if}>Extremely Thick</option>
				<option value="Other" {if in_array("Other", $textures['standard'])} selected{/if}>Other</option>
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
				<input type="text" name="other[]" value="{$other['other']}">
				<!-- <input type="text" name="other[]" class="other-input" placeholder="Enter other order info..." value="{$other['other']}"> -->
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
</div>

<script type="text/javascript" src="{$JS}/diet.js"></script>

