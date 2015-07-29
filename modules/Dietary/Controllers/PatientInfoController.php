<?php

class PatientInfoController extends MainPageController {

	public $module = "Dietary";
	protected $navigation = 'dietary';
	protected $searchBar = 'dietary';


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
				$patientFoodInfo = $this->loadModel("PatientFoodInfo");
				$patientFoodInfo->patient_id = $patient->id;
				$dietaryAllergy = $this->loadModel("Allergy")->fetchByName($item);
				if ($dietaryAllergy) {
					$patientFoodInfo->food_id = $dietaryAllergy->id;
				} else {
					$dietaryAllergy->name = $item;
					$dietaryAllergy->save();
					$patientFoodInfo->food_id = $dietaryAllergy->id;
				}
				$patientFoodInfo->allergy = true;
				$allergiesArray[] = $patientFoodInfo;
			}
		}


		// set dislikes array
		$dislikesArray = array();
		if (!empty (input()->dislikes)) {
			foreach (input()->dislikes as $item) {
				$patientFoodInfo = $this->loadModel("PatientFoodInfo");
				$patientFoodInfo->patient_id = $patient->id;
				$dietaryDislike = $this->loadModel("Dislike")->fetchByName($item);
				if ($dietaryDislike) {
					$patientFoodInfo->food_id = $dietaryDislike->id;
				} else {
					$dietaryDislike->name = $item;
					$dietaryDislike->save();
					$patientFoodInfo->food_id = $dietaryDislike->id;
				}
				$dislikesArray[] = $patientFoodInfo;
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
		if ($patientDiet->save()) {
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
			$this->redirect(array("module" => "Dietary", "page" => "Dietary", "action" => "index", "location" => $location->public_id));
		} else {
			session()->setFlash($feedback, "error");
			$this->redirect(input()->currentUrl);
		}

	}

	public function traycard() {
		smarty()->assign("title", "Print Traycard");
		$this->template = "blank";

		if (input()->patient != "") {
			$patient = $this->loadModel("Patient", input()->patient);
		} else {
			session()->setFlash("Could not fine the selected patient, please try again.", 'error');
			$this->redirect();
		}

		// need to get the schedule info from the admission db
		$schedule = $this->loadModel("AdmissionDashboard")->fetchSchedule($patient->public_id);
		// need to get patient diet info
		$diet = $this->loadModel("PatientInfo")->fetchDietInfo($patient->id);

		// calculate the patients age
		$age = getAge(date('m/d/Y', strtotime($patient->date_of_birth)));

		smarty()->assignByRef('patient', $patient);
		smarty()->assignByRef('diet', $diet);
		smarty()->assignByRef('schedule', $schedule);
		smarty()->assign('age', $age);
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
