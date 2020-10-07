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
		

		$PatientInfo_model =& $this->loadModel('PatientInfo');
		// get the diet info for the selected patient
		$patientInfo = $PatientInfo_model->fetchDietInfo2($patient->id);
		$location = $this->loadModel("Location", $patientInfo->location_id);
		$atomicFetch = $PatientInfo_model->fetchTrayCardInfoByPatient($patient->id);
		/*
		echo "<pre>";
		print_r($PatientInfo_model->fetchTrayCardInfoByPatient($patient->id));
		die();*/
		
		//lookup array for variables later.
		$meals = array(1=> 'breakfast', 2=> 'lunch', 3=> 'dinner');
		
		$order_array = array();
		$order_array['standard'] = array();
		$order_array['other'] = "";
		
		$texture_array = array();
		$texture_array['standard'] = array();
		$texture_array['other'] = "";
		
		$other_array = array();
		$other_array["standard"] = array();
		$other_array["other"] = "";
		
		foreach($atomicFetch as $k => $v)
		{
			/*
		    [id] => 21
			[name] => green beans
			[meal] => 
			[is_other] => 
			[type] => allergy
			[sort_index] => 
			[cat_sort] => 0 */
			//echo $v->id . "" . $v->name . "" . $v->meal . "" . $v->is_other . "" .  $v->type . "" .  $v->sort_index . "" . "<br/>\n";
			switch($v->type) {
				case 'allergy': 
					$allergies[$k] = (object) array(
						'id' 	=> $v->id,
						'name'	=> $v->name,
					);
					break;
				case 'dislike': 
					$dislikes[$k] = (object) array(
						'id' 	=> $v->id,
						'name'	=> $v->name,
					);
					break;
				case 'adapt_equip': 
					$adapt_equip[$k] = (object) array(
						'id' 	=> $v->id,
						'name'	=> $v->name,
					);
					break;
				case 'supplement': 
					$supplements[$k] = (object) array(
						'id' 	=> $v->id,
						'name'	=> $v->name,
					);
					break;
				case 'special_req':
					${"{$meals[$v->meal]}_spec_req"}[$k] = (object) array(
						'id' 	=> $v->id,
						'name'	=> $v->name,
					);
					break;
				case 'beverage': 
					${"{$meals[$v->meal]}_beverages"}[$k] = (object) array(
						'id' 	=> $v->id,
						'name'	=> $v->name,
					);
					break;
				//Snacks are ENUM in DB to be the same as variable names, but needed to sort on number later, to avoid adding a column I reused is_other....
				case 'snack': 
					${"{$v->is_other}_snacks"}[$k] = (object) array(
						'id' 	=> $v->id,
						'name'	=> $v->name,
					);
					break;
				case 'order': 
					if(!$v->is_other){
						$order_array['standard'][] .= $v->name;
					} else {
						$order_array['other'] = $v->name;
					}
					break;
				case 'liquid': //I query the db twice, so liquids belong in textures too.
				case 'texture': 
					if(!$v->is_other){
						$texture_array['standard'][] .= $v->name;
					} else {
						$texture_array['other'] = $v->name;
					}
					break;
				case 'other': 
					if(!$v->is_other){
						$other_array['standard'][] .= $v->name;
					} else {
						$other_array['other'] = $v->name;
					}
					break;

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
		//echo "<pre>";
		//print_r(input());
		//exit();
		$feedback = array();
		if (input()->patient != "") {
			$patient = $this->loadModel("Patient", input()->patient);
		} else {
			session()->setFlash("Could not find the patient.", 'error');
			$this->redirect(input()->currentUrl);
		}

		$patientInfo = $this->loadModel("PatientInfo");
		$patientDiet = $patientInfo->fetchDietInfo($patient->id);
		$patientDiet->patient_id = $patient->id;

		$patient->first_name = input()->first_name;
		$patient->last_name = input()->last_name;
		if (input()->date_of_birth != "") {
			$patient->date_of_birth = mysql_date(input()->date_of_birth);
		} else {
			$patient->date_of_birth = null;
		}
		
		$listofsubs = array("allergies", "dislikes", "adaptEquip", "supplements", "breakfast_specialrequest", "lunch_specialrequest", "dinner_specialrequest", "breakfast_beverages", "lunch_beverages", "dinner_beverages", "am", "pm", "bedtime", "diet_order", "texture", "other");
		
		
		//surpress errors and make sure that save function runs with data that it can process.
		foreach($listofsubs as $k => $formCategory)
		{
			if(empty(input()->$formCategory))
			{
				input()->$formCategory = array();
			}
		}
		
		if (!empty(input()->table_number)) {
			$patientDiet->table_number = input()->table_number;
			$feedback[] = "Table Number Saved.";
			
		} else {
			$patientDiet->table_number = null;
		}

		if (!empty(input()->diet_order_other)) {
			$patientDiet->diet_info_other = input()->diet_order_other;
			$feedback[] = "Other  Diet  Saved.";
			
		} else {
			$patientDiet->diet_info_other = null;
		}

		if (!empty(input()->texture_other)) {
			$patientDiet->texture_other = input()->texture_other;
			$feedback[] = "Other Texture Saved.";
			
		} else {
			$patientDiet->texture_other = null;
		}

		if (!empty(input()->fluid_other)) {
			$patientDiet->fluid_other = input()->fluid_other;
			$feedback[] = "Fluid Restriction Saved.";
			
		} else {
			$patientDiet->fluid_other = null;
		}
		//Jason DB Save
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "allergy", input()->allergies, "allergy", 1, "food_info", "food_id");
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "dislike", input()->dislikes, "allergy", 0, "food_info", "food_id");
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "adapt_equip", input()->adaptEquip);
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "supplement", input()->supplements);
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "special_req", input()->breakfast_specialrequest, "meal", 1);
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "special_req", input()->lunch_specialrequest, "meal", 2);
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "special_req", input()->dinner_specialrequest, "meal", 3);
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "beverage", input()->breakfast_beverages, "meal", 1);
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "beverage", input()->lunch_beverages, "meal", 2);
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "beverage", input()->dinner_beverages, "meal", 3);
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "snack", input()->am, "time", "am");
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "snack", input()->pm, "time", "pm");
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "snack", input()->bedtime, "time", "bedtime");
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "diet_order", input()->diet_order);
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "texture", input()->texture);
		$feedback[] = $patientInfo->save_dietary_patient($patient->id, "other", input()->other);
		
		
		if (!empty(input()->portion_size)) {
			$patientDiet->portion_size = input()->portion_size;
		} else {
			$feedback[] = "Portion size has not been entered";
		}
		
		$patientDiet->save();
		$patient->save();
		
		//exit();

		$location = $this->loadModel("Location", $patientDiet->location_id);
		session()->setFlash(array("Diet Info was saved for {$patient->fullName()}", $feedback), "success");
		$this->redirect(array("module" => "Dietary", "page" => "Dietary", "location" => $location->public_id));

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
		// if user is not authorized to access this page, then re-direct
		if (!auth()->getRecord()) {
			$this->redirect();
		}
		// this page will always create a PDF
		$this->template = 'pdf';
		
		// get date from the url
		$_dateStart = "";
		$_month_day = "";
		if(isset(input()->date) && input()->date != ""){
			$_dateStart = date('Y-m-d', strtotime(input()->date));
			$_month_day = date('m-d', strtotime(input()->date));
		} else{
			$_dateStart = date('Y-m-d', strtotime('now'));
			$_month_day = date('m-d', strtotime('now'));
		}
		
		if(isset(input()->pdf2) && input()->pdf2 == true) {
			$this->template = "pdf2";
			$this->landscape_array = true;
			$this->margins = 0;
			@$this->pdfName = "meal_tray_cards_".$_dateStart.".pdf";
		}
		// fetch the location
		$location = $this->getLocation();
		
		// fetch data
		@$tray_card_info = $this->loadModel('PatientInfo')->fetchTrayCardInfo(input()->patient, $location->id);
		$tray_card_cols = array();

		/*
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
		*/
		
		//print_r($tray_card_info);
		




		/*
		// get the meal id from the url
		if (isset (input()->meal_id)) {
			$meal_id = input()->meal_id;
		} else {
			$meal_id = "all";
		}*/

		//tray cards still loading lots of extra data.
		//$menu = $this->loadModel('Menu')->fetchMenu($location->id, $_dateStart);
		//$numDays = $this->loadModel('MenuItem')->fetchMenuDay($menu->menu_id);
		//$startDay = round($this->dateDiff($menu->date_start, $_dateStart) % $numDays->count + 1);
		//$menuItems = $this->loadModel('MenuItem')->fetchMenuItems($location->id, $_dateStart, $_dateStart, $startDay, $startDay, $menu->menu_id, $meal_id);


		$meal_names = array(0 => "Breakfast", 1 => "Lunch", 2 => "Dinner");
		
		//$meals = array(1,2,3);
		foreach ($tray_card_info as $key => $tci) {
			//$tci->iddsi_food = "FOOD!"; 
			//$tci->iddsi_liqu = "LIQUID!";
			
			//process texture into icons:
			$food_temp = null;
			$liqu_temp = null;
			if(strpos($tci->textures, "Liquidised") !== false)
			{
				$food_temp = "Down_3_Liquidized.png";
			} elseif(strpos($tci->textures, "Puree") !== false)
			{
				$food_temp = "Down_4_Pureed.png";
			}elseif(strpos($tci->textures, "Minced & Moist") !== false)
			{
				$food_temp = "Down_5_Minced_Moist.png";
			}elseif(strpos($tci->textures, "Soft & Bite Sized") !== false)
			{
				$food_temp = "Down_6_Soft_Bite-Sized.png";
			}elseif(strpos($tci->textures, "Easy to Chew") !== false)
			{
				$food_temp = "Down_7_RegularEC.png";
			}elseif(strpos($tci->textures, "Regular") !== false)
			{
				//$food_temp = "Down_7_Regular.png";
			}
			
			if(strpos($tci->textures, "Thin") !== false)
			{
				$liqu_temp = "0_Thin.png";
			} elseif(strpos($tci->textures, "Slightly Thick") !== false)
			{
				$liqu_temp = "1_SlightlyThick.png";
			}elseif(strpos($tci->textures, "Mildly Thick") !== false)
			{
				$liqu_temp = "2_Midly_Thick.png";
			}elseif(strpos($tci->textures, "Moderately Thick") !== false)
			{
				$liqu_temp = "3_Moderately_Thick.png";
			}elseif(strpos($tci->textures, "Extremely Thick") !== false)
			{
				$liqu_temp = "4_Extremely_Thick.png";
			}
			
			if($food_temp !== null)
				$tci->iddsi_food = $food_temp;
			if($liqu_temp !== null)
				$tci->iddsi_liqu = $liqu_temp;
			
			//process diet orders to icons
			$tci->dietOrderIcons = array();
			if(strpos($tci->diet_orders, "AHA/Cardiac") !== false)
			{
				$tci->dietOrderIcons[] = "heart.png";
			}
			if(strpos($tci->diet_orders, "RCS") !== false)
			{
				$tci->dietOrderIcons[] = "sugar.png";
			}
			if(strpos($tci->diet_orders, "Gluten Restricted") !== false)
			{
				$tci->dietOrderIcons[] = "glutenRestricted.png";
			}
			if(strpos($tci->diet_orders, "Fortified/High Calorie") !== false)
			{
				$tci->dietOrderIcons[] = "fortified.png";
			}
			if(strpos($tci->diet_orders, "Renal") !== false)
			{
				$tci->dietOrderIcons[] = "renal.png";
			}
			if(strpos($tci->diet_orders, "2 gram Na") !== false)
			{
				$tci->dietOrderIcons[] = "salt.png";
			}
			if(strpos($tci->diet_orders, "No Added Salt") !== false)
			{
				$tci->dietOrderIcons[] = "salt.png";
			}
			//smarty()->assign('dietIcons', $dietOrderIcons);
			
			//var_dump($tci);
			//die();
			for ($i=0;$i<3;$i++) {
				if(isset(input()->meal_id) && "all" != strtolower(input()->meal_id)  && (input()->meal_id - 1) != $i)
				{
					continue;
				}
				
				$tray_card_cols[$key][$i] = new stdClass();
				$tray_card_cols[$key][$i]->meal_name = $meal_names[$i];
				
				$tray_card_cols[$key][$i]->beverages = "";
				@$tray_card_cols[$key][$i]->beverages = $tci->{"beverages_".$i};
				unset($tci->{"beverages_".$i});
				
				$tray_card_cols[$key][$i]->special_reqs = "";
				@$tray_card_cols[$key][$i]->special_reqs = $tci->{"special_reqs_".$i};
				unset($tci->{"special_reqs_".$i});					
				
				foreach ($tci as $k => $d) {
					$tray_card_cols[$key][$i]->$k = $d;
				}

				if (@$tci->date_of_birth != '' && date('m-d', strtotime($tci->date_of_birth)) == $_month_day) {
					$tray_card_cols[$key][$i]->birthday = true;
				} else {
					$tray_card_cols[$key][$i]->birthday = false;
				}
			}
		}
			/*
			//$all_tray_cards = true;
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
		}*/
		
		smarty()->assign('trayCardCols', $tray_card_cols);
		// smarty()->assign('patient', $patient);
		smarty()->assign('selectedDate', $_dateStart);
		smarty()->assign('allTrayCards', true);
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

		$scheduled = $this->loadModel("Patient")->fetchDietaryPatients($location->id);
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
	

	//AJAX END POINT
	public function deleteItem() {
		return;
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
	
	//AJAX INPUT
	//get next or previous room:	
	public function switchPatientLookup() {
		//echo "<pre>";
		/*
		for($i = 0; $i < 150; $i++)
		{
			echo "$i: ";
			echo $i % 40;
			echo "\n";
		}*/
		
		if (!auth()->isLoggedIn()) {
			$this->redirect(array("module" => "Dietary"));
			die();
		}
			
		//variables are missing and die.
		if(!isset(input()->location) || !isset(input()->patient) || !isset(input()->direction))
		{
			die("ERROR: MISSING VARS");
		}
		$location = $this->getLocation();
		
		//Location good?
		if($location == null)
		{
			die("ERROR: INVALID LOCATION");
		}
		
		//Fetch patient list
		$currentPatients = $this->loadModel("Patient")->fetchDietaryPatients($location->id);
		
		
		$found = null;
		
		foreach($currentPatients as $k => $pt)
		{
			if($pt->public_id === input()->patient)
			{
				$found = $k;
			}
		}
		
		//print_r($currentPatients);
		
		if($found === null)
		{
			die("ERROR: INVALID PATIENT");
		}
		//echo "Found ID is: $found\n";
		$newPotentialPublicID = $found;
		
		$counter = 0;
		
		
		do {
			$counter++;
			//echo "Try Count: $counter\n";
			if(input()->direction === " " || input()->direction == "up") //+ URL decodes to space.
			{
				//echo "Going up!\n";
				$newPotentialPublicID = ($newPotentialPublicID + 1) % (count($currentPatients)-1);
			} else if (input()->direction === "-" || input()->direction == "down") {
				//echo "Going down!\n";
				if($newPotentialPublicID <= 0)
				{
					$newPotentialPublicID = count($currentPatients) - 1;
				} else {
					$newPotentialPublicID = ($newPotentialPublicID - 1) % (count($currentPatients)-1);
				}
			}
			//echo "Trying $newPotentialPublicID\n";
			if($counter > count($currentPatients) + 5)
			{
				//that's enough sliding.
				break;
			}
		} while(@$currentPatients[$newPotentialPublicID]->patient_admit_id == "");
		
		if($counter > count($currentPatients) + 5)
		{
			session()->setFlash("Unable to seek. Click below.", 'error');
			$this->redirect(array("module" => "Dietary", "page" => "dietary", "location" => $location->public_id));
			return;
		}
		
		echo "Took $counter trys. ";
		echo "\n";
		echo "Found: $newPotentialPublicID\n";
		echo "New Patient Name is: {$currentPatients[$newPotentialPublicID]->last_name}\n";
		echo "New Public ID is: {$currentPatients[$newPotentialPublicID]->public_id}\n";
		/*
		
		if(input()->direction === " " || input()->direction == "up") //+ URL decodes to space.
		{
			echo "Going up!\n";
			if(isset($currentPatients[$found+1]))
			{
				echo "YES!";
			}
		} else if (input()->direction === "-" || input()->direction == "down") {
			echo "Going down!\n";
			if(isset($currentPatients[$found-1]))
			{
				echo "YES!". ($found-1);
			}
		}*/

		$this->redirect(array("module" => "Dietary", "page" => "patient_info", "patient" =>  urlencode($currentPatients[$newPotentialPublicID]->public_id), "action" => "diet"));
		
		
		$data = ""; //print_r($currentPatients, true);
		
		$this->template = 'ajax';
		smarty()->assign('data', $data);
	}



}
