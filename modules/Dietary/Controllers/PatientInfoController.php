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


		$breakfast_beverages = $this->loadModel("PatientBeverage")->fetchPatientBeverage($patient->id, "Breakfast");
		$lunch_beverages = $this->loadModel("PatientBeverage")->fetchPatientBeverage($patient->id, "Lunch");
		$dinner_beverages = $this->loadModel("PatientBeverage")->fetchPatientBeverage($patient->id, "Dinner");

		$adapt_equip = $this->loadModel("PatientAdaptEquip")->fetchPatientAdaptEquip($patient->id);
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
		smarty()->assignByRef('breakfast_beverages', $breakfast_beverages);
		smarty()->assignByRef('lunch_beverages', $lunch_beverages);
		smarty()->assignByRef('dinner_beverages', $dinner_beverages);
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
			$patientDiet->orders_other = input()->other_orders_info;
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
		if (!empty (input()->breakfast_beverages)) {
			foreach (input()->breakfast_beverages as $item) {
				$beverage = $this->loadModel("Beverage")->fetchByName($item);
				$patientBeverage = $this->loadModel("PatientBeverage")->fetchByPatientAndBeverageId($patient->id, $beverage->id);

				if ($patientBeverage->patient_id == "") {
					$patientBeverage->patient_id = $patient->id;
					$patientBeverage->beverage_id = $beverage->id;
					$patientBeverage->meal = "Breakfast";
					$beveragesArray[] = $patientBeverage;
				}
			}
		}

		// set beverages array
		if (!empty (input()->lunch_beverages)) {
			foreach (input()->lunch_beverages as $item) {
				$beverage = $this->loadModel("Beverage")->fetchByName($item);
				$patientBeverage = $this->loadModel("PatientBeverage")->fetchByPatientAndBeverageId($patient->id, $beverage->id);

				if ($patientBeverage->patient_id == "") {
					$patientBeverage->patient_id = $patient->id;
					$patientBeverage->beverage_id = $beverage->id;
					$patientBeverage->meal = "Lunch";
					$beveragesArray[] = $patientBeverage;
				}
			}
		}

		// set beverages array
		if (!empty (input()->dinner_beverages)) {
			foreach (input()->dinner_beverages as $item) {
				$beverage = $this->loadModel("Beverage")->fetchByName($item);
				$patientBeverage = $this->loadModel("PatientBeverage")->fetchByPatientAndBeverageId($patient->id, $beverage->id);

				if ($patientBeverage->patient_id == "") {
					$patientBeverage->patient_id = $patient->id;
					$patientBeverage->beverage_id = $beverage->id;
					$patientBeverage->meal = "Dinner";
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
			$feedback[] = "Orders has not been entered";
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



/*
 * -------------------------------------------------------------------------
 *  TRAYCARD PDF PAGE
 * -------------------------------------------------------------------------
 *
 * This method will create a traycard for:
 * 1) all patients in the facility (via the "Tray Cards" button on the Dietary module home page)
 * 2) the current day's traycard for a specific patient
 * 3) the selected day traycard for the selected patient
 * 4) the selected day traycard and the selected meal for the selected patient
 *
 * This method uses the HTML from meal_traycard.tpl and the css from pdf_styles.css to create a
 * PDF page.
 *
 */

	public function meal_tray_card() {

		// this page will always create a PDF
		$this->template = 'pdf';
		// fetch the location
		$location = $this->getLocation();

		// fetch data
		if (input()->patient == "all") {
			// need to fetch info for every patient in the building
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

			$tray_card_info = array();
		    foreach($currentPatients as $key => $patient){
		    	if (get_class($patient) == "Patient") {
		    		// fetch the traycard info for this patient
		    		$tray_card_info[] = $this->loadModel('PatientInfo')->fetchTrayCardInfo($patient->id);
		    	}
		    }

		} else {
			// fetch the selected patient info
			$patient = $this->loadModel("Patient")->fetchPatientById(input()->patient);
			// fetch the patient's tray card info
			$tray_card_info = $this->loadModel('PatientInfo')->fetchTrayCardInfo($patient->id);			
		}

		// get date from the url
		if(isset(input()->date)){
			$_dateStart = date('Y-m-d', strtotime(input()->date));
		} else{
			$_dateStart = date('Y-m-d', strtotime('now'));
		}	

		// get the meal id from the url
		if (isset (input()->meal_id)) {
			$meal_id = input()->meal_id;
		} else {
			$meal_id = "all";
		}

		$menu = $this->loadModel('Menu')->fetchMenu($location->id, $_dateStart);
		$numDays = $this->loadModel('MenuItem')->fetchMenuDay($menu->menu_id);
		$startDay = round($this->dateDiff($menu->date_start, $_dateStart) % $numDays->count + 1);
		$menuItems = $this->loadModel('MenuItem')->fetchMenuItems($location->id, $_dateStart, $_dateStart, $startDay, $startDay, $menu->menu_id, $meal_id);


		$meal_names = array(0 => "Breakfast", 1 => "Lunch", 2 => "Dinner");
		

		if (input()->patient == 'all') {
			foreach ($tray_card_info as $key => $tci) {
				for ($i=0;$i<3;$i++) {
				
					$tray_card_cols[$key][$i] = new stdClass();
					$tray_card_cols[$key][$i]->meal_name = $meal_names[$i];
					foreach ($tci['main_data'] as $k => $d) {
						$tray_card_cols[$key][$i]->$k = $d;
					}
					$tray_card_cols[$key][$i]->beverages = "";
					foreach ($tci['items_by_meal']['beverages'] as $k => $b) {			
						if ($b->meal == $tray_card_cols[$key][$i]->meal_name) {
							$tray_card_cols[$key][$i]->beverages .= $b->name . ", ";
						}
					}
					$tray_card_cols[$key][$i]->special_reqs = "";
					foreach ($tci['items_by_meal']['special_reqs'] as $k => $sr) {	
						if ($sr->meal == $tray_card_cols[$key][$i]->meal_name) {
							$tray_card_cols[$key][$i]->special_reqs .= $sr->name . ", ";
						}
					}
					if (strtotime($tci['main_data']->date_of_birth) == strtotime(date('Y-m-d', strtotime('now')))) {
						$tray_card_cols[$key][$i]->birthday = true;
					} else {
						$tray_card_cols[$key][$i]->birthday = false;
					}
				}
			}
			$all_tray_cards = true;
		} else {
			if ($meal_id != "all") {
				$i = $meal_id -1;
				$tray_card_cols[$i] = new stdClass();
				$tray_card_cols[$i]->meal_name = $meal_names[$i];
				foreach ($tray_card_info['main_data'] as $k => $d) {
					$tray_card_cols[$i]->$k = $d;
				}
				$tray_card_cols[$i]->beverages = "";
				foreach ($tray_card_info['items_by_meal']['beverages'] as $k => $b) {			
					if ($b->meal == $tray_card_cols[$i]->meal_name) {
						$tray_card_cols[$i]->beverages .= $b->name . ", ";
					}
				}
				$tray_card_cols[$i]->special_reqs = "";
				foreach ($tray_card_info['items_by_meal']['special_reqs'] as $k => $sr) {	
					if ($sr->meal == $tray_card_cols[$i]->meal_name) {
						$tray_card_cols[$i]->special_reqs .= $sr->name . ", ";
					}
				}
				if (strtotime($tray_card_info['main_data']->date_of_birth) == strtotime(date('Y-m-d', strtotime('now')))) {
					$tray_card_cols[$i]->birthday = true;
				} else {
					$tray_card_cols[$i]->birthday = false;
				}
			} else {
				for ($i=0;$i<3;$i++) {
					$tray_card_cols[$i] = new stdClass();
					$tray_card_cols[$i]->meal_name = $meal_names[$i];
					foreach ($tray_card_info['main_data'] as $k => $d) {
						$tray_card_cols[$i]->$k = $d;
					}
					$tray_card_cols[$i]->beverages = "";
					foreach ($tray_card_info['items_by_meal']['beverages'] as $k => $b) {			
						if ($b->meal == $tray_card_cols[$i]->meal_name) {
							$tray_card_cols[$i]->beverages .= $b->name . ", ";
						}
					}
					$tray_card_cols[$i]->special_reqs = "";
					foreach ($tray_card_info['items_by_meal']['special_reqs'] as $k => $sr) {	
						if ($sr->meal == $tray_card_cols[$i]->meal_name) {
							$tray_card_cols[$i]->special_reqs .= $sr->name . ", ";
						}
					}
					if (strtotime($tray_card_info['main_data']->date_of_birth) == strtotime(date('Y-m-d', strtotime('now')))) {
						$tray_card_cols[$i]->birthday = true;
					} else {
						$tray_card_cols[$i]->birthday = false;
					}
					if (isset ($tray_card_cols[$i]->beverages)) {
						$tray_card_cols[$i]->beverages = rtrim($tray_card_cols[$i]->beverages, ",");
					}
					if (isset ($tray_card_cols[$i]->special_reqs)) {
						$tray_card_cols[$i]->special_reqs = rtrim($tray_card_cols[$i]->special_reqs, ",");						
					}
				}
			}
			$all_tray_cards = false;
		}


		smarty()->assign('trayCardCols', $tray_card_cols);
		// smarty()->assign('patient', $patient);
		smarty()->assign('selectedDate', $_dateStart);
		smarty()->assign('allTrayCards', $all_tray_cards);
	}



 //  public function traycard() {
 //  	explode(delimiter, string)
 //  	ini_set('memory_limit','-1');
 //    $location = $this->getLocation();
	// $html = "";

 //    if(input()->patient == "all"){
	// 		// check if the location is has the admission dashboard enabled
	// 		$modEnabled = ModuleEnabled::isAdmissionsEnabled($location->id);

	// 		// if the facility is using the admission dashboard, then get a list of
	// 		// the current patients from the admission app for the current location.

	// 		// NOTE: if a location is using the admission dashboard they should
	// 		// not have the ability to add or delete patients through the dietary
	// 		// app interface.
	// 		$rooms = $this->loadModel("Room")->fetchEmpty($location->id);
	// 		if ($modEnabled) {
	// 			// until the admission app is re-built and we move to a single database we need to fetch
	// 			// the data from the admission db and save to the master db
	// 			// IMPORTANT: Remove this after admission app is re-built in new framework!!!
	// 			$scheduled = $this->loadModel('AdmissionDashboard')->syncCurrentPatients($location->id);
	// 		} else {
	// 			// if the locations is not using the admission dashboard then load the patients
	// 			// from ac_patient and dietary_patient_info tables
	// 			// fetch current patients
	// 			$scheduled = $this->loadModel("Patient")->fetchPatients($location->id);
	// 		}
	// 		$currentPatients = $this->loadModel("Room")->mergeRooms($rooms, $scheduled);


	//     foreach($currentPatients as $key => $patient){
	//     	if(get_class($patient) == "Patient"){
	//     		$htmlArray = $this->createHtml($patient, $location, $html, false);
 //  				$html = $htmlArray["html"];
	//     	}
	//     }
 //    }
 //    else{
	//   	if (input()->patient != "") {
	//     	//$patient = $this->loadModel("Patient", input()->patient);

	//     	// created query to get the room number with the patient info - 2016-02-24 by kwh
	//     	$patient = $this->loadModel("Patient")->fetchPatientById(input()->patient);
	//     	$htmlArray = $this->createHtml($patient, $location, $html, true);
	//     	$html = $html . $htmlArray["html"];
	//   	}
	// 	  else {
	// 	    session()->setFlash("Could not find the selected patient, please try again.", 'error');
	// 	    $this->redirect();
	// 	  }
 //    }


	// 	$pdfDetails = array("title" => '', "html" => $htmlArray["html"], "header" => false, "footer" => false, "orientation" => "Landscape", "top_margin" => 0, "font_size" => 10, "custom_footer" => false);

	// 	$this->buildPDFOptions($pdfDetails);

 //  }

	public function traycard_options(){
		// get the location
		$location = $this->getLocation();

		if (isset (input()->patient)) {
			$patient = $this->loadModel('Patient', input()->patient);
		} else {
			$patient = $this->loadModel('Patient');
		}

		if (isset (input()->date)) {
			$selectedDate = mysql_date(input()->date);
		} else {
			$selectedDate = mysql_date();
		}

		// check if the user has permission to access this module
		if ($location->location_type != 1) {
			$this->redirect();
		}
		$rooms = $this->loadModel("Room")->fetchEmpty($location->id);

		$scheduled = $this->loadModel("Patient")->fetchPatients($location->id);
		$currentPatients = $this->loadModel("Room")->mergeRooms($rooms, $scheduled);

		smarty()->assign('currentPatients', $currentPatients);
		smarty()->assign('patient', $patient);
		smarty()->assign('selectedDate', $selectedDate);

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



  private function createHtml($patient, $location, $html, $single_patient){

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

  $single_meal_footer_html = <<<EOD
	    	<table cellpadding="4">
	    		<tr>
	    			<td colspan="2"><strong>{$patient->first_name} {$patient->last_name}</strong></td>
	    			<td text-align="right">{$patient->number}</td>
	    		</tr>
	    		<tr>
	    			<td>Consumed</td>
	    			<td colspan="2">0 0-25 26-50 51-75 76-100 100</td>
	    		</tr>
	    	</table>
EOD;

  //Are we looking for specific meal?
	if(!isset(input()->meal) || input()->meal == "All"){
		$breakfast_beverages = $this->loadModel("PatientBeverage")->fetchPatientBeverage($patient->id, "Breakfast");
		$lunch_beverages = $this->loadModel("PatientBeverage")->fetchPatientBeverage($patient->id, "Lunch");
		$dinner_beverages = $this->loadModel("PatientBeverage")->fetchPatientBeverage($patient->id, "Dinner");
    $menuItems[0]->meal = "Breakfast";
    $menuItems[0]->beverages = $breakfast_beverages;
    $menuItems[1]->meal = "Lunch";
    $menuItems[1]->beverages = $lunch_beverages;
    $menuItems[2]->meal = "Dinner";
    $menuItems[2]->beverages = $dinner_beverages;

	  	    	$footer_html = <<<EOD
	    	<table cellpadding="4">
	    		<tr>
	    			<td colspan="2"><strong>{$patient->first_name} {$patient->last_name}</strong></td>
	    			<td text-align="right">{$patient->number}</td>
	    			<td colspan="2"><strong>{$patient->first_name} {$patient->last_name}</strong></td>
	    			<td text-align="right">{$patient->number}</td>
	    			<td colspan="2"><strong>{$patient->first_name} {$patient->last_name}</strong></td>
	    			<td text-align="right">{$patient->number}</td>
	    		</tr>
	    		<tr>
	    			<td>Consumed</td>
	    			<td colspan="2">0 0-25 26-50 51-75 76-100 100</td>
	    			<td>Consumed</td>
	    			<td colspan="2">0 0-25 26-50 51-75 76-100 100</td>
	    			<td>Consumed</td>
	    			<td colspan="2">0 0-25 26-50 51-75 76-100 100</td>
	    		</tr>
	    	</table>
EOD;
  }
  else{
    if(input()->meal == "Breakfast"){
			$breakfast_beverages = $this->loadModel("PatientBeverage")->fetchPatientBeverage($patient->id, "Breakfast");

      $menuItems[0]->meal = "Breakfast";
    	$menuItems[0]->beverages = $breakfast_beverages;
      $menuItems[1]->meal = "";
      $menuItems[2]->meal = "";
	  	    	$footer_html = $single_meal_footer_html;

    }
    elseif (input()->meal == "Lunch") {
			$lunch_beverages = $this->loadModel("PatientBeverage")->fetchPatientBeverage($patient->id, "Lunch");
      $menuItems[0]->meal = "Lunch";
    	$menuItems[0]->beverages = $lunch_beverages;
      $menuItems[1]->meal = "";
      $menuItems[2]->meal = "";
	  	    	$footer_html = $single_meal_footer_html;
    }
    elseif (input()->meal == "Dinner") {
			$dinner_beverages = $this->loadModel("PatientBeverage")->fetchPatientBeverage($patient->id, "Dinner");
      $menuItems[0]->meal = "Dinner";
    	$menuItems[0]->beverages = $dinner_beverages;
      $menuItems[1]->meal = "";
      $menuItems[2]->meal = "";
	  	    	$footer_html = $single_meal_footer_html;
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
  $patientDietInfo = $this->loadModel('PatientDietInfo')->fetchPatientDietInfo($patient->id);


  $birthday = false;
  if(date('m-d') == substr($patient->date_of_birth,5,5)){
    $birthday = true;
  };

  $diet_names = array();

  foreach($patientDietInfo as $diet){
  	if ($diet->patient_id){
    	array_push($diet_names, $diet->name);
    }
  }
  $diet_names = implode(', ', $diet_names);

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

  $_dateStart = date("M jS, Y", strtotime($_dateStart));

//Here's where we make the rows modular so they can choose specific meals instead of always printing all of them
$headerRow = <<<EOD
<th ><strong>{$menuItems[0]->meal}</strong></th>
<th>{$_dateStart}</th>
<th ><strong>{$menuItems[1]->meal}</strong></th>
<th>{$_dateStart}</th>
<th ><strong>{$menuItems[2]->meal}</strong></th>
<th>{$_dateStart}</th>
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
    $consumedAmountRow = "";
    $beverageRow = "";
    $dietOrderRow = "";

    foreach($menuItems as $item){
if ($item->meal != ""){

$nameRow = $nameRow . <<<EOD
  <td><strong>{$patient->first_name} {$patient->last_name}</strong></td>
  <td></td>
EOD;

$consumedRow = $consumedRow . <<<EOD
  <td>Meal Consumed: </td>
  <td></td>
EOD;

$consumedAmountRow = $consumedAmountRow . <<<EOD
  <td colspan="2">0 0-25 51-75 76-100 100 </td>

EOD;

$dietOrderRow = $dietOrderRow . <<<EOD
  <td >Diet Order: </td>
  <td >{$diet_names}</td>

EOD;

$birthdayRow = $birthdayRow . <<<EOD
  <td colspan="2" class="birthday">Happy Birthday!</td>

EOD;

	$beverages = array();
  if($item->beverages){
	  foreach($item->beverages as $beverage){
	    array_push($beverages, $beverage->name);
	  }
	}
	else{
  	array_push($beverages, "");
	}
  $beverages = implode(', ', $beverages);

$beverageRow = $beverageRow . <<<EOD
  <td>Beverages: </td>
  <td>{$beverages}</td>
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

$content = str_replace("\n", "<br>", $item->content); 

//pr($content); exit;

$contentRow = $contentRow . <<<EOD
  <td colspan="2">{$content}</td>
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
  {$footer_html}
  <br>
  <br>
  <table cellpadding="4">
    <thead>
      <tr>
      	{$headerRow}
      </tr>
    </thead>
    <tbody>
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
				{$dietOrderRow}
			</tr>
      <tr>
        {$textureRow}
      </tr>
      <tr>
      	{$portionRow}
      </tr>
      <tr>
      	{$allergyRow}
      </tr>


      <tr>
      	{$orderRow}
      </tr>
      <tr>
      	{$beverageRow}
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
      
      
EOD;
	if(!$single_patient){
		$html = $html . '<br pagebreak="true"/>';
	}

		//$html = $footer_html . $html;
  
		//
		$htmlArray = array("html" => $html, "footer_html" => $footer_html);

		return $htmlArray;
	}



}
