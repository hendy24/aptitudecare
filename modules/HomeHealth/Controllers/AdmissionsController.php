<?php

class AdmissionsController extends MainController {
	
	public $module = 'HomeHealth';



	public function new_admit() {
		smarty()->assign('title', 'New Admission');

		$lm = $this->loadModel('Location');
		$locations = $lm->fetchLocations();
		smarty()->assignByRef('locations', $locations);



		/*
		 *	Process Form Submission
		 */

		if (isset (input()->submit)) {

			$patient = new Patient();
			$schedule = new HomeHealthSchedule();


			// Admission date
			if (input()->admit_date != '') {
				$schedule->datetime_admit = date('Y-m-d 11:00:00', strtotime(input()->admit_date));
			} else {
				$error_messages[] = "Enter the date the patient will be admitted";
			}


			//	Admit from location
			if (input()->admit_from != '') {
				$schedule->admit_from_id = input()->admit_from;
			} else {
				$error_messages[] = "Enter the location from which the patient will be admitted";
			}

			if (input()->referred_by_id != '') {
				$schedule->referred_by_id = input()->referred_by_id;
				$schedule->referred_by_type = input()->referred_by_type;
			}


			//	Patient First name
			if (input()->first_name != '') {
				$patient->first_name = input()->first_name;
			} else {
				$error_messages[] = "Enter the patient's first name";
			}

			//	Patient Last Name
			if (input()->last_name != '') {
				$patient->last_name = input()->last_name;
			} else {
				$error_messages[] = "Enter the patient's last name";
			}

			
			//	Patient zip code
			$patient->zip = input()->zip;
			//	Patient middle name
			$patient->middle_name = input()->middle_name;

			//	Patient phone number
			if (input()->phone != '') {
				$patient->phone = input()->phone;
			} else {
				$error_messages[] = "Enter the patient's phone number";
			}

			//	Admit to location
			if (input()->location != '') {
				$l = $this->loadModel('Location');
				$location = $l->fetchLocation(input()->location);
				$schedule->location_id = $location->id;
			}


			// 	Break point.  If there are error messages set them in the session and redirect back
			//	to the new admit page.
			if (!empty($error_messages)) {
				session()->setFlash($error_messages, 'error');
				$this->redirect(input()->path);
			} else {
				$patient_id = $patient->save();
				if ($patient->save()) {
					$schedule->patient_id = $patient->id;
					if ($schedule->save()) {
						session()->setFlash("The patient info and schedule for {$patient->fullName()} have been saved", "success");
						$this->redirect();
					}
					
				} else {
					session()->setFlash("Could not save the patient info and schedule", "error");
					$this->redirect(input()->path);
				}
				
				
			}

		}

	}


	public function pending_admits() {

	}
}