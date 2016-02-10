<?php

class PatientInfoController extends DietaryController {

	// protected $navigation = 'dietary';
	// protected $searchBar = 'dietary';


	public function diet() {
		smarty()->assign('title', "Edit Diet");

		// fetch the patient info from the id in the url
		if (isset (input()->patient) && input()->patient != "") {
			$patient = $this->loadModel('Patient', input()->patient);
		} else {
			session()->setFlash("Could not find the selected patient, please try again", 'error');
			$this->redirect();
		}

		// get the diet info for the selected patient
		$patientInfo = $this->loadModel('PatientInfo')->fetchDietInfo($patient->id);

		// fetch the allergies, dislikes and snacks
		$allergies = $this->loadModel("PatientFoodInfo")->fetchPatientAllergies($patient->id);
		$dislikes = $this->loadModel("PatientFoodInfo")->fetchPatientDislikes($patient->id);
		$am_snacks = $this->loadModel("PatientSnack")->fetchPatientSnacks($patient->id, "am");
		$pm_snacks = $this->loadModel("PatientSnack")->fetchPatientSnacks($patient->id, "pm");
		$bedtime_snacks = $this->loadModel("PatientSnack")->fetchPatientSnacks($patient->id, "bedtime");

		$adapt_equip = $this->loadModel("PatientAdaptEquip")->fetchPatientAdaptEquip($patient->id);
		$beverages = $this->loadModel("PatientBeverage")->fetchPatientBeverage($patient->id);
		$supplements = $this->loadModel("PatientSupplement")->fetchPatientSupplement($patient->id);


		// set arrays for checkboxes, dropdowns, etc.
		$dietOrder = $this->loadModel("PatientDietInfo")->fetchPatientDietInfo($patient->id);

		$textures = $this->loadModel("PatientTexture")->fetchPatientTexture($patient->id);

		$portionSize = array("Small", "Medium", "Large");

		$orders = $this->loadModel("PatientOrder")->fetchPatientOrder($patient->id);

		smarty()->assignByRef('patient', $patient);
		smarty()->assignByRef('patientInfo', $patientInfo);
		smarty()->assignByRef('allergies', $allergies);
		smarty()->assignByRef('dislikes', $dislikes);
		smarty()->assignByRef('adaptEquip', $adapt_equip);
		smarty()->assignByRef('beverages', $beverages);
		smarty()->assignByRef('supplements', $supplements);
		smarty()->assignByRef('am_snacks', $am_snacks);
		smarty()->assignByRef('pm_snacks', $pm_snacks);
		smarty()->assignByRef('bedtime_snacks', $bedtime_snacks);
		smarty()->assign("dietOrder", $dietOrder);
		smarty()->assign("textures", $textures);
		smarty()->assign("portionSize", $portionSize);
		smarty()->assign("orders", $orders);
	}


	public function saveDiet() {
		$feedback = array();
		if (input()->patient != "") {
			$patient = $this->loadModel("Patient", input()->patient);
		} else {
			session()->setFlash("Could not find the patient.", 'error');
			$this->redirect(input()->currentUrl);
		}

		$patientDiet = $this->loadModel("PatientInfo")->fetchDietInfo($patient->id);
		$patientDiet->patient_id = $patient->id;

		$patient->first_name = input()->first_name;
		$patient->last_name = input()->last_name;
		$patient->date_of_birth = mysql_date(input()->date_of_birth);

		// if input fields are not empty then set the data
/*		if (input()->height != "") {
			$patientDiet->height = input()->height;
		}

		if (input()->weight != "") {
			$patientDiet->weight = input()->weight;
		}*/

		if(input()->other_diet_info){
			$patientDiet->diet_info_other = input()->other_diet_info;
		}

		if(input()->other_texture_info){
			$patientDiet->texture_other = input()->other_texture_info;
		}

		if(input()->other_orders_info){
			$patientDiet->orders_other = input()->other_texture_info;
		}


		// set allergies array
		$allergiesArray = array();
		if (!empty (input()->allergies)) {
			foreach (input()->allergies as $item) {
				$allergy = $this->loadModel("Allergy")->fetchByName($item);
				$foodInfo = $this->loadModel("PatientFoodInfo")->fetchByPatientAndFoodId($patient->id, $allergy->id);

				if ($foodInfo->patient_id == "") {
					$foodInfo->patient_id = $patient->id;
					$foodInfo->food_id = $allergy->id;
					$foodInfo->allergy = true;
					$allergiesArray[] = $foodInfo;
				}
			}
		}

		// set dislikes array
		$dislikesArray = array();
		if (!empty (input()->dislikes)) {
			foreach (input()->dislikes as $item) {
				$dislike = $this->loadModel("Dislike")->fetchByName($item);
				$foodInfo = $this->loadModel("PatientFoodInfo")->fetchByPatientAndFoodId($patient->id, $dislike->id);

				if ($foodInfo->patient_id == "") {
					$foodInfo->patient_id = $patient->id;
					$foodInfo->food_id = $dislike->id;
					$foodInfo->allergy = false;
					$dislikesArray[] = $foodInfo;
				}
			}
		}

		// set adaptive equipment array
		$adaptEquipArray = array();
		if (!empty (input()->adaptEquip)) {
			foreach (input()->adaptEquip as $item) {
				$adaptEquip = $this->loadModel("AdaptEquip")->fetchByName($item);
				$patientAdaptEquip = $this->loadModel("PatientAdaptEquip")->fetchByPatientAndAdaptEquipId($patient->id, $adaptEquip->id);

				if ($patientAdaptEquip->patient_id == "") {
					$patientAdaptEquip->patient_id = $patient->id;
					$patientAdaptEquip->adapt_equip_id = $adaptEquip->id;
					$adaptEquipArray[] = $patientAdaptEquip;
				}
			}
		}

		// set beverages array
		$beveragesArray = array();
		if (!empty (input()->beverages)) {
			foreach (input()->beverages as $item) {
				$beverage = $this->loadModel("Beverage")->fetchByName($item);
				$patientBeverage = $this->loadModel("PatientBeverage")->fetchByPatientAndBeverageId($patient->id, $beverage->id);

				if ($patientBeverage->patient_id == "") {
					$patientBeverage->patient_id = $patient->id;
					$patientBeverage->beverage_id = $beverage->id;
					$beveragesArray[] = $patientBeverage;
				}
			}
		}

		// set supplements array
		$supplementsArray = array();
		if (!empty (input()->supplements)) {
			foreach (input()->supplements as $item) {
				$supplement = $this->loadModel("Supplement")->fetchByName($item);
				$patientSupplement = $this->loadModel("PatientSupplement")->fetchByPatientAndSupplementId($patient->id, $supplement->id);

				if ($patientSupplement->patient_id == "") {
					$patientSupplement->patient_id = $patient->id;
					$patientSupplement->supplement_id = $supplement->id;
					$supplementsArray[] = $patientSupplement;
				}
			}
		}

		// set diet_info array
		$patientdietInfoArray = array();
		if (!empty (input()->diet_info)) {
			foreach (input()->diet_info as $item) {
				$diet_info = $this->loadModel("DietInfo")->fetchByName($item);
				$patientDietInfo = $this->loadModel("PatientDietInfo")->fetchByPatientAndDietInfoId($patient->id, $diet_info->id);

				if ($patientDietInfo->patient_id == "") {
					$patientDietInfo->patient_id = $patient->id;
					$patientDietInfo->diet_info_id = $diet_info->id;
					$patientDietInfoArray[] = $patientDietInfo;
				}
			}
		} else {
			$feedback[] = "Diet order has not been entered";
		}

		// set texture array
		if (!empty (input()->texture)) {
			foreach (input()->texture as $item) {
				$texture_item = $this->loadModel("Texture")->fetchByName($item);
				$patientTexture = $this->loadModel("PatientTexture")->fetchByPatientAndTextureId($patient->id, $texture_item->id);

				if ($patientTexture->patient_id == "") {
					$patientTexture->patient_id = $patient->id;
					$patientTexture->texture_id = $texture_item->id;
					$patientTextureArray[] = $patientTexture;
				}
			}
		} else {
			$feedback[] = "Diet texture has not been entered";
		}

		// set order array
		if (!empty (input()->orders)) {
			foreach (input()->orders as $item) {
				$order_item = $this->loadModel("Order")->fetchByName($item);
				$patientOrder = $this->loadModel("PatientOrder")->fetchByPatientAndOrderId($patient->id, $order_item->id);

				if ($patientOrder->patient_id == "") {
					$patientOrder->patient_id = $patient->id;
					$patientOrder->order_id = $order_item->id;
					$patientOrderArray[] = $patientOrder;
				}
			}
		} else {
			$feedback[] = "Diet texture has not been entered";
		}

		if (!empty(input()->portion_size)) {
			$patientDiet->portion_size = input()->portion_size;
		} else {
			$feedback[] = "Portion size has not been entered";
		}

		if (input()->special_requests != "") {
			$patientDiet->special_requests = input()->special_requests;
		}

		$snackArray = array();
		if (!empty(input()->am)) {
			$snackArray[] = $this->saveFoodItems(input()->am, $patient->id, "am");
		} else {
			$feedback[] = "AM Snack has not been entered";
		}

		if (!empty(input()->pm)) {
			$snackArray[] = $this->saveFoodItems(input()->pm, $patient->id, "pm");
		} else {
			$feedback[] = "PM Snack has not been entered";
		}

		if (!empty(input()->bedtime)) {
			$snackArray[] = $this->saveFoodItems(input()->bedtime, $patient->id, "bedtime");
		} else {
			$feedback[] = "Bedtime Snack has not been entered";
		}

		// save the patient diet info
		if ($patientDiet->save() && $patient->save()) {
			// save the patient's allergies
			foreach ($allergiesArray as $item) {
				$item->save();
			}

			// save the patient's diet info
			foreach ($patientDietInfoArray as $item) {
				$item->save();
			}

			// save the patient's adapt equip info
			foreach ($adaptEquipArray as $item) {
				$item->save();
			}

			// save the patient's beverage info
			foreach ($beveragesArray as $item) {
				$item->save();
			}


			// save the patient's beverage info
			foreach ($supplementsArray as $item) {
				$item->save();
			}

			// save the patient's texture info
			foreach ($patientTextureArray as $item) {
				$item->save();
			}

			// save the patient's dislikes
			foreach ($dislikesArray as $item) {
				$item->save();
			}

			// save the patient's orders
			foreach ($patientOrderArray as $item) {
				$item->save();
			}
			// save the patient's snacks. Snacks are very important, especially late at night when you are really hungry.
			foreach ($snackArray as $item) {
				foreach ($item as $i) {
					$i->save();
				}
			}

			$location = $this->loadModel("Location", $patientDiet->location_id);
			session()->setFlash(array("Diet Info was saved for {$patient->fullName()}", $feedback), "success");
			$this->redirect(array("module" => "Dietary", "page" => "Dietary", "location" => $location->public_id));
		} else {
			session()->setFlash($feedback, "error");
			$this->redirect(input()->currentUrl);
		}

	}

  public function traycard() {
  	ini_set('memory_limit','-1');
    $location = $this->getLocation();
		$html = "";

    if(input()->patient == "all"){
			// check if the location is has the admission dashboard enabled
			$modEnabled = ModuleEnabled::isAdmissionsEnabled($location->id);

			// if the facility is using the admission dashboard, then get a list of
			// the current patients from the admission app for the current location.

			// NOTE: if a location is using the admission dashboard they should
			// not have the ability to add or delete patients through the dietary
			// app interface.
			$rooms = $this->loadModel("Room")->fetchEmpty($location->id);
			if ($modEnabled) {
				// until the admission app is re-built and we move to a single database we need to fetch
				// the data from the admission db and save to the master db
				// IMPORTANT: Remove this after admission app is re-built in new framework!!!
				$scheduled = $this->loadModel('AdmissionDashboard')->syncCurrentPatients($location->id);
			} else {
				// if the locations is not using the admission dashboard then load the patients
				// from ac_patient and dietary_patient_info tables
				// fetch current patients
				$scheduled = $this->loadModel("Patient")->fetchPatients($location->id);
			}
			$currentPatients = $this->loadModel("Room")->mergeRooms($rooms, $scheduled);

	    foreach($currentPatients as $key => $patient){
	    	if(get_class($patient) == "Patient"){
  				$html =  $this->createHtml($patient, $location, $html);
	    	}
	    }
    }
    else{
	  	if (input()->patient != "") {
	    	$patient = $this->loadModel("Patient", input()->patient);
	    	$html = $html . $this->createHtml($patient, $location, $html);
	  	}
		  else {
		    session()->setFlash("Could not fine the selected patient, please try again.", 'error');
		    $this->redirect();
		  }
    }

		$pdfDetails = array("title" => '', "html" => $html, "header" => false, "footer" => false, "orientation" => "Landscape");

		$this->buildPDFOptions($pdfDetails);

  }

	public function traycard_options(){
		// get the location
		$location = $this->getLocation();

		// check if the user has permission to access this module
		if ($location->location_type != 1) {
			$this->redirect();
		}
		$rooms = $this->loadModel("Room")->fetchEmpty($location->id);

		$scheduled = $this->loadModel("Patient")->fetchPatients($location->id);
		$currentPatients = $this->loadModel("Room")->mergeRooms($rooms, $scheduled);

		smarty()->assign('currentPatients', $currentPatients);
	}


	public function add_patient() {
		smarty()->assign("title", "Add New Patient");

		if (input()->number != "") {
			$number = input()->number;
		} else {
			$number = "";
		}

		if (input()->location != "") {
			$location = $this->loadModel("Location", input()->location);
		} else {
			session()->setFlash("No location was selected. Please try again", 'error');
			$this->redirect();
		}


		smarty()->assign("number", $number);
		smarty()->assignByRef('location', $location);

	}


	public function saveAddPatient() {

		$feedback = array();
		$patient = $this->loadModel("Patient");
		if (input()->location != "") {
			$location = $this->loadModel("Location", input()->location);
		} else {
			session()->setFlash("No location was selected. Please try again", 'error');
			$this->redirect(input()->currentUrl);
		}

		if (input()->number != "") {
			$room = $this->loadModel("Room")->getRoom($location->id, input()->number);
		} else {
			session()->setFlash("No room number was selected. Please try again", 'error');
			$this->redirect(input()->currentUrl);
		}

		if (input()->last_name != "") {
			$patient->last_name = input()->last_name;
		} else {
			$feedback[] = "Enter a last name";
		}

		if (input()->first_name != "") {
			$patient->first_name = input()->first_name;
		} else {
			$feedback[] = "Enter a first name.";
		}

		// Breakpoint
		if (!empty ($feedback)) {
			session()->setFlash($feedback, 'error');
			$this->redirect(input()->currentUrl);
		}

		// save patient info
		if ($patient->save()) {
			// if the patient info save is successful, then set the patient admit data and save it
			$schedule = $this->loadModel("Schedule");
			$schedule->patient_id = $patient->id;
			$schedule->location_id = $location->id;
			$schedule->room_id = $room->id;
			$schedule->datetime_admit = mysql_date(input()->admit_date);
			$schedule->status = "Approved";


			// set dietary patient info
			$dietaryInfo = $this->loadModel("PatientInfo");
			$dietaryInfo->patient_id = $patient->id;
			$dietaryInfo->location_id = $location->id;


			if ($schedule->save() && $dietaryInfo->save()) {
				session()->setFlash("Added {$patient->fullName()}", 'success');
				$this->redirect(array("module" => "Dietary"));
			} else {
				session()->setFlash("Could not add patient. Please try again.", 'error');
				$this->redirect(array("module" => "Dietary"));
			}
		} else {
			session()->setFlash("Could not add patient. Please try again.", 'error');
			$this->redirect(array("module" => "Dietary"));
		}

	}



	public function fetchOptions() {
		if (input()->type != "") {
			$type = input()->type;
		}

		$options = $this->loadModel($type)->fetchAll();
		json_return($options);
	}


	public function deleteItem() {
		if (input()->patient != "") {
			$patient = $this->loadModel("Patient", input()->patient);
		} else {
			return false;
		}

		if (input()->name != "") {
			// delete the patient food info item
			if (input()->type != "snack") {
				if ($this->loadModel("PatientFoodInfo")->deleteFoodInfoItem($patient->id, input()->name, input()->type)) {
					return true;
				}
			} else {
				if ($this->loadModel("PatientSnack")->deleteSnack($patient->id, input()->name, input()->time)) {
					return true;
				}
			}
			return false;
		}

		return false;
	}



	public function saveFoodItems($items = array(), $patient_id = null, $snackTime = null) {
		if (!empty($items)) {
			$snackArray = array();
			foreach ($items as $key => $snack) {
				echo $key;
				$time = $this->loadModel("PatientSnack");
				$snackObj = $this->loadModel("Snack")->fetchByName($snack);
				// if the item was found in the db, then assign the id
				if ($snackObj) {
					$time->snack_id = $snackObj->id;
				// if nothing was found then we need to save the new item
				} else {
					$snackObj->name = $snack;
					$snackObj->save();
					$time->snack_id = $snackObj->id;
				}
				$time->patient_id = $patient_id;
				$time->time = $snackTime;
				$snackArray[] = $time;
			}
			return $snackArray;
		}

		return false;
	}

  private function createHtml($patient, $location, $html){

  if(isset(input()->date)){
    $weekSeed = input()->date;
  }
  else{
    $weekSeed = date('Y-m-d');
  }
  $week = Calendar::getWeek($weekSeed);
  $_dateStart = date('Y-m-d', strtotime($week[0]));

  $menu = $this->loadModel('Menu')->fetchMenu($location->id, $_dateStart);
  $numDays = $this->loadModel('MenuItem')->fetchMenuDay($menu->menu_id);
  $startDay = round($this->dateDiff($menu->date_start, $_dateStart) % $numDays->count + 1);


  $now = date('Y-m-d', strtotime('now'));
  $menuItems = $this->loadModel('MenuItem')->fetchMenuItems($location->id, $_dateStart, $_dateStart, $startDay, $startDay, $menu->menu_id);

  //Are we looking for specific meal?
	if(!isset(input()->meal) || input()->meal == "All"){
    $menuItems[0]->meal = "Breakfast";
    $menuItems[1]->meal = "Lunch";
    $menuItems[2]->meal = "Dinner";
  }
  else{
    if(input()->meal == "Breakfast"){
      $menuItems[0]->meal = "Breakfast";
      $menuItems[1]->meal = "";
      $menuItems[2]->meal = "";

    }
    elseif (input()->meal == "Lunch") {
      $menuItems[0]->meal = "Lunch";
      $menuItems[1]->meal = "";
      $menuItems[2]->meal = "";
    }
    elseif (input()->meal == "Dinner") {
      $menuItems[0]->meal = "Dinner";
      $menuItems[1]->meal = "";
      $menuItems[2]->meal = "";
    }
  }
  // need to get patient diet info
  $diet = $this->loadModel("PatientInfo")->fetchDietInfo($patient->id);
  // get patient schedule info
  $schedule = $this->loadModel("Schedule")->fetchByPatientId($patient->id);

  $allergies = $this->loadModel("PatientFoodInfo")->fetchPatientAllergies($patient->id);
  $dislikes = $this->loadModel("PatientFoodInfo")->fetchPatientDislikes($patient->id);
  $textures = $this->loadModel("PatientTexture")->fetchPatientTexture($patient->id);
  $orders = $this->loadModel("PatientOrder")->fetchPatientOrder($patient->id);
  $patientInfo = $this->loadModel('PatientInfo')->fetchDietInfo($patient->id);

  $birthday = false;
  if(date('m-d') == substr($patient->date_of_birth,5,5)){
    $birthday = true;
  };

  $allergy_names = array();

  if($allergies){ //Does this patient have any allergies?
	  foreach($allergies as $allergy){
	    array_push($allergy_names, $allergy->name);
	  }
  }
  else{
  	array_push($allergy_names, "");
  }
  $allergy_names = implode(', ', $allergy_names);

  $texture_names = array();

  foreach($textures as $texture){
    if($texture->patient_id && $texture->name != 'Other'){
      array_push($texture_names, $texture->name);
    }
    if($texture->name == 'Other'){
      array_push($texture_names, $patientInfo->texture_other);
    }
  }
  $texture_names = implode(', ', $texture_names);

  $order_names = array();

  if($orders){
	  foreach($orders as $order){
	    if($order->patient_id && $order->name != 'Other'){
	      array_push($order_names, $order->name);
	    }
	    if($order->name == 'Other'){
	      array_push($order_names, $patientInfo->orders_other);
	    }
	  }
  }
  else{
  	array_push($order_names, "");
  }
  $order_names = implode(', ', $order_names);

  $dislike_names = array();

  if($dislikes){
	  foreach($dislikes as $dislike){
	    array_push($dislike_names, $dislike->name);
	  }
	}
	else{
  	array_push($dislike_names, "");
	}
  $dislike_names = implode(', ', $dislike_names);

  $_dateStart = date("F jS, Y", strtotime($_dateStart));

//Here's where we make the rows modular so they can choose specific meals instead of always printing all of them
$headerRow = <<<EOD
<th colspan="2" align="center"><strong>{$menuItems[0]->meal}</strong></th>
<th colspan="2" align="center"><strong>{$menuItems[1]->meal}</strong></th>
<th colspan="2" align="center"><strong>{$menuItems[2]->meal}</strong></th>
EOD;
    $nameRow = "";
    $consumedRow ="";
    $birthdayRow = "";
    $portionRow = "";
    $allergyRow = "";
    $textureRow = "";
    $orderRow = "";
    $dislikeRow = "";
    $mealRow = "";
    $contentRow = "";

    foreach($menuItems as $item){
if ($item->meal != ""){

$nameRow = $nameRow . <<<EOD
  <td>{$patient->first_name} {$patient->last_name}</td>
  <td>{$_dateStart}</td>
EOD;

$consumedRow = $consumedRow . <<<EOD
  <td>Meal Consumed: </td>
  <td></td>
EOD;

$birthdayRow = $birthdayRow . <<<EOD
  <td colspan="2" class="birthday">Happy Birthday!</td>

EOD;

$portionRow = $portionRow . <<<EOD
  <td>Portion size: </td>
  <td>{$patientInfo->portion_size}</td>
EOD;


$allergyRow = $allergyRow . <<<EOD
  <td class="allergy">Allergies:</td>
  <td class="allergy">{$allergy_names}</td>
EOD;

$textureRow = $textureRow . <<<EOD
  <td >Textures:</td>
  <td >{$texture_names}</td>
EOD;

$orderRow = $orderRow . <<<EOD
  <td >Orders:</td>
  <td >{$order_names}</td>
EOD;

$dislikeRow = $dislikeRow . <<<EOD
  <td >Do Not Serve:</td>
  <td >{$dislike_names}</td>
EOD;

$mealRow = $mealRow . <<<EOD
  <td colspan="2" align="center" class="border_bottom">Meal</td>
EOD;

$contentRow = $contentRow . <<<EOD
  <td colspan="2">{$item->content}</td>
EOD;
}

    }

$html = $html . <<<EOD
  <style>
    .birthday{
      color:green;
    }
    .allergy{
      color:red;
      font-style:italic;

    }
    .border_bottom{
    	border-bottom:1px solid black;
    }
  </style>
  <table cellpadding="4">
    <thead>
      <tr>
      	{$headerRow}
      </tr>
    </thead>
    <tbody>
      <tr>
        {$nameRow}
      </tr>
EOD;
if($birthday){
$html = $html . <<<EOD
      <tr>
      	{$birthdayRow}
      </tr>
EOD;

  }

$html = $html . <<<EOD
      <tr>
      	{$consumedRow}
      </tr>
      <tr>
      	{$portionRow}
      </tr>
      <tr>
      	{$allergyRow}
      </tr>

      <tr>
        {$textureRow}
      </tr>

      <tr>
      	{$orderRow}
      </tr>

      <tr>
      	{$dislikeRow}
      </tr>

      <tr>
      	{$mealRow}
      </tr>
      <tr>
      	{$contentRow}
      </tr>
    </tbody>
  </table>
  <br pagebreak="true"/>
EOD;
		//

		return $html;
	}



}
