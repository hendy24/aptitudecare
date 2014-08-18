<?php

class PatientsController extends MainController {

	public $module = 'HomeHealth';



	/*
	 * --------------------------------------------------------
	 * 	PATIENT INQUIRY RECORD
	 * --------------------------------------------------------
	 */

	public function inquiry() {
		smarty()->assign('title', 'Inquiry Record');
		
		if (!isset(input()->patient)) {
			$this->redirect();
		} else {
			$patient = $this->loadModel('Patient', input()->patient);
			$schedule = $this->loadModel('HomeHealthSchedule', $patient->id);
		}

		smarty()->assignByRef('patient', $patient);
		smarty()->assignByRef('schedule', $schedule);

		//	Get admitting facility
		$location = $this->loadModel('Location', $schedule->location_id);
		smarty()->assignByRef('location', $location);

		//	Get ethnicities
		smarty()->assign('ethnicities', getEthnicities());
		//	Get languages
		smarty()->assign('languages', getLanguages());
		//	Get marital status
		smarty()->assign('maritalStatuses', getMaritalStatuses());
		// 	Get DME Equipment
		smarty()->assign('dmEquipment', getDmEquipment());


		/*
		 *	PROCESS SUBMITTED INQUIRY FORM
		 */

		if (isset (input()->submit)) {

			/*
			 * 	Patient Info
			 */

			if (input()->first_name != '') {
				$patient->first_name = input()->first_name;
			} else {
				$error_messages[] = "The patient's first name cannot be left blank.";
			}

			if (input()->last_name != '') {
				$patient->last_name = input()->last_name;
			} else {
				$error_messages[] = "The patient's last name cannot be left blank.";
			}

			$patient->middle_name = input()->middle_name;
			$patient->address = input()->address;
			$patient->city = input()->city;
			$patient->state = input()->state;
			$patient->zip = input()->zip;

			if (input()->phone != '') {
				$patient->phone = input()->phone;
			} else {
				$error_messages[] = "Enter a contact phone number";
			}

			if (input()->date_of_birth != '') {
				$patient->date_of_birth = mysql_date(input()->date_of_birth);
			} else {
				$error_messages[] = "Enter the patient's date of birth";
			}

			if (isset(input()->sex)) {
				$patient->sex = input()->sex;
			} else {
				$error_messages[] = "Select the patient's sex";
			}
			if (input()->ethnicity != '') {
				$patient->ethnicity = input()->ethnicity;
			}
			if (input()->language != '') {
				$patient->language = input()->language;
			}
			if (input()->marital_status != '') {
				$patient->marital_status = input()->marital_status;
			}
			if (input()->religion != '') {
				$patient->religion = input()->religion;
			}
			
			$patient->emergency_contact = input()->emergency_contact;
			$patient->emergency_phone = input()->emergency_phone;



			/*
			 *	Clinical Info
			 */

			if (input()->pcp_id != '') {
				$schedule->pcp_id = input()->pcp_id;
			} else {
				$error_messages[] = "Enter the patient's Primary Care Physician";
			}

			if (input()->surgeon_id != '') {
				$schedule->surgeon_id = input()->surgeon_id;
			} 
			
			if (input()->diagnosis1_onset_date != '') {
				$schedule->diagnosis1_onset_date = input()->diagnosis1_onset_date;
			}
			

			if (input()->primary_diagnosis != '') {
				$schedule->primary_diagnosis = input()->primary_diagnosis;
			} else {
				$error_messages[] = "Enter the patient's primary diagnosis";
			}

			if (input()->diagnosis2_onset_date != '') {
				$schedule->diagnosis2_onset_date = input()->diagnosis2_onset_date;
			} 
			
			
			if (isset (input()->secondary_diagnosis)) {
				$schedule->secondary_diagnosis = input()->secondary_diagnosis;
			}
			
			if (isset (input()->diabetic)) {
				$patient->diabetic = input()->diabetic;
			}
			
			if (isset (input()->iddm)) {
				$patient->iddm = input()->iddm;
			}
			
			if (input()->allergies != '') {
				$patient->allergies = input()->allergies;
			}
			

			if (isset (input()->dme)) {
				$schedule->dme = input()->dme;
			}
			
			if (input()->special_instructions != '') {
				$schedule->special_instructions = input()->special_instructions;
			}
			



			/*
			 * Insurance Info
			 */

			if (input()->primary_insurance != '') {
				$schedule->primary_insurance = input()->primary_insurance;
			} else {
				$schedule->primary_insurance = null;
				$error_messages[] = "Enter the primary insurance info";
			}

			if (input()->primary_insurance_number != '') {
				$schedule->primary_insurance_number = input()->primary_insurance_number;
			} else {
				$schedule->primary_insurance_number = null;
				$error_messages[] = "Enter the insurance policy number";
			} 

			if (input()->primary_insurance_group != '') {
				$schedule->primary_insurance_group = input()->primary_insurance_group;
			} else {
				$schedule->primary_insurance_group = null;
			}
			if (input()->secondary_insurance != '') {
				$schedule->secondary_insurance = input()->secondary_insurance;
			} else {
				$schedule->secondary_insurance = null;
			}
			
			if (input()->secondary_insurance_number != '') {
				$schedule->secondary_insurance_number = input()->secondary_insurance_number;
			}
			
			if (input()->secondary_insurance_group != '') {
				$schedule->secondary_insurance_group = input()->secondary_insurance_group;
			}
			
			if (input()->private_pay_party != '') {
				$schedule->private_pay_party = input()->private_pay_party;
			}
			if (input()->private_pay_phone != '') {
				$schedule->private_pay_phone = input()->private_pay_phone;
			}
			if (input()->private_pay_address != '') {
				$schedule->private_pay_address = input()->private_pay_address;
			}
			if (input()->private_pay_city != '') {
				$schedule->private_pay_city = input()->private_pay_city;
			}
			if (input()->private_pay_state != '') {
				$schedule->private_pay_state = input()->private_pay_state;
			}
			if (input()->private_pay_zip != '') {
				$schedule->private_pay_state = input()->private_pay_state;
			}
			
			

			// 	BREAKPOINT 
			//	We will allow fields to be left blank, the form data that has been entered and will be saved, 
			//	the user will be redirected to the home page and the error and success messages will be 
			//	displayed there.

			if (!empty ($error_messages)) {			
				session()->setFlash($error_messages, 'error');
			} 
			
			//	Save the form data regardless of error messages
			if ($patient->save()) {	//	First save the patient info
				if ($schedule->save()) {
					session()->setFlash("The inquiry record for {$patient->first_name} {$patient->last_name} has been saved.", 'success');
					$this->redirect();
				} else {
					session()->setFlash("Could not save the inquiry record for {$patient->first_name} {$patient->last_name}.  Please try again.");
					$this->redirect(input()->path);
				}
				
			} else {
				session()->setFlash("Could not save the inquiry record for {$patient->first_name} {$patient->last_name}.  Please try again.}");
				$this->redirect(input()->path);
			}


		}

	}




	/*
	 * --------------------------------------------------------
	 * 	ASSIGN CLINICIANS TO THE PATIENT
	 * --------------------------------------------------------
	 */

	public function assign_clinicians() {
		//	Get patient info
		$patient = null;
		if (input()->patient == '') {
			$this->redirect();
		} else {
			$patient = $this->loadModel('Patient');
			$patient = $patient->fetchPatientById(input()->patient);
		}

		smarty()->assignByRef('patient', $patient);
		smarty()->assign('title', 'Assign Clinicians');

	}
}