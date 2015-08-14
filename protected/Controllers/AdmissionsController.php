<?php

class AdmissionsController extends MainPageController {
	
	public $module = 'HomeHealth';


	public function pending_admits() {
		$this->helper = 'PatientMenu';
		smarty()->assign('title', 'Pending Admissions');
		
		if (isset(input()->location)) {
			// If the location is set in the url, get the location by the public_id
			$location = $this->loadModel('Location', input()->location);

			if (isset (input()->area)) {
				$area = $this->loadModel('Location', input()->area);
			} else {
				$area = $location->fetchLinkedFacility($location->id);
			}
		} else {
			// Get the users default location from the session
			$location = $this->loadModel('Location', auth()->getDefaultLocation());
			//  If this is not a home health location need to get the associated home health agency
			if ($location->location_type != 2) {
				$area = $location;
				$location = $location->fetchHomeHealthLocation();
			} else {
				$area = $location->fetchLinkedFacility($location->id);
			}
			
		}

		smarty()->assign('loc', $location);
		smarty()->assignByRef('selectedArea', $area);


		$admits = $this->loadModel('Patient')->fetchPendingAdmits($area->id);
		smarty()->assignByRef('admits', $admits);
	}


	public function new_admit() {
		smarty()->assign('title', 'New Admission');
	}


	public function submitNewAdmit() {
		$patient = $this->loadModel('Patient');
		$schedule = $this->loadModel('HomeHealthSchedule');

		// Admission date
		if (input()->referral_date != '') {
			$schedule->referral_date = date('Y-m-d 11:00:00', strtotime(input()->referral_date));
			$schedule->start_of_care = $schedule->referral_date;
		} else {
			$error_messages[] = "Enter the date the patient will be admitted";
		}


		//	Admit from location
		if (input()->admit_from != '') {
			$schedule->admit_from_id = input()->admit_from;
		} else {
			$error_messages[] = "Enter the location from which the patient will be admitted";
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
		if (input()->area != '') {
			$l = $this->loadModel('Location');
			$location = $l->fetchLocation(input()->area);
			$schedule->location_id = $location->id;
		}

		// 	Set patient status as pending for new admits
		$schedule->status = "Under Consideration";

		$schedule->referred_by_location_id = input()->referred_by_location_id;

		if (input()->referred_by_individual_id != "") {
			$schedule->referred_by_individual_id = input()->referred_by_individual_id;
			$schedule->referred_by_type = "healthcare_facility";
		} elseif (input()->physician_id != "") {
			$schedule->referred_by_individual_id = input()->physician_id;
			$schedule->referred_by_type = "physician";
		}
		
			

		// 	Break point.  If there are error messages set them in the session and redirect back
		//	to the new admit page.
		if (!empty($error_messages)) {
			session()->setFlash($error_messages, 'error');
			json_return(array('url' => SITE_URL));
		} else {
			if ($patient->save()) {
				$schedule->patient_id = $patient->id;
				if ($schedule->save()) {
					session()->setFlash("The patient info and schedule for {$patient->first_name} {$patient->last_name} have been saved", "success");
					json_return(array('url' => SITE_URL . "/?module=HomeHealth&location=" . input()->location . "&area=" . $location->public_id));
				}
				
			} else {
				session()->setFlash("Could not save the patient info and schedule", "error");
				json_return(array('url' => SITE_URL));
			}
			
			
		}
	}


	public function submitPrevPatient() {
		//	Need to get the previous patient info
		if (input()->patient_id != '') {
			$patient = $this->loadModel('Patient')->fetchById(input()->patient_id);
			$prevSchedule = $this->loadModel('HomeHealthSchedule')->fetchByPatientId($patient->id);
			$schedule = $this->loadModel('HomeHealthSchedule');
			$location = $this->loadModel('Location')->fetchById(input()->area);
		}


		//	If the patient's status is approved they are a current patient
		if ($prevSchedule->status == "Approved") {
			$error_messages[] = "{$patient->first_name} {$patient->last_name} was already admitted on " . display_date($prevSchedule->referral_date);
		} 
		//	If patient is a pending admit in the future then throw an error
		elseif ($prevSchedule->status == 'Pending' && strtotime($prevSchedule->referral_date) >= strtotime("now")) {
			$error_messages[] = "{$patient->first_name} {$patient->last_name} is already pending admission on " . display_date($prevSchedule->referral_date);
		} else {
			//	Set the new schedule info with the previous patient id
			$schedule->patient_id = $patient->id;
			$schedule->referral_date = mysql_datetime_admit(input()->referral_date);
			$schedule->start_of_care = mysql_datetime_admit(input()->referral_date);
			$schedule->location_id = $location->id;
			$schedule->admit_from_id = input()->admit_from;

			if (input()->referred_by_location_id != "") {
				$schedule->referred_by_location_id = input()->referred_by_location_id;
				$schedule->referred_by_type = "healthcare_facility";
			} else {
				$schedule->referred_by_type = "physician";
			}

			$schedule->referred_by_individual_id = input()->referred_by_individual_id;
			
			$schedule->referred_by_individual_id = input()->referred_by_individual_id;
			

			//	If the phone number is different than before update it
			if ($patient->phone !== input()->phone) {
				$patient->phone = input()->phone;
			}

			//	If the zip code is different than before then update it
			if ($patient->zip !== input()->zip) {
				$patient->zip = input()->zip;
			}
		}


		// The model is not fetching an object will all the row names empty,
		// rather than spend additional time troubleshooting this issue
		// I am manually creating the table names which are not automatically 
		// saving -- kwh 2015-06-16
		$schedule->public_id = null;
		$schedule->datetime_created = null;
		$schedule->datetime_modified = null;
		$schedule->user_created = null;
		$schedule->user_modified = null;


		$schedule->status = "Under Consideration";

		//	Breakpoint
		if (!empty ($error_messages)) {
			session()->setFlash($error_messages, 'error');
			json_return (array('url' => SITE_URL . "/?module=HomeHealth"));
		}

		//	If no errors then save the patient and schedule info

		if ($schedule->save() && $patient->save()) {
			session()->setFlash($patient->first_name . " " . $patient->last_name . " has been saved.");
			json_return (array('url' => SITE_URL . "/?module=HomeHealth"));
		} else {
			session()->setFlash("Could not save the patient info", "error");
			json_return(array('url' => SITE_URL . "/?module=HomeHealth"));
		}
		

	}



	public function searchPrevPatients() {

		if (isset (input()->first_name)) {
			$first_name = input()->first_name;
		}
		if (isset (input()->last_name)) {
			$last_name = input()->last_name;
		}
		
		if (isset (input()->middle_name)) {
			$middle_name = input()->middle_name;
		}
		$patient = $this->loadModel('Patient');
		$result = $patient->fetchPreviousPatients($last_name, $first_name, $middle_name);
		json_return ($result);
	}


	public function moveAdmitDate() {
		$schedule = $this->loadModel('HomeHealthSchedule', input()->public_id);
		$schedule->start_of_care = mysql_datetime(input()->date);

		if ($schedule->save()) {
			json_return(true);
		} else {
			json_return(false);
		}
	}


}