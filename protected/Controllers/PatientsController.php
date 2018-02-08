<?php

class PatientsController extends MainPageController {


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

		// 	Get the DME equipment assigned to the user
		$dme = $this->loadModel('HHPatientDme')->fetchPatientEquipment($schedule->id);
		smarty()->assignByRef('patientDme', $dme);

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
		smarty()->assign('dmEquipment', $this->loadModel('DmEquipment')->fetchEquipment());

		//	Set admitting facility
		if ($schedule->admit_from_id != '') {
			$admit_from = $this->loadModel('HealthcareFacility', $schedule->admit_from_id);
		} else {
			$admit_from = array();
		}
		smarty()->assignByRef('admit', $admit_from);


		//	Set primary care physician
		if ($schedule->pcp_id != '') {
			$pcp = $this->loadModel('Physician', $schedule->pcp_id);
		} else {
			$pcp = array();
		}
		smarty()->assign("pcp", $pcp);

		if ($schedule->following_physician_id != '') {
			$followingPhysician  = $this->loadModel('Physician', $schedule->following_physician_id);
		} else {
			$followingPhysician = array();
		}

		smarty()->assign("followingPhysician", $followingPhysician);


		//	Set surgeon/specialist
		if ($schedule->specialist_id != '') {
			$specialist = $this->loadModel('Physician', $schedule->specialist_id);
		} else {
			$specialist = array();
		}
		smarty()->assign("specialist", $specialist);





		/*
		 *	PROCESS SUBMITTED INQUIRY FORM
		 *
		 */

		if (input()->is('post')) {

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

			if (input()->specialist_id != '') {
				$schedule->specialist_id = input()->specialist_id;
			} 

			if (input()->following_physician_id != '') {
				$schedule->following_physician_id = input()->following_physician_id;
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
				$dme = $this->loadModel('HHPatientDme');
				$dme->deleteCurrentDme($schedule->id);
				foreach (input()->dme as $k => $e) {
					$dme->home_health_schedule_id = $schedule->id;
					$dme->dme_id = $e;
					$dme->save();
				}
			} else {
				$dme = $this->loadModel('HHPatientDme');
				$dme->deleteCurrentDme($schedule->id);
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
			
			if (input()->f2f_received == true) {
				$schedule->f2f_received = true;
			} else {
				$schedule->f2f_received = false;
			}

			if (input()->clinicians_assigned == true) {
				$schedule->clinicians_assigned = true;
			} else {
				$schedule->clinicians_assigned = false;
			}

			if (input()->insurance_verified == true) {
				$schedule->insurance_verified = true;
			} else {
				$schedule->insurance_verified = false;
			}

			if ($schedule->status != "Approved" && ($schedule->f2f_received && $schedule->clinicians_assigned && $schedule->insurance_verified)) {
				$schedule->status = "Pending";
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
					$this->redirect(array("module" => "HomeHealth"));
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


	// Functionality was removed per request

	// public function assign_clinicians() {
	// 	//	Get patient info
	// 	$patient = null;
	// 	if (input()->patient == '') {
	// 		$this->redirect();
	// 	} else {
	// 		$patient = $this->loadModel('Patient')->fetchById(input()->patient);
	// 	}


	// 	$schedule = $this->loadModel('HomeHealthSchedule')->fetchByPatientId($patient->id);

	// 	//	Fetch location info
	// 	$homeHealth = $this->loadModel('Location', $schedule->location_id)->fetchHomeHealthLocation();

	// 	//	Fetch clinician types
	// 	$clinicianTypes = $this->loadModel('Clinician')->fetchAll();
	// 	smarty()->assign('clinicianTypes', $clinicianTypes);

	// 	$clinician = $this->loadModel('User');
	// 	foreach ($clinicianTypes as $type) {
	// 		$clinicianByType[$type->name] = $clinician->fetchByType($type->name, $homeHealth->id);
	// 	}
	// 	smarty()->assignByRef('clinicianByType', $clinicianByType);
	// 	smarty()->assignByRef('patient', $patient);
	// 	smarty()->assignByRef('schedule', $schedule);
	// 	smarty()->assign('title', 'Assign Clinicians');


	// 	if (input()->is('post')) {
	// 		foreach (input()->clinician_id as $k => $id) {
	// 			foreach ($clinicianTypes as $type) {
	// 				if ($type->name == $k) {
	// 					$schedule->{$type->name . "_id"} = $id;
	// 				}
	// 			}
	// 		}

			
	// 		if ($schedule->sn_id != '') {
	// 			$schedule->clinicians_assigned = true;
	// 		} else {
	// 			$schedule->clinicians_assigned = false;
	// 		}

	// 		if ($schedule->save()) {
	// 			session()->setFlash('Successfully assigned clinician(s) for ' . $patient->fullName(), 'success');
	// 			$this->redirect();
	// 		} else {
	// 			session()->setFlash("Could not save clinician(s)", 'error');
	// 			$this->redirect(input()->currentUrl);
	// 		}
	// 	}

	// }




	/* 
	 * Enter patient Visits 
	 *	
	 */

	public function visits() {
		if (input()->patient != "") {
			$patient = $this->loadModel('Patient', input()->patient);
		} else {
			session()->setFlash("Could not find the patient. Please try again.", 'error');
			$this->redirect();
		}

		// Fetch any past patient visits
		$patientVisits = $this->loadModel('HomeHealthPatientVisit')->fetchPatientVisits($patient->id);

		smarty()->assignByRef('patient', $patient);
		smarty()->assignByRef('patientVisits', $patientVisits);
	}


	public function submit_patient_visits() {
		$visits = array();
		if (input()->patient != "") {
			$patient = $this->loadModel('Patient', input()->patient);
		} else {
			session()->setFlash("Could not find the patient. Please try again.", 'error');
			$this->redirect();
		}


		// check visit values for the physician
		if (input()->physician_visit_date != "") {
			// if the date is not empty but the time is, throw an error
			if (input()->physician_visit_time == "") {
				session()->setFlash("Enter the time of the physician visit", 'error');
				$this->redirect(input()->current_url);
			} else {
				$hhVisit = $this->loadModel('HomeHealthPatientVisit');
				$hhVisit->patient_id = $patient->id;
				$hhVisit->visit_type = "physician";
				$hhVisit->datetime_visit = date("Y-m-d H:i:s", strtotime(input()->physician_visit_date . " " . input()->physician_visit_time));
				$visit[] = $hhVisit;
			}
		}

		// check visit values for nurse practitioner
		if (input()->nurse_practitioner_visit_date != "") {
			if (input()->nurse_practitioner_visit_time == "") {
				session()->setFlash("Enter the time of the nurse practitioner visit", 'error');
				$this->redirect(input()->current_url);
			} else {
				$hhVisit = $this->loadModel('HomeHealthPatientVisit');
				$hhVisit->patient_id = $patient->id;
				$hhVisit->visit_type = "nurse_practitioner";
				$hhVisit->datetime_visit = date("Y-m-d H:i:s", strtotime(input()->nurse_practitioner_visit_date . " " . input()->nurse_practitioner_visit_time));
				$visit[] = $hhVisit;

			}
		}


		if (input()->nurse_visit_date != "") {
			if (input()->nurse_visit_time == "") {
				session()->setFlash("Enter the time of the nurse visit", 'error');
				$this->redirect(input()->current_url);
			} else {
				$hhVisit = $this->loadModel('HomeHealthPatientVisit');
				$hhVisit->patient_id = $patient->id;
				$hhVisit->visit_type = "nurse";
				$hhVisit->datetime_visit = date("Y-m-d H:i:s", strtotime(input()->nurse_visit_date . " " . input()->nurse_visit_time));
				$visit[] = $hhVisit;

			}
		}


		if (input()->therapist_visit_date != "") {
			if (input()->therapist_visit_time == "") {
				session()->setFlash("Enter the time of the therapis visit", 'error');
				$this->redirect(input()->current_url);
			} else {
				$hhVisit = $this->loadModel('HomeHealthPatientVisit');
				$hhVisit->patient_id = $patient->id;
				$hhVisit->visit_type = "therapist";
				$hhVisit->datetime_visit = date("Y-m-d H:i:s", strtotime(input()->therapist_visit_date . " " . input()->therapist_visit_time));
				$visit[] = $hhVisit;

			}
		}

		foreach ($visit as $v) {
			if ($v->save()) {
				$success = true;
			} else {
				$success = false;
			}
		}

		if ($success) {
			session()->setFlash("Saved the visit times for {$patient->fullName()}", 'success');
			$this->redirect(array("module" => "HomeHealth"));
		} else {
			session()->setFlash("Could not save the visit times for {$patient->fullName()}", 'error');
			$this->redirect(array("module" => "HomeHealth"));
		}


	}



	public function patient_files() {
		smarty()->assign("title", "Patient Files");

		// fetch patient info
		if (input()->patient != "") {
			$patient = $this->loadModel("Patient", input()->patient);
		} else {
			session()->setFlash("Could not find the patient you were looking for.", 'error');
			$this->redirect();
		}

		$patientFiles = $this->loadModel("PatientNote")->fetchNotes($patient->id);

		smarty()->assign("patient", $patient);
		smarty()->assign("patientFiles", $patientFiles);
		
	}


	public function fileUpload() {
		// fetch patient info
		if (input()->patient == "") {
			$error_messages = "Could not find the selected patient.";
		} else {
			$patient = $this->loadModel("Patient", input()->patient);
			$patientNote = $this->loadModel("PatientNote");
		}
		if ( !empty ($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];
			$fileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$targetPath = dirname(dirname(dirname(dirname (__FILE__)))) . DS . "public/files/patient_files/";
			$fileName = getRandomString() . "." . $fileType;
			$targetFile = $targetPath . $fileName;
			
		

			if (move_uploaded_file($tempFile, $targetFile)) {
				// success
				// need to create a file name and save to the patient's record
				$patientNote->patient_id = $patient->id;
				$patientNote->file = $fileName;
				$patientNote->name = $_FILES['file']['name'];
				if ($patientNote->save()) {
					json_return (array("filetype" => $fileType, "name" => $patientNote->name));
				} else {
					json_return(false);
				}
			} else {
				// failure
				json_return(false);
			}
			
		} else {
			// error message
			json_return(false);
		}
	}



	public function deleteFile() {
		if (input()->public_id != '' && input()->filename != '') {
			//	Fetch the file by name and user id
			$patient = $this->loadModel("Patient", input()->public_id);
			$note = $this->loadModel("PatientNote")->fetchNote($patient->id, input()->filename);
			if ($note->deleteNote()) {
				json_return(true);
			}
			json_return(false);
		}
		json_return(false);

	}


	public function previewNotesFile() {
		smarty()->assign("title", "Patient Note");

		// get the page offset, or start at 0
		$offset = (input()->offset != "" && is_numeric(input()->offset) && input()->offset > 0) ? input()->offset : 0;
		smarty()->assign("offset", $offset);

		// number of pages in a chunck
		$numPages = 5;
		smarty()->assign("numPages", $numPages);

		// the page we came from
		smarty()->assign("b", input()->b);

		if (input()->width == '') {
			$width = 930;
		} else {
			$width = input()->width;
		}
		smarty()->assign("width", $width);

		// check for the patient id
		if (input()->patient != "") {
			$patient = $this->loadModel("Patient", input()->patient);
			smarty()->assignByRef("patient", $patient);
		} else {
			 $error_messages[] = "Could not find the patient you are looking for. Please try again.";
		}

		// check for the file name 
		if (input()->file != "") {
			$filename = input()->file;
		} else {
			$error_messages[] = "Could not find the file name. Please try again.";
		}

		if (!empty ($error_messages)) {
			session()->setFlash($error_messages, 'error');
			$this->redirect(input()->path);
		}

		$note = $this->loadModel("PatientNote")->fetchNote($patient->id, $filename);
		smarty()->assignByRef("note", $note);

		// get the path to the file
		$pdfFile = $this->getFilePath($note->file);

		if ($pdfFile) {
			try {
				$image = new Imagick($pdfFile);
				$totalPages = $image->getNumberImages();
				smarty()->assign("totalPages", $totalPages);

				// figure out how many pages actually exist in this chunk
				if ($offset + $numPages + 1 > $totalPages) {
					$thisChunkNumPages = $totalPages - $offset;
				} else {
					$thisChunkNumPages = $numPages;
				}
				smarty()->assign("thisChunkNumPages", $thisChunkNumPages);
			} catch (ImagickException $e) {
				session()->setFlash("Could not file the selected file.", 'error');
				$this->redirect();
			}
		} else {
			session()->setFlash("Could not file the selected file.", 'error');
			$this->redirect();
		}
		
	}


	public function previewNotesFileImage() {
		// get the page offset we'll start at, or default to 0
		$offset = (input()->offset != '' && is_numeric(input()->offset) && input()->offset > 0) ? input()->offset : 0;
		
		// get the number of pages from the request, or default to 5
		$numPages = (input()->numPages != '' && is_numeric(input()->numPages) && input()->numPages > 0) ? input()->numPages : 5;

		// we should be told when invoked how many total pages there are
		$totalPages = input()->totalPages;

				// check for the patient id
		if (input()->patient != "") {
			$patient = $this->loadModel("Patient", input()->patient);
			smarty()->assignByRef("patient", $patient);
		} else {
			 $error_messages[] = "Could not find the patient you are looking for. Please try again.";
		}

		// check for the file name 
		if (input()->file != "") {
			$filename = input()->file;
			smarty()->assign("filename", $filename);
		} else {
			$error_messages[] = "Could not find the file name. Please try again.";
		}

		if (!empty ($error_messages)) {
			session()->setFlash($error_messages, 'error');
			$this->redirect(input()->path);
		}

		// fetch the patient note
		$note = $this->loadModel("PatientNote")->fetchNote($patient->id, $filename);

		// get the path to the file
		$pdfFile = $this->getFilePath($note->file);

		if ($pdfFile) {
			$width = input()->width;
			try {
				$image = new Imagick;
				// figure out how many pages actually exist in this chunk
				if ($offset + $numPages + 1 > $totalPages) {
					$numPages = $totalPages - $offset;
				}
				// cycle from the offset to the number of requested pages, adding a page to
				// the stack as we go
				for ($i=$offset; $i < ($offset + $numPages); $i++) {
					$image_sub = new Imagick();
					$image_sub->setResolution(200, 200);
					$image_sub->readImage($pdfFile. "[{$i}]");
					$image_sub->setImageCompression(Imagick::COMPRESSION_LOSSLESSJPEG); 
					$image_sub->setImageCompressionQuality(100);
					$image_sub->thumbnailImage($width, 0);
					$image->addImage($image_sub);
				}
				$image->resetIterator();
				// stack them horizontally
				$image_multi = $image->appendImages(false);
				// as a PNG
				$image_multi->setImageFormat('png');
	
				// add in the  blob back to the item and output as a PNG
				header("Content-type: image/png");
				echo $image_multi->getImageBlob();
			} catch (ImagickException $e) {
				
			}
		} else {
			
		}

		exit;



	}


	public function search_patients() {
		smarty()->assign("title", "Search Patients by Name");
		$this->helper = 'PatientMenu';
		if (input()->term == "") {
			$this->redirect();
		} else {
			$patient_name = input()->term;
			$obj = $this->loadModel("Patient");
			$locations = $this->loadModel("Location")->fetchAllLocations();
		}

		$results = $obj->fetchPatientSearch($patient_name, $locations);

		if (empty ($results)) {
			smarty()->assign("results", false);
		} else {
			smarty()->assignByRef("results", $results);
		}
	}


	public function face_to_face() {

		if (input()->patient == '') {
			session()->setFlash("Could not find the Face to Face form for the selected patient.", "error");
			$this->redirect();
		} 

		smarty()->assign('title', 'Face-to-Face Encounter');
		$patient = $this->loadModel('Patient', input()->patient);
		$schedule = $this->loadModel('HomeHealthSchedule')->fetchByPatientId($patient->id);
		$f2f_form = $this->loadModel('FaceToFace')->fetchBySchedule($schedule->id);
		
		if (empty ($f2f_form)) {
			$f2f_form = $this->loadModel('FaceToFace');
		}
			
		smarty()->assignByRef('patient', $patient);
		smarty()->assignByRef('schedule', $schedule);
		smarty()->assignByRef('f2f_form', $f2f_form);


		if (input()->is('post')) {
			$f2f = $this->loadModel('FaceToFace');

			$f2f->home_health_schedule = $schedule->id;

			if (input()->f2f_date != '') {
				$f2f->f2f_date = mysql_date(input()->f2f_date);
			} else {
				$error_messages[] = "Enter the date of the face-to-face encounter";
			}
			if (input()->medical_condition != '') {
				$f2f->medical_condition = input()->medical_condition;
			} else {
				$error_messages[] = "Enter the patient's medical condition";
			}
			if (input()->home_health_services != '') {
				$f2f->home_health_services = input()->home_health_services;
			} else {
				$error_messages[] = "Enter the required home health services";
			}
			if (input()->home_health_reasons != '') {
				$f2f->home_health_reasons = input()->home_health_reasons;
			} else {
				$error_messages[] = "Enter the supporting reasons for home health services";
			}
			if (input()->homebound_reason != '') {
				$f2f->homebound_reason = input()->homebound_reason;
			} else {
				$error_messages[] = "Enter the supporting reasons the patient is homebound";
			}
			if (input()->physician_id != '') {
				$f2f->physician_id = input()->physician_id;
			} else {
				$error_messages[] = "Enter your name in the \"completed by\" box";
			}


			if (!empty ($error_messages)) {
				session()->setFlash($error_messages, 'error');
				$this->redirect(input()->path);
			}

			$f2f->datetime_completed = date('Y-m-d H:i:s', strtotime('now'));
			$f2f->completed_by_user = auth()->getRecord()->id;
			
			if ($f2f->save()) {
				$schedule->f2f_received = true;
				$schedule->save();
				session()->setFlash("The face-to-face encounter form has been saved for {$patient->fullName()}", "success");
				$this->redirect();
			}
			
		}


	}


	public function approve_inquiry() {
		$patient = $this->loadModel('Patient', input()->patient);
		$schedule = $this->loadModel('HomeHealthSchedule')->fetchByPatientId($patient->id);

		$schedule->status = 'Approved';

		if ($schedule->save()) {
			session()->setFlash("{$patient->fullName()} has been approved", "success");
			$this->redirect(array('module' => 'HomeHealth'));
		} else {
			session()->setFlash("Could not complete the request.  Please try again.", "error");
			$this->redirect(array('module' => 'HomeHealth'));
		}
	}


	public function cancel_inquiry() {
		$patient = $this->loadModel('Patient', input()->patient);
		$schedule = $this->loadModel('HomeHealthSchedule')->fetchByPatientId($patient->id);

		$schedule->status = "Cancelled";

		if ($schedule->save()) {
			session()->setFlash("The patient inquiry for {$patient->fullName()} has been cancelled.", 'success');
			$this->redirect(array('module' => 'HomeHealth'));
		} else {
			session()->setFlash("Could not cancel the inquiry record for {$patient->fullName()}. Please try again.", 'error');
			$this->redirect(array('module' => 'HomeHealth'));
		}
	}

	private function getFilePath($name) {
		if (file_exists(SITE_DIR . DS . ".dev")) {
			$dir = dirname(ROOT) . DS . "aptitudecare" . DS . "sites" . DS . "ahc" . DS . "protected" . DS . "assets";
		} else {
			$dir = dirname(ROOT) . DS . "sites" . DS . "ahc" . "live" . DS . "protected" . DS . "assets";
		}

		// search home health directory for the file first
		if (file_exists(SITE_DIR . DS . "public" . DS . "files" . DS . "patient_files" . DS . $name)) {
			return SITE_DIR . DS . "public" . DS . "files" . DS . "patient_files" . DS . $name;
		} else {
			// search the admission app directory for the file
			$directory = new RecursiveDirectoryiterator($dir);
			$iterator = new RecursiveIteratorIterator($directory);
			foreach ($iterator as $file) {
				if ($file->getFilename() == $name) {
					return $file->getPath() . DS . $name;
				}
			}
		}

		return false;
	}



}