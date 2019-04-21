<?php

class PatientInfoController extends DietaryController {

	// protected $navigation = 'dietary';
	// protected $searchBar = 'dietary';




/*
 * -------------------------------------------------------------------------
 *  EDIT DIET PAGE
 * -------------------------------------------------------------------------
 */
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
		$location = $this->loadModel("Location", $patientInfo->location_id);

		// fetch the allergies, dislikes and snacks
		$allergies = $this->loadModel("PatientFoodInfo")->fetchPatientAllergies($patient->id);
		$dislikes = $this->loadModel("PatientFoodInfo")->fetchPatientDislikes($patient->id);

		// Fetch snacks for each time of day
		$am_snacks = $this->loadModel("PatientSnack")->fetchPatientSnacks($patient->id, "am");
		$pm_snacks = $this->loadModel("PatientSnack")->fetchPatientSnacks($patient->id, "pm");
		$bedtime_snacks = $this->loadModel("PatientSnack")->fetchPatientSnacks($patient->id, "bedtime");


		// fetch special requests
		$breakfast_spec_req =  $this->loadModel("PatientSpecialReq")->fetchSpecialRequestsByPatient($patient->id, 1);
		$lunch_spec_req =  $this->loadModel("PatientSpecialReq")->fetchSpecialRequestsByPatient($patient->id, 2);
		$dinner_spec_req =  $this->loadModel("PatientSpecialReq")->fetchSpecialRequestsByPatient($patient->id, 3);


		// Fetch beverages for each meal
		$breakfast_beverages = $this->loadModel("PatientBeverage")->fetchPatientBeverage($patient->id, 1);
		$lunch_beverages = $this->loadModel("PatientBeverage")->fetchPatientBeverage($patient->id, 2);
		$dinner_beverages = $this->loadModel("PatientBeverage")->fetchPatientBeverage($patient->id, 3);

		$adapt_equip = $this->loadModel("PatientAdaptEquip")->fetchPatientAdaptEquip($patient->id);
		$supplements = $this->loadModel("PatientSupplement")->fetchPatientSupplement($patient->id);



		// NOTE: The three foreach loops below to get data for the page view could be consolidated and called
		// from a single private function


		// Patient Diet Info (Diet Order)
		$diet_order = $this->loadModel("PatientDietOrder")->fetchPatientDietOrder($patient->id);
		$order_array = array();
		$order_array['standard'] = array();
		$order_array['other'] = "";
		if (!empty ($diet_order)) {
			foreach ($diet_order as $order) {
				$order_array['standard'][] .= $order->name;
				if ($order->is_other) {
					$order_array['other'] = $order->name;
				}
			}
		}

		// Patient textures
		$patient_textures = $this->loadModel("PatientTexture")->fetchPatientTexture($patient->id);
		$texture_array = array();
		$texture_array['standard'] = array();
		$texture_array['other'] = "";

		if (!empty ($patient_textures)) {
			foreach ($patient_textures as $pt) {
				$texture_array['standard'][] .= $pt->name;
				if ($pt->is_other) {
					$texture_array['other'] = $pt->name;
				}
			}
		}

		// Patient's other items
		$patient_other = $this->loadModel("PatientOther")->fetchPatientOther($patient->id);
		$other_array = array();
		$other_array["standard"] = array();
		$other_array["other"] = "";

		if (!empty ($patient_other)) {
			foreach ($patient_other as $other) {
				$other_array["standard"][] .= $other->name;
				if ($other->is_other) {
					$other_array["other"] = $other->name;
				}
			}
		}


		smarty()->assignByRef('patient', $patient);
		smarty()->assignByRef('patientInfo', $patientInfo);
		smarty()->assignByRef('allergies', $allergies);
		smarty()->assignByRef('dislikes', $dislikes);
		smarty()->assignByRef('adaptEquip', $adapt_equip);
		smarty()->assignByRef('breakfast_spec_req', $breakfast_spec_req);
		smarty()->assignByRef('lunch_spec_req', $lunch_spec_req);
		smarty()->assignByRef('dinner_spec_req', $dinner_spec_req);
		smarty()->assignByRef('breakfast_beverages', $breakfast_beverages);
		smarty()->assignByRef('lunch_beverages', $lunch_beverages);
		smarty()->assignByRef('dinner_beverages', $dinner_beverages);
		smarty()->assignByRef('supplements', $supplements);
		smarty()->assignByRef('am_snacks', $am_snacks);
		smarty()->assignByRef('pm_snacks', $pm_snacks);
		smarty()->assignByRef('bedtime_snacks', $bedtime_snacks);
		smarty()->assign('selectedLocation', $location);

		smarty()->assign("dietOrder", $order_array);
		smarty()->assign("textures", $texture_array);
		smarty()->assign("other", $other_array);
	}





/*
 * -------------------------------------------------------------------------
 *  Save items from the Edit Diet page
 * -------------------------------------------------------------------------
 */
	public function save_diet() {

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
		if (input()->date_of_birth != "") {
			$patient->date_of_birth = mysql_date(input()->date_of_birth);
		} else {
			$patient->date_of_birth = null;
		}

		// if input fields are not empty then set the data
/*		if (input()->height != "") {
			$patientDiet->height = input()->height;
		}

		if (input()->weight != "") {
			$patientDiet->weight = input()->weight;
		}*/


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

		$spec_reqs_array = array();
		if (!empty (input()->breakfast_specialrequest)) {
			foreach (input()->breakfast_specialrequest as $item) {
				$spec_req = $this->loadModel("SpecialReq")->fetchByName($item);
				$deleteSpecialReq = $this->loadModel("PatientSpecialReq")->deleteSpecialReqs($patient->id, $item, 1);
				$patient_spec_req = $this->loadModel("PatientSpecialReq");
				$patient_spec_req->patient_id = $patient->id;
				$patient_spec_req->special_req_id = $spec_req->id;
				$patient_spec_req->meal = 1;
				$spec_reqs_array[] = $patient_spec_req;

			}
		}
		if (!empty (input()->lunch_specialrequest)) {
			foreach (input()->lunch_specialrequest as $item) {
				$spec_req = $this->loadModel("SpecialReq")->fetchByName($item);
				$patient_spec_req = $this->loadModel("PatientSpecialReq");
				$patient_spec_req->patient_id = $patient->id;
				$patient_spec_req->special_req_id = $spec_req->id;
				$patient_spec_req->meal = 2;
				$spec_reqs_array[] = $patient_spec_req;

			}
		}

		if (!empty (input()->dinner_specialrequest)) {
			foreach (input()->dinner_specialrequest as $item) {
				$spec_req = $this->loadModel("SpecialReq")->fetchByName($item);
				$patient_spec_req = $this->loadModel("PatientSpecialReq");
				$patient_spec_req->patient_id = $patient->id;
				$patient_spec_req->special_req_id = $spec_req->id;
				$patient_spec_req->meal = 3;
				$spec_reqs_array[] = $patient_spec_req;

			}
		}


		// set beverages array
		$beveragesArray = array();
		if (!empty (input()->breakfast_beverages)) {
			foreach (input()->breakfast_beverages as $item) {
				$beverage = $this->loadModel("Beverage")->fetchByName($item);
				$deletePatientBeverages = $this->loadModel("PatientBeverage")->deletePatientBevs($patient->id);

				// create new empty array
				$patientBeverage = $this->loadModel("PatientBeverage");
				$patientBeverage->patient_id = $patient->id;
				$patientBeverage->beverage_id = $beverage->id;
				$patientBeverage->meal = 1;
				$beveragesArray[] = $patientBeverage;
			}
		}

		// set beverages array
		if (!empty (input()->lunch_beverages)) {
			foreach (input()->lunch_beverages as $item) {
				$beverage = $this->loadModel("Beverage")->fetchByName($item);
				$patientBeverage = $this->loadModel("PatientBeverage");
				$patientBeverage->patient_id = $patient->id;
				$patientBeverage->beverage_id = $beverage->id;
				$patientBeverage->meal = 2;
				$beveragesArray[] = $patientBeverage;
			}
		}

		// set beverages array
		if (!empty (input()->dinner_beverages)) {
			foreach (input()->dinner_beverages as $item) {
				$beverage = $this->loadModel("Beverage")->fetchByName($item);
				$patientBeverage = $this->loadModel("PatientBeverage");
				$patientBeverage->patient_id = $patient->id;
				$patientBeverage->beverage_id = $beverage->id;
				$patientBeverage->meal = 3;
				$beveragesArray[] = $patientBeverage;
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


		// set diet_order array
		$patientDietOrderArray = array();
		if (!empty (input()->diet_order)) {
			// check if the patient already has an "other" item saved.
			// if there is something... delete it.
			$this->loadModel("PatientDietOrder")->removeOtherItems($patient->id, 'DietOrder');
			$this->loadModel("PatientDietOrder")->removePatientDietItems($patient->id);

			//remove sepecial requests and beverages
			$this->loadModel("PatientSpecialReq")->removePatientDietItems($patient->id);
			$this->loadModel("PatientBeverage")->removePatientDietItems($patient->id);

			foreach (input()->diet_order as $item) {
				$diet_order = $this->loadModel("DietOrder")->fetchByName($item, true);
				if (!empty ($diet_order)) {
					$patientDietOrder = $this->loadModel("PatientDietOrder")->fetchByPatientAndDietOrderId($patient->id, $diet_order->id);

					if ($patientDietOrder->patient_id == "") {
						$patientDietOrder->patient_id = $patient->id;
						$patientDietOrder->diet_order_id = $diet_order->id;
						$patientDietOrderArray[] = $patientDietOrder;
					}
				}
			}
		} else {
			$feedback[] = "Diet order has not been entered";
		}
		
		if (input()->puree !== null) {
		}

		// set texture array
		$texture_entered = false;
		foreach (input()->texture as $texture) {
			if ($texture != "") {
				$texture_entered = true;
				break;
			}
		}

		if (input()->puree !== null) {
			$texture_entered = true;
		}
		
		if ($texture_entered) {
			// check if the patient already has an "other" item saved.
			// if there is something... delete it.
			$this->loadModel("PatientTexture")->removeOtherItems($patient->id, 'Texture');
			$this->loadModel("PatientTexture")->removePatientDietItems($patient->id);

			$puree_item = $this->loadModel("Texture")->fetchByName(input()->puree, false, false, true);
			$patient_puree = $this->loadModel("PatientTexture")->fetchByPatientAndTextureId($patient->id, $puree_item->id);
			if ($patient_puree->id == null) {
				$patient_puree->texture_id = $puree_item->id;
				$patient_puree->patient_id = $patient->id;
				$patient_puree->save();
			}

			foreach (input()->texture as $item) {
				if ($item != "") {
					$texture_item = $this->loadModel("Texture")->fetchByName($item, true, true);
					$patientTexture = $this->loadModel("PatientTexture")->fetchByPatientAndTextureId($patient->id, $texture_item->id);
					if ($patientTexture->patient_id == "") {
						$patientTexture->patient_id = $patient->id;
						$patientTexture->texture_id = $texture_item->id;
						$patientTextureArray[] = $patientTexture;
					}
				} else {
					$feedback[] = "Diet texture has not been entered";
				}
				
			}
		} else {
			$feedback[] = "Diet texture has not been entered";
		}

		// set other array
		$patient_other_array = array();
		if (!empty (input()->other)) {
			$this->loadModel("PatientOther")->removeOtherItems($patient->id, 'Other');
			$this->loadModel("PatientOther")->removePatientDietItems($patient->id);
			foreach (input()->other as $item) {
				$other_item = $this->loadModel("Other")->fetchByName($item, true, false, false);
				$patientOther = $this->loadModel("PatientOther")->fetchByPatientAndOtherId($patient->id, $other_item->id);

				if ($patientOther->patient_id == "") {
					$patientOther->patient_id = $patient->id;
					$patientOther->other_id = $other_item->id;
					$patient_other_array[] = $patientOther;
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

		if (isset (input()->special_requests)) {
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
			foreach ($patientDietOrderArray as $item) {
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


			// save the patient's special requests
			foreach ($spec_reqs_array as $item) {
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
			foreach ($patient_other_array as $item) {
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
		    		$tray_card_info[] = $this->loadModel('PatientInfo')->fetchTrayCardInfo($patient->id, $location->id);
		    	}
		    }

		} else {
			// fetch info for only the selected patient
			$patient = $this->loadModel("Patient")->fetchPatientById(input()->patient);
			// fetch the patient's tray card info
			$tray_card_info = $this->loadModel('PatientInfo')->fetchTrayCardInfo($patient->id, $location->id);
		}

		// get date from the url
		if(isset(input()->date)){
			$_dateStart = date('Y-m-d', strtotime(input()->date));
			$_month_day = date('m-d', strtotime(input()->date));
		} else{
			$_dateStart = date('Y-m-d', strtotime('now'));
			$_month_day = date('m-d', strtotime('now'));
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
		$meals = array(1,2,3);
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
						if ($b->meal == $meals[$i]) {
							$tray_card_cols[$key][$i]->beverages .= $b->name . ", ";
						}
					}
					$tray_card_cols[$key][$i]->special_reqs = "";
					foreach ($tci['items_by_meal']['special_reqs'] as $k => $sr) {
						if ($sr->meal == $meals[$i]) {
							$tray_card_cols[$key][$i]->special_reqs .= $sr->name . ", ";
						}
					}

					if ($tci['main_data']->date_of_birth != '' && date('m-d', strtotime($tci['main_data']->date_of_birth)) == $_month_day) {
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
					if ($b->meal == $meals[$i]) {
						$tray_card_cols[$i]->beverages .= $b->name . ', ';
					}
				}
				$tray_card_cols[$i]->special_reqs = "";
				foreach ($tray_card_info['items_by_meal']['special_reqs'] as $k => $sr) {
					if ($sr->meal == $meals[$i]) {
						$tray_card_cols[$i]->special_reqs .= $sr->name .= ', ';
					}
				}
				if (strtotime($tci['main_data']->date_of_birth) != "" && date('m-d', strtotime($tray_card_info['main_data']->date_of_birth)) == $_month_day) {
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
						if ($b->meal == $meals[$i]) {
							$tray_card_cols[$i]->beverages .= $b->name . ', ';
						}
					}
					$tray_card_cols[$i]->special_reqs = "";
					foreach ($tray_card_info['items_by_meal']['special_reqs'] as $k => $sr) {
						if ($sr->meal == $meals[$i]) {
							$tray_card_cols[$i]->special_reqs .= $sr->name . ', ';
						}
					}
					if (strtotime($tray_card_info['main_data']->date_of_birth) != "" && date('m-d', strtotime($tray_card_info['main_data']->date_of_birth)) == $_month_day) {
						$tray_card_cols[$i]->birthday = true;
					} else {
						$tray_card_cols[$i]->birthday = false;
					}
					if (isset ($tray_card_cols[$i]->beverages)) {
						$tray_card_cols[$i]->beverages = rtrim($tray_card_cols[$i]->beverages, ', ');
					}
					if (isset ($tray_card_cols[$i]->special_reqs)) {
						$tray_card_cols[$i]->special_reqs = rtrim($tray_card_cols[$i]->special_reqs, ', ');
					}
				}
			}
			$all_tray_cards = false;
		}
		
		smarty()->assign('trayCardCols', $tray_card_cols);
		// smarty()->assign('patient', $patient);
		smarty()->assign('selectedDate', $_dateStart);
		smarty()->assign('allTrayCards', $all_tray_cards);
		smarty()->assign('location', $location);
	}




/*
 * -------------------------------------------------------------------------
 *  TRAYCARD OPTIONS PAGE
 * -------------------------------------------------------------------------
 *
 * This page allows the selection of a tray card for a specific date and/or
 * meal. It is accessed from the patient's wrench menu with "Selected Tray
 * Card".
 *
 */
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


/*
 * -------------------------------------------------------------------------
 *  ADD A NEW PATIENT PAGE
 * -------------------------------------------------------------------------
 *
 * This page allows the adding of a new patient for those locations not using
 * the admission dashboard module.
 *
 */
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



/*
 * -------------------------------------------------------------------------
 *  SAVE THE NEWLY ADDED PATIENT
 * -------------------------------------------------------------------------
 */
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
			switch (input()->type) {
				case "allergy":
					if ($this->loadModel("PatientFoodInfo")->deleteFoodInfoItem($patient->id, input()->name, input()->type)) {
						return true;
					}
					break;

				case "dislike":
					if ($this->loadModel("PatientFoodInfo")->deleteFoodInfoItem($patient->id, input()->name, input()->type)) {
						return true;
					}
					break;

				case "snack":
					if ($this->loadModel("PatientSnack")->deleteSnack($patient->id, input()->name, input()->time)) {
						return true;
					}
					break;

				case "adapt_equip":
					if ($this->loadModel("PatientAdaptEquip")->deleteAdaptEquip($patient->id, input()->name)) {
						return true;
					}
					break;

				case "supplement":
					if ($this->loadModel("PatientSupplement")->deleteSupplement($patient->id, input()->name)) {
						return true;
					}
					break;

				case "special_request":
					if ($this->loadModel("PatientSpecialReq")->deleteSpecialReq($patient->id, input()->name, input()->meal)) {
						return true;
					}
					break;

				case "beverage":
					if ($this->loadModel("PatientBeverage")->deleteBeverage($patient->id, input()->name, input()->meal)) {
						return true;
					}
					break;

			}



			// if (input()->type == "allergy") {
			// 	if ($this->loadModel("PatientFoodInfo")->deleteFoodInfoItem($patient->id, input()->name, input()->type)) {
			// 		return true;
			// 	}
			// } elseif (input()->type == "snack") {
			// 	if ($this->loadModel("PatientSnack")->deleteSnack($patient->id, input()->name, input()->time)) {
			// 		return true;
			// 	}
			// } elseif (input()->type == "adapt_equip") {
			// 	if ($this->loadModel("PatientAdaptEquip")->deleteAdaptEquip($patient->id, input()->name)) {
			// 		return true;
			// 	}
			// } elseif (input()->type == "supplement") {
			// 	if ($this->loadModel("PatientSupplement")->deleteSupplement($patient->id, input()->name)) {
			// 		return true;
			// 	}
			// } elseif (input()->type == "special_request") {
			// 	if ($this->loadModel("PatientSpecialReq")->deleteSpecialReq($patient->id, input()->name, input()->meal)) {
			// 		return true;
			// 	}
			// }


			return false;
		}

		return false;
	}



	public function saveFoodItems($items = array(), $patient_id = null, $snackTime = null) {
		if (!empty($items)) {
			$snackArray = array();
			// delete all snacks for this patient
			$this->loadModel("PatientSnack")->deleteSnacksByPatientId($patient_id);

			foreach ($items as $key => $snack) {
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



}
