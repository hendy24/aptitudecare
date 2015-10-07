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

		// set arrays for checkboxes, dropdowns, etc.
		$dietOrder = array("None/Regular", "AHA/Cardiac", "No Added Salt", "Renal",
		"2 gram Na", "Fortified/High Calorie", "Other");
		$texture = array("Regular", "Mechanical Soft", "Puree", "Full Liquid",
			"Clear Liquid", "Tube Feeding", "Nectar Thick Liquids", "Honey Thick Liquids",
			"Pudding Thick Liquids", "Other");
		$portionSize = array("Small", "Medium", "Large");
		$orders = array("Isolation", "Fluid Restriction", "Clear Liquid", "Adaptive Equipment", "Other");

		smarty()->assignByRef('patient', $patient);
		smarty()->assignByRef('patientInfo', $patientInfo);
		smarty()->assignByRef('allergies', $allergies);
		smarty()->assignByRef('dislikes', $dislikes);
		smarty()->assignByRef('am_snacks', $am_snacks);
		smarty()->assignByRef('pm_snacks', $pm_snacks);
		smarty()->assignByRef('bedtime_snacks', $bedtime_snacks);
		smarty()->assign("dietOrder", $dietOrder);
		smarty()->assign("texture", $texture);
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
		if (input()->height != "") {
			$patientDiet->height = input()->height;
		}

		if (input()->weight != "") {
			$patientDiet->weight = input()->weight;
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

		if (input()->diet_info != "") {
			$patientDiet->diet_info = input()->diet_info;
		} else {
			$feedback[] = "Diet order has not been entered";
		}

		if (input()->texture != "") {
			$patientDiet->texture = input()->texture;
		} else {
			$feedback[] = "Diet texture has not been entered";
		}

		if (input()->orders != "") {
			$patientDiet->orders = input()->orders;
		} else {
			$feedback[] = "Orders have not been entered";
		}

		if (input()->portion_size != "") {
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

			// save the patient's dislikes
			foreach ($dislikesArray as $item) {
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
		smarty()->assign("title", "Print Traycard");
		$this->template = "print";

		if (input()->patient != "") {
			$patient = $this->loadModel("Patient", input()->patient);
		} else {
			session()->setFlash("Could not fine the selected patient, please try again.", 'error');
			$this->redirect();
		}

		$weekSeed = date('Y-m-d');
		$week = Calendar::getWeek($weekSeed);

		$_dateStart = date('Y-m-d', strtotime($week[0]));

		$location = $this->getLocation();
	  $menu = $this->loadModel('Menu')->fetchMenu($location->id, $_dateStart);
		$numDays = $this->loadModel('MenuItem')->fetchMenuDay($menu->menu_id);
		$startDay = round($this->dateDiff($menu->date_start, $_dateStart) % $numDays->count + 1);

		$now = date('Y-m-d', strtotime('now'));
		$menuItems = $this->loadModel('MenuItem')->fetchMenuItems($location->id, $_dateStart, $_dateStart, $startDay, $startDay, $menu->menu_id);
		$menuItems[0]->meal = "Breakfast";
		$menuItems[1]->meal = "Lunch";
		$menuItems[2]->meal = "Dinner";
		// need to get patient diet info
		$diet = $this->loadModel("PatientInfo")->fetchDietInfo($patient->id);
		// get patient schedule info
		$schedule = $this->loadModel("Schedule")->fetchByPatientId($patient->id);

		// get allergies, food dislikes, and snacks
		// fetch the allergies, dislikes and snacks
		$allergies = $this->loadModel("PatientFoodInfo")->fetchPatientAllergies($patient->id);
		$dislikes = $this->loadModel("PatientFoodInfo")->fetchPatientDislikes($patient->id);
		$am_snacks = $this->loadModel("PatientSnack")->fetchPatientSnacks($patient->id, "am");
		$pm_snacks = $this->loadModel("PatientSnack")->fetchPatientSnacks($patient->id, "pm");
		$bedtime_snacks = $this->loadModel("PatientSnack")->fetchPatientSnacks($patient->id, "bedtime");

		// calculate the patients age
		$age = getAge(date('m/d/Y', strtotime($patient->date_of_birth)));

		smarty()->assignByRef('patient', $patient);
		smarty()->assignByRef('schedule', $schedule);
		smarty()->assignByRef('diet', $diet);
		smarty()->assignByRef('allergies', $allergies);
		smarty()->assignByRef('dislikes', $dislikes);
		smarty()->assignByRef('am_snacks', $am_snacks);
		smarty()->assignByRef('pm_snacks', $pm_snacks);
		smarty()->assignByRef('bedtime_snacks', $bedtime_snacks);

		smarty()->assignByRef('schedule', $schedule);
		smarty()->assignByRef('menuItems', $menuItems);
		smarty()->assign('age', $age);
		$birthday = false;
		if(date('m-d') == substr($patient->date_of_birth,5,5)){
			$birthday = true;
		};
		smarty()->assign('birthday', $birthday);

	}

	public function traycard_options() {


		require_once VENDORS_DIR . DS . "PHPExcel/Classes/PHPExcel.php";

		$styleArray = array(
			'font' => array(
				'bold' => true,
			)
		);


		// Get the patient info from the URL
		$patient = $this->loadModel('Patient', input()->patient);

		// Export to a PDF file
		// The traycard.xlsx file can by styled to display content properly (i.e. - display a border)
		$objPHPExcel = PHPExcel_IOFactory::load(APP_PUBLIC_DIR . DS . "templates/traycard.xlsx");
		$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
		$rendererLibrary = 'mPDF5.3';
		$rendererLibraryPath = VENDORS_DIR . DS . "Libraries" . DS . $rendererLibrary;
		//$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);


		// Assign content to the template file
		// This is where dynamic content is entered to be displayed on the template file
		// PHPExcel has examples of what can be done at https://phpexcel.codeplex.com/wikipage?title=Examples&referringTitle=Home
		$objPHPExcel->getActiveSheet()->setCellValue("A1", $patient->fullName());

		if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
			die("NOTICE: Please set the $rendererName and $rendererLibraryPath values' . EOL . 'at the top of this script as appropriate for your directory structure");
		}

		// Include required files
		require_once VENDORS_DIR . DS . "PHPExcel/Classes//PHPExcel/IOFactory.php";
		// If you want to output e.g. a PDF file, simply do:
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
		// Output to PDF file
		header('Pragma: ');
		header("Content-type: application/pdf");
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		// Name the file
		//header("Content-Disposition: attachment; filename=" . $facility->name . "_" . $_dateStart . ".pdf");

		// Write file to the browser
		$objWriter->save("php://output");
		exit;


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




}
