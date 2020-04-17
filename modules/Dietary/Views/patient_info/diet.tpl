<div class="container diet">
	<h1 class="mb-5">Edit Diet for {$patient->fullName()}</h1>


	<form class="form" action="{$SITE_URL}" method="post">
		<input type="hidden" name="page" value="PatientInfo" />
		<input type="hidden" name="action" value="saveDiet" />
		<input type="hidden" id="patient-id" name="patient" value="{$patient->public_id}" />
		<input type="hidden" name="path" value="{$current_url}" />

		<div class="row form-header">
			<h2>Patient Info</h2>
		</div>
		<div class="row mb-4">
			<div class="form-group col-md-3 col-sm-6">
				<label for="first-name">First Name:</label>
				<input type="text" id="first-name" class="form-control" name="first_name" value="{$patient->first_name}">
			</div>
			<div class="form-group col-md-2 col-sm-6">
				<label for="middle-name">Middle Name:</label>
				<input type="text" id="middle-name" class="form-control" name="middle_name" value="{$patient->middle_name}">
			</div>
			<div class="form-group col-md-5 col-sm-9">
				<label for="last-name">Last Name:</label>
						<input type=" text" id="last-name" class="form-control" name="last_name" value="{$patient->last_name}">
			</div>
			<div class="form-group col-md-2 col-sm-3">
				<label for="last-name">Birthdate:</label>
				<input type="text" class="form-control datepicker" name="date_of_birth"
					value="{display_date($patient->date_of_birth)}" />
			</div>
		</div>



		<!-- diet info section -->
		<div class="row form-header">
			<h2>Diet Info</h2>
		</div>
		<div class="row">

			<div class="col-lg-6 col-md-12">
				<label for="food-allergies">Food Allergies:</label>		
				<select id="food-allergies" name="allergies[]" multiple>
					{foreach from=$allergies item="allergy"}
					<option value="{$allergy->id}"
						{foreach from=$patientAllergies item='pa'}
							{if $allergy->id == $pa->id} selected{/if}
						{/foreach}>{$allergy->name}
					</option>
					{/foreach}
				</select>
			</div>
			
			<div class="form-group col-lg-6 col-md-12">
				<label for="food-dislikes">Food dislikes or intolerances:</label>
				<select id="food-dislikes" name="dislikes[]" multiple>
					{foreach from=$dislikes item="dislike"}
					<option value="{$dislike->id}"
						{foreach from=$patientDislikes item='pd'}
							{if $dislike->id == $pd->id} selected{/if}
						{/foreach}>{$dislike->name}</option>
					{/foreach}
				</select>
				<input type="hidden" name="dislike">
			</div>

		</div>
		<!-- /diet info section -->



		<!-- Adaptive Equipment Section -->
<!-- 		<div class="form-row">
			<div class="form-group col-lg-6 col-md-12">
				<label for="adaptive-equipment">Adaptive Equipment:</label>
				<input type="text" value="" name="adaptive_equipment" id="adaptive-equipment" class="form-control">
			</div> -->

			<!-- Supplements Section -->
<!-- 			<div class="form-group col-lg-6 col-md-12">
				<label for="supplements">Supplements:</label>
				<input type="text" value="" name="supplements" id="supplements" class="form-control">
			</div>
				
		</div> -->


		
		<div class="accordion" id="accordion">
			<!-- special requests section -->
			<div class="card">
				<div class="card-header" id="heading1">
					<h2 class="mb-0">
						<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1"><i class="fa fa-plus mr-3"></i>Special Requests</button>
					</h2>
				</div>
				<div id="collapse1" class="collapse show" aria-labelledby="heading1" data-parent="#accordion">
					<div class="card-body">
						<div class="row">
							<div class="col-md-4">
								<label for="breakfast-special-requests">Breakfast</label>
								<select name="breakfast_special_requests[]" id="breakfast-special-requests" class="special-request" multiple>
									{foreach from=$specialRequests item="sr"}
									<option 
										value="{$sr->id}"
										{foreach from=$breakfast_spec_req item="bsr"}
											{if $sr->id == $bsr->id} selected{/if}
										{/foreach}
									>{$sr->name}</option>
									{/foreach}
								</select>
								
							</div>
							<div class="col-md-4">
								<label for="lunch-special-requests">Lunch</label>
								<select name="lunch_special_requests[]" id="lunch-special-requests" class="special-request" multiple>
									{foreach from=$specialRequests item="sr"}
									<option 
										value="{$sr->id}"
										{foreach from=$lunch_spec_req item="lsr"}
											{if $sr->id == $lsr->id} selected{/if}
										{/foreach}
									>{$sr->name}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-md-4">
								<label for="dinner-special-requests">Dinner</label>
								<select name="dinner_special_requests[]" id="dinner-special-requests" class="special-request" multiple>
									{foreach from=$specialRequests item="sr"}
									<option 
										value="{$sr->id}"
										{foreach from=$dinner_spec_req item="dsr"}
											{if $sr->id == $dsr->id} selected{/if}
										{/foreach}
									>{$sr->name}</option>
									{/foreach}
								</select>
							</div>

						</div>
						
					</div>
				</div>
			</div>
			<!-- /special requests section -->

			<!-- beverages section -->
			<div class="card">
				<div class="card-header" id="heading2">
					<h2 class="mb-0">
						<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2"><i class="fa fa-plus mr-3"></i>Beverages</button>
					</h2>
				</div>
				<div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordion">
					<div class="card-body">
						<div class="row">
							<div class="col-md-4">
								<label for="breakfast-beverages">Breakfast</label>
								<select name="breakfast_beverages[]" id="breakfast-beverages" class="beverages" multiple>
									{foreach from=$beverages item="bev"}
									<option value="{$bev->id}"
										{foreach from=$breakfast_beverages item="bb"}
											{if $bev->id == $bb->beverage_id} selected {/if}
										{/foreach}
									>{$bev->name}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-md-4">
								<label for="lunch-beverages">Lunch</label>
								<select name="lunch_beverages[]" id="lunch-beverages" class="beverages" multiple>
									{foreach from=$beverages item="bev"}
									<option value="{$bev->id}"
										{foreach from=$lunch_beverages item="lb"}
											{if $bev->id == $lb->beverage_id} selected {/if}
										{/foreach}
									>{$bev->name}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-md-4">
								<label for="dinner-beverages">Dinner</label>
								<select name="dinner_beverages[]" id="dinner-beverages" class="beverages" multiple>
									{foreach from=$beverages item="bev"}
									<option value="{$bev->id}"
										{foreach from=$dinner_beverages item="db"}
											{if $bev->id == $db->beverage_id} selected {/if}
										{/foreach}
									>{$bev->name}</option>
									{/foreach}
								</select>
							</div>

						</div>
						
					</div>
				</div>
			</div>
			<!-- /beverages section -->

			<!-- snacks section -->
<!-- 			<div class="card">
				<div class="card-header" id="heading3">
					<h2 class="mb-0">
						<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3"><i class="fa fa-plus mr-3"></i>Snacks</button>
					</h2>
				</div>
				<div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordion">
					<div class="card-body">
						<div class="row">
							<div class="col-md-4">
								<label for="breakfast-snacks">Breakfast</label>
								<input type="text" name="breakfast_snacks" id="breakfast-snacks" class="form-control">
							</div>
							<div class="col-md-4">
								<label for="lunch-snacks">Lunch</label>
								<input type="text" name="lunch_snacks" id="lunch-snacks" class="form-control">
							</div>
							<div class="col-md-4">
								<label for="dinner-snacks">Dinner</label>
								<input type="text" name="dinner_snacks" id="dinner-snacks" class="form-control">
							</div>

						</div>
						
					</div>
				</div>
			</div>
 -->			<!-- /snacks section -->
		</div>



		<!-- diet order section-->
		<div class="row form-header">
			<h2>Diet Order</h2>
		</div>
		
		<!-- regular -->
		<div class="form-check form-check-inline">	
			<input id="regular" class="form-check-input" type="checkbox" name="diet_order[]" value="Regular" {if in_array("=Regular", $dietOrder['standard'])} checked{/if}> 
			<label class="form-check-label" for="regular"> Regular</label>			
		</div>
		<!-- /regular -->

		<!-- aha/cardiac -->
		<div class="form-check form-check-inline">
			<input id="aha-cardiac" class="form-check-input" type="checkbox" name="diet_order[]" value="AHA/Cardiac" {if in_array("AHA/Cardiac", $dietOrder['standard'])} checked{/if}>  
			<label class="form-check-label" for="aha-cardiac"> AHA/Cardiac </label>
		</div>
		<!-- /aha/cardiac -->

		<!-- no added salt -->
		<div class="form-check form-check-inline">
			<input type="checkbox" id="no-added-salt" class="form-check-input" name="diet_order[]" value="No Added Salt" {if in_array("No Added Salt", $dietOrder['standard'])} checked{/if}>  
			<label class="form-check-label" for="no-added-salt"> No Added Salt </label>
		</div>
		<!-- /no added salt -->

		<!-- rcs -->
		<div class="form-check form-check-inline">
			<input type="checkbox" id="rcs" class="form-check-input" name="diet_order[]" value="RCS" {if in_array("RCS", $dietOrder['standard'])} checked{/if}>
			<label class="form-check-label" for="rcs"> RCS</label> 
		</div>
		<!-- /rcs -->

		<!-- 2 gram na -->
		<div class="form-check form-check-inline">				
			<input type="checkbox" id="2-gram-na" class="form-check-input" name="diet_order[]" value="2 gram Na" {if in_array("2 gram Na", $dietOrder['standard'])} checked{/if}>
			<label class="form-check-label" for="2-gram-na"> 2 gram Na </label> 
		</div>
		<!-- /2 gram na -->

		<!-- renal -->
		<div class="form-check form-check-inline">
			<input type="checkbox" id="renal" class="form-check-input" name="diet_order[]" value="Renal" {if in_array("Renal", $dietOrder['standard'])} checked{/if}>
			<label class="form-check-label" for="renal"> Renal</label> 
		</div>	
		<!-- /renal -->			

		<!-- gluten restricted -->
		<div class="form-check form-check-inline">
			<input type="checkbox" id="gluten-restricted" class="form-check-input" name="diet_order[]" value="Gluten Restricted" {if in_array("Gluten Restricted", $dietOrder['standard'])} checked{/if}>  
			<label class="form-check-label" for="gluten-restricted"> Gluten Restricted </label>
		</div>
		<!-- /gluten restricted -->

		<!-- fortified/high calorie -->
		<div class="form-check form-check-inline">
			<input type="checkbox" id="fortified-high-calorie" class="form-check-input" name="diet_order[]" value="Fortified/High Calorie" {if in_array("Fortified/High Calorie", $dietOrder['standard'])} checked{/if}>  
			<label class="form-check-label" for="fortified-high-calorie"> Fortified/High Calorie </label>
		</div>
		<!-- /fortified/high calorie -->
		<div class="form-group mt-4">
			<label for="other-diet-orders">Other Diet Orders:</label>
			<input type="text" id="other-diet-orders" class="form-control" name="diet_order[]" class="other-input checkbox-input" placeholder="Enter other diet orders..." value="{$dietOrder['other']}">
		</div>


		<!-- Texture Section -->
		<div class="row form-header">
			<h2>Texture</h2>
		</div>

		<!-- regular -->
		<div class="form-check form-check-inline">
			<input type="checkbox" id="texture-regular" class="form-check-input" name="texture[]" value="Regular" {if in_array("Regular", $textures['standard'])} checked{/if}>
			<label for="texture-regular" class="form-check-label">Regular</label>
		</div>
		<!-- /regular -->

		<!-- easy to chew -->
		<div class="form-check form-check-inline">
			<input type="checkbox" id="easy-to-chew" class="form-check-label" name="texture[]" value="Easy to Chew" {if in_array('Easy to Chew', $textures['standard'])} checked{/if}>
			<label for="easy-to-chew" class="form-check-label"> Easy to Chew</label>	
		</div>
		<!-- /easy to chew -->
			
		<!-- puree -->
		<div class="form-check form-check-inline">
			<input type="checkbox" id="puree" class="form-check-input" name="texture[]" value="Puree" {if in_array('Puree', $textures['standard'])} checked{/if}>
			<label for="puree" class="form-check-label"> Puree</label>
		</div>
		<!-- /puree -->
			
		<!-- soft & bite sized -->
		<div class="form-check form-check-inline">
			<input type="checkbox" id="soft-bite-sized" class="form-check-input" name="texture[]" value="Soft & Bite Sized" {if in_array('Soft & Bite Sized', $textures['standard'])} checked{/if}>
			<label for="soft-bite-sized" class="form-check-label"> Soft &amp; Bite Sized</label>
		</div>
		<!-- /soft & bite sized  -->

		<!-- minced & moist -->
		<div class="form-check form-check-inline">
			<input type="checkbox" id="minced-moist" name="texture[]" value="Minced & Moist" {if in_array('Minced & Moist', $textures['standard'])} checked{/if}>
			<label for="minced-moist" class="form-check-label"> Minced &amp; Moist</label>
		</div>
		<!-- /minced & moist  -->

		<!-- chopped -->
		<div class="form-check form-check-inline">
			<input type="checkbox" id="chopped" class="form-check-input" name="texture[]" value="Chopped" {if in_array('Chopped', $textures['standard'])} checked{/if}>
			<label for="chopped" class="form-check-label"> Chopped</label>
		</div>
		<!-- /chopped  -->

		<!-- chopped meat -->
		<div class="form-check form-check-inline">
			<input type="checkbox" id="chopped-meat" class="form-check-input" name="texture[]" value="Chopped Meat" {if in_array('Chopped Meat', $textures['standard'])} checked{/if}>
			<label for="chopped-meat" class="form-check-label"> Chopped Meat</label>
		</div>
		<!-- /chopped meat  -->

		<!-- tube feeding -->
		<div class="form-check form-check-inline">
			<input type="checkbox" id="tube-feeding" class="form-check-input" name="texture[]" value="Tube Feeding" {if in_array('Tube Feeding', $textures['standard'])} checked{/if}>
			<label for="tube-feeding" class="form-check-label"> Tube Feeding</label>
		</div>
		<!-- /tube feeding  -->


		<div class="row form-group mt-4">
			<div class="col-md-4">
				<select class="form-control" id="liquid" name="texture[]" id="">
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
			</div>
			<div class="col-md-8">
				<input type="text" class="form-control" maxlength="25" name="texture[]" placeholder="Enter other texture info..." value="{$textures['other']}">
			</div>
		</div>

		<!-- Other Section -->
		<div class="form-header">
			<h2>Other</h2>
		</div>

		<div class="row">
			
			<div class="col-md-5 col-sm-12">
				<!-- tube feeding -->
				<div class="form-check form-check-inline">
					<input type="checkbox" id="tube-feeding" class="form-check-input" name="other[]" value="Tube Feeding" {if in_array("Isolation", $other['standard'])} checked{/if}>
					<label for="tube-feeding" class="form-check-label"> Tube Feeding</label>
				</div>
				<!-- /tube feeding -->

				<!-- isolation -->
				<div class="form-check form-check-inline">
					<input type="checkbox" id="isolation" class="form-check-input" name="other[]" value="Isolation" {if in_array("Isolation", $other['standard'])} checked{/if}>
					<label for="isolation" class="form-check-label"> Isolation</label>
				</div>
				<!-- /isolation -->

				<!-- fluid restriction -->
				<div class="from-check form-check-inline">
					<input type="checkbox" id="fluid-restriction" class="form-check-input" name="other[]" value="Fluid Restriction" {if in_array("Fluid Restriction", $other['standard'])} checked{/if}>
					<label for="fluid-restriction" class="form-check-label"> Fluid Restriction</label>
					<!-- fluid restriction -->
				</div>
			</div>
			<div class="col-md-7 col-sm-12">
				<input type="text" class="form-control" name="other[]" value="Fluid Restriction Info" placeholder="Enter fluid restriction info">
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- portion size -->
				<div class="font-weight-bold">Portion Size:</div>
				<div class="form-check form-check-inline">
					<input type="radio" id="portion-small" class="form-check-input" name="portion_size" value="Small" {if $patientInfo->portion_size == "Small"} checked{/if}>
					<label for="portion-small" class="form-check-label">Small</label>
				</div>

				<div class="form-check form-check-inline">
					<input type="radio" id="portion-regular" class="form-check-input" name="portion_size" value="Regular" {if $patientInfo->portion_size == "Regular"} checked{elseif $patientInfo->portion_size == "Medium"} checked{elseif !isset($patientInfo->portion_size)} checked{/if}>
					<label for="portion-regular" class="form-check-label"> Regular</label>
				</div>
					
				<div class="form-check form-check-inline">
					<input type="radio" id="portion-large" class="form-check-input" name="portion_size" value="Large" {if $patientInfo->portion_size == "Large"} checked{/if}>
					<label for="portion-large" class="form-check-label">Large</label>
				</div>
				<!-- /portion size -->
			</div>
		</div>


		<div class="row text-right my-4">
			<div class="col-md-12">
				<button type="button" class="btn btn-secondary" onClick="history.go(-1)">Cancel</button>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
		</div>

	</form>
</div>

