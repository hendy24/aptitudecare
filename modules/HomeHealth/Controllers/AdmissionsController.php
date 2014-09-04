<?php

class AdmissionsController extends MainController {
	
	public $module = 'HomeHealth';


	public function pending_admits() {
		$this->helper = 'PatientMenu';
		smarty()->assign('title', 'Pending Admissions');
		if (isset (input()->area)) {
			$area = $this->loadModel('Location', input()->area);
		} else {
			//	Get the users default location
			$location = $this->loadModel('Location', auth()->getDefaultLocation());
			$area = $location->fetchLinkedFacility($location->id);
		}

		$admits = $this->loadModel('Patient')->fetchPendingAdmits($area->id);
		smarty()->assignByRef('admits', $admits);
	}


	public function new_admit() {
		smarty()->assign('title', 'New Admission');

		$lm = $this->loadModel('Location');
		$locations = $lm->fetchLocations();
		smarty()->assignByRef('locations', $locations);

	}


	public function submitNewAdmit() {
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
			$schedule->referred_by_type = underscoreString(input()->referred_by_type);
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
			json_return(array('url' => SITE_URL));
		} else {
			$patient_id = $patient->save();
			if ($patient_id != '') {
				$schedule->patient_id = $patient_id;
				if ($schedule->save()) {
					session()->setFlash("The patient info and schedule for {$patient->first_name} {$patient->last_name} have been saved", "success");
					json_return(array('url' => SITE_URL));
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
			$location = $this->loadModel('Location')->fetchById(input()->location);
		}

		
		//	If the patient's status is approved they are a current patient
		if ($prevSchedule->status == "Approved") {
			$error_messages[] = "{$patient->first_name} {$patient->last_name} was already admitted on " . display_date($prevSchedule->datetime_admit);
		} 
		//	If patient is a pending admit in the future then throw an error
		elseif ($prevSchedule->status == 'Pending' && strtotime($prevSchedule->datetime_admit) >= strtotime("now")) {
			$error_messages[] = "{$patient->first_name} {$patient->last_name} is already pending admission on " . display_date($prevSchedule->datetime_admit);
		} else {
			//	Set the new schedule info with the previous patient id
			$schedule->patient_id = $patient->id;
			$schedule->datetime_admit = mysql_datetime_admit(input()->admit_date);
			$schedule->location_id = $location->id;
			$schedule->admit_from_id = input()->admit_from;
			$schedule->referred_by_id = input()->referred_by_id;
			$schedule->referred_by_type = input()->referred_by_type;

			//	If the phone number is different than before update it
			if ($patient->phone !== input()->phone) {
				$patient->phone = input()->phone;
			}

			//	If the zip code is different than before then update it
			if ($patient->zip !== input()->zip) {
				$patient->zip = input()->zip;
			}
		}


		//	Breakpoint
		if (!empty ($error_messages)) {
			session()->setFlash($error_messages, 'error');
			json_return (array('url' => SITE_URL));
		}


		//	If no errors then save the patient and schedule info

		if ($schedule->save() && $patient->save()) {
			session()->setFlash($patient->first_name . " " . $patient->last_name . " has been saved.");
			json_return (array('url' => SITE_URL));
		} else {
			session()->setFlash("Could not save the patient info", "error");
			json_return(array('url' => SITE_URL));
		}
		

	}



	public function searchPrevPatients() {

		$class = $this->loadModel('Patient');
		$table = $class->fetchTable();

		$sql = "SELECT `{$table}`.`public_id`, `{$table}`.`last_name`, `{$table}`.`first_name`, `{$table}`.`middle_name`, `{$table}`.`ssn`, `location`.`name` AS location_name, `home_health_schedule`.`datetime_discharge`, `home_health_schedule`.`status` FROM `{$table}` INNER JOIN `home_health_schedule` ON `patient`.`id`=`home_health_schedule`.`patient_id` INNER JOIN `location` ON `location`.`id`=`home_health_schedule`.`location_id` WHERE `{$table}`.`first_name` LIKE :first_name AND `{$table}`.`last_name` LIKE :last_name";
		if (input()->middle_name != '') {
			$sql .= " AND `{$table}`.`middle_name` LIKE :middle_name";
			$params[":middle_name"] = "%" . input()->middle_name . "%";
		}
		$params = array(
			":first_name" => "%" . input()->first_name . "%",
			":last_name" => "%" . input()->last_name . "%",
		);

		$result = db()->fetchRows($sql, $params, $class);

		json_return ($result);

	}


	public function moveAdmitDate() {
		echo input()->public_id;
		if (input()->public_id != '') {
			$schedule = $this->loadModel('HomeHealthSchedule', input()->public_id);
		}

		pr ($schedule);
		die();
	}


}