<?php

class PageControllerPatient extends PageController {
	
	
	public function init() {
		
		Authentication::disallow();
		
	}
	
	
	public function index() {
		
	}
	
	public function searchPatientByName() {
		$records = CMS_Patient_Admit::searchByName(input()->last_name, input()->first_name, input()->middle_name);
		
		$results = array();
		
		// cycle through the records and format them for the JSON handler
		if ($records !== false && is_array($records)) {
			foreach ($records as $patient) {

				$empty = "<span class=\"text-grey\">n/a</span>";

				if ($patient->birthday != '' && $patient->birthday != '0000-00-00') {
					$birthday = date("m/d/Y", strtotime($patient->birthday));
				} else {
					$birthday = $empty;
				}

				if ($patient->sex != '') {
					$gender = $patient->sex;
				} else {
					$gender = $empty;
				}

				if ($patient->ssn != '') {
					$ssn = $patient->ssn;
				} else {
					$ssn = $empty;
				}

				if ($patient->datetime_admit != '') {
					$date_admit = date("m/d/Y", strtotime($patient->datetime_admit));
				} else {
					$date_admit = $empty;
				}

				if ($patient->datetime_discharge != '') {
					$date_discharge = date("m/d/Y", strtotime($patient->datetime_discharge));
				} else {
					$date_discharge = $empty;
				}
								

				$results[] = array(
					"label" => "{$patient->fullName()}",
					"flag_readmission" => $patient->flag_readmission,
					"person_id" => $patient->person_id,
					"birthday" => $birthday,
					"sex" => $gender,
					"ssn" => $ssn,
					"admit_date" => $date_admit,
					"discharge_date" => $date_discharge,
					"is_complete" => $patient->is_complete
				);
			}
		}
		// return to front-end code
		json_return($results);
	}
	
	public function setScheduleStatus() {
		$schedule = new CMS_Schedule(input()->schedule);
		if ($schedule->valid()) {
			if (input()->status != '') {
				$schedule->status = input()->status;
				$schedule->save();
				json_return(array('status' => true));
			}
			
			json_return(array('status' => false));
		}
		
		json_return(array('status' => false));
		
	}
		
	
	public function search_results() {
		if (input()->patient_name == '') {
			$this->redirect(SITE_URL . "/?page=coord");
		} else {
			$obj = CMS_Patient_Admit::generate();
			$facilities = auth()->getRecord()->getFacilities();
			
			
			foreach ($facilities as $f) {
				$results[] = $obj->searchPatients(input()->patient_name, $f->id);
			}	
/* 			$results = $obj->searchPatients(input()->patient_name, $facilities); */
						
			foreach ($results as $k => $r) {
				if (empty($r)) {
					unset($results[$k]);
				}
			}
			
			$statusOptions = array_filter(db()->enumOptions("schedule", "status"));
						
			smarty()->assign('statusOptions', $statusOptions);										
			smarty()->assign('searchName', input()->patient_name);			
			smarty()->assign('results', $results);
		}
		
		
	}
	
	
/*  Added on 2013-06-12 by kwh but not implemented
	public function patient_schedule() {
		if (input()->schedule == '') {
			$this->redirect(auth()->getRecord()->homeURL());
		} else {
			$schedule = new CMS_Schedule(input()->schedule);
			$patient_admit = new CMS_Patient_Admit($schedule->patient_admit);
		}
		
		smarty()->assign('schedule', $schedule);
		smarty()->assign('patient_admit', $patient_admit);
	}
*/
	
	public function submitAdmitRequestNewPatient() {	

		// validate facility
		if (input()->facility == '') {
			feedback()->error("You did not select a facility.");
		} else {
			$facility = new CMS_Facility(input()->facility);
			if (! $facility->valid() ) {
				feedback()->error("You selected an invalid facility.");
			}
		}
				
		// facility is okay. do you have permission?
		if (! feedback()->wasError()) {
			// You must have permission to do this
			if (! auth()->getRecord()->canCreateAdmit($facility)) {
				feedback()->error("Permission denied; you do not have permission to create new Admit Requests.");
			}
		}
		
		// permission is okay. did you enter everything else okay?
		if (! feedback()->wasError()) {
			if (trim(strip_tags(input()->last_name)) == '') {
				feedback()->error("You did not provide a last name for this patient.");
			}
			if (trim(strip_tags(input()->datetime_admit)) == '') {
				feedback()->error("You did not provide a date for patient admit.");
			} else {
				$datetime_admit = datetime(strtotime(input()->datetime_admit));
			}

			if (input()->admit_from == '') {
				feedback()->error("Please enter the location from which the patient will be admitted.");
			} 
			
			
/*
			if (input()->icd9_code != '') {
				$icd9_code = input()->icd9_code;
			} else {
				$icd9_code = '';
			}
*/
			
			/* kwh reverted funcitonality to old hospital search by name
			if (input()->referral_org_name == '__OTHER__') {
				if (input()->referral_org_name_OTHER == '') {
					feedback()->error("Please select a valid referral source.");
				} else {
					$referral_org_name = input()->referral_org_name_OTHER;
				}
			} else {
				if (input()->referral_org_name == '') {
					feedback()->error("Please select a valid referral source.");
				} else {
					$referral_org_name = input()->referral_org_name;
				}
			} */



		}

		if (! feedback()->wasError()) {
			// create a patient record
			$patient = new CMS_Patient_Admit();
			$patient->first_name = input()->first_name;
			$patient->last_name = input()->last_name;
			$patient->middle_name = input()->middle_name;
			$patient->datetime_created = datetime();
			$patient->site_user_created = auth()->getRecord()->id;
			$patient->person_id = generate_pubid();
			$patient->admit_from = input()->admit_from;
			
			if (input()->hospital_id != '') {
				$patient->referred_by_type = 'Organization';
				$patient->referred_by_id = input()->hospital_id;
			}
			
			if (input()->physician_id != '') {
				$patient->referred_by_type = 'Doctor';
				$patient->referred_by_id = input()->physician_id;
			}
			
			if (input()->case_manager_id != '') {
				$patient->referred_by_type = 'Case Manager';
				$patient->referred_by_id = input()->case_manager_id;
			}
			
			if (input()->other_name != '') {
				$patient->referred_by_type = 'Other';
				$patient->referred_by_name = input()->other_name;
				$patient->referred_by_phone = input()->other_phone;
			}
			
			if (input()->home_health != '') {
				$patient->scheduled_home_health = 1;
			}
			
			// check if the admit_from location is a hospital
			$hospital = new CMS_Hospital(input()->admit_from);
			if ($hospital->type == 'Hospital'){
				$patient->hospital_id = input()->admit_from;
			}			
			
			// $patient->icd9_id = $icd9_code;
			$patient->other_diagnosis = input()->other_diagnosis;
			// $patient->referral_org_name = $referral_org_name;
			
			try {
				$patient->save(false);
			} catch (ORMException $e) {
				feedback()->error("There was an error while attempting to save this new patient admit request.");
			}
			
			// now add them to the schedule
			$schedule = new CMS_Schedule();
			if (input()->home_health != '') {
				$schedule->service_disposition = "Other Home Health";
				$schedule->home_health_id = input()->home_health;
			}
			$schedule->patient_admit = $patient->id;
			$schedule->status = 'Under Consideration';
			$schedule->datetime_admit = $datetime_admit;
			$schedule->facility = $facility->id;
			$schedule->elective = input()->elective;
						
			try {
				$schedule->save();
			} catch (ORMException $e) {
				feedback()->error("There was an error while attempting to add this new patient to the schedule");

				// roll back
				CMS_Patient_Admit::delete($patient);
			}
		}
		
		
		$retval = array();
			
		if (feedback()->wasError()) {
			$retval["status"] = false;
			$retval["errors"] = feedback()->getVals("error");
			feedback()->clear();
		} else {
			$retval["status"] = true;
		}
				
		json_return($retval);
		
	}
	
	
	public function submitAdmitRequestExistingPatient() {	

		$readmitType = input()->readmit_type;
				
		// validate facility
		if (input()->facility == '') {
			feedback()->error("You did not provide a facility.");
		} else {
			$facility = new CMS_Facility(input()->facility);
			if (! $facility->valid() ) {
				feedback()->error("You provided an invalid facility.");
			}
		}

		// facility is okay. do you have permission?
		if (! feedback()->wasError()) {
			// You must have permission to do this
			if (! auth()->getRecord()->canCreateAdmit($facility)) {
				feedback()->error("Permission denied; you do not have permission to create new Admit Requests.");
			}
		}
		
		// permission is okay. did you enter everything else okay?
		if (! feedback()->wasError()) {

			if (input()->admit_from == '') {
				feedback()->error("Please enter the location from which the patient will be admitted.");
			} 

			// Removed 2012-02-27 by kwh, reverting to hospital functionality
			// if (input()->referral_org_name == '__OTHER__') {
			// 	if (input()->referral_org_name_OTHER == '') {
			// 		feedback()->error("Please select a valid referral source.");
			// 	} else {
			// 		$referral_org_name = input()->referral_org_name_OTHER;
			// 	}
			// } else {
			// 	if (input()->referral_org_name == '') {
			// 		feedback()->error("Please select a valid referral source.");
			// 	} else {
			// 		$referral_org_name = input()->referral_org_name;
			// 	}
			// }
			
			// validate datetime admit
			if (input()->datetime_admit == '') {
				feedback()->error("You did not provide a date for patient admit.");
			} else {
				$datetime_admit = datetime(strtotime(input()->datetime_admit));
			}	

			// set value for re-admit type
			$readmitType = "Former Patient";

			// if (input()->readmit_type == 'hospital_readmit') {
			// 	$readmitType = 'hospital';
			// } elseif (input()->readmit_type == 'elective') {
			// 	$readmitType = 'elective';
			// } else {
			// 	feedback()->error("No Re-admit type has been set.");
			// }
		
			// make sure not already scheduled
			$existingSchedule = current(CMS_Schedule::getScheduleByPerson(input()->person_id, input()->datetime_admit));
			if ($existingSchedule != false) {
				feedback()->error("Unable to add {$existingSchedule->getPatient()->fullName()} to schedule; he/she is already scheduled for admission to {$existingSchedule->getFacility()->name} on {$existingSchedule->admitDatetimeFormatted()}");
			}
		}
		
		// Go away on error
		if (feedback()->wasError()) {
			//$this->redirect();
			$this->redirect(SITE_URL . "/?page=coord&action=admit");
		}
		
	
		$record = CMS_Patient_Admit::newAdmitExisting(input()->person_id, $datetime_admit,$readmitType);
		$record->admit_from = input()->admit_from; // replaced the referral_org_name below
		// $record->other_diagnosis = input()->other_diagnosis;
		// $record->referral_org_name = $referral_org_name;
		$record->hospital_id = input()->admit_from;
		
		if (input()->hospital_id != '') {
			$record->referred_by_type = 'Organization';
			$record->referred_by_id = input()->hospital_id;
		}
		
		if (input()->physician_id != '') {
			$record->referred_by_type = 'Doctor';
			$record->referred_by_id = input()->physician_id;
		}
		
		if (input()->case_manager_id != '') {
			$record->referred_by_type = 'Case Manager';
			$record->referred_by_id = input()->case_manager_id;
		}
		
		if (input()->other_name != '') {
			$record->referred_by_type = 'Other';
			$record->referred_by_name = input()->other_name;
			$record->referred_by_phone = input()->other_phone;
		}
		
		if (input()->home_health != '') {
			$record->scheduled_home_health = 1;
		}


		$record->site_user_created = auth()->getRecord()->id;
		$record->save();
		
		if ($record != false) {
			if ($record->valid()) {
				// update hospital
				//$record->hospital = $hospital->id;
				//$record->save();
				
				// add to the schedule
				$schedule = new CMS_Schedule;
				if (input()->home_health != '') {
					$schedule->service_disposition = "Other Home Health";
					$schedule->home_health_id = input()->home_health;
				}
				$schedule->datetime_admit = $datetime_admit;
				$schedule->readmit_type = $readmitType;
				$schedule->facility = $facility->id;
				$schedule->patient_admit = $record->id;
				$schedule->status = 'Under Consideration';
				$schedule->elective = input()->elective;
				$schedule->save();
				
				feedback()->conf("New admit has been saved to system for '{$record->fullName()}'");
			}
		}
		$this->redirect(SITE_URL . "/?page=coord");
	}

	public function submitReadmit() {
		// validate facility
		if (input()->facility == '') {
			feedback()->error("You did not select a valid facility to which the patient will admit.  Please select a facility and try again.");
		} else {
			$facility = new CMS_Facility(input()->facility);
			if (! $facility->valid()) {
				feedback()->error("You provided an invalid facility.");
			} 
		}

		// facility is okay. do you have permission?
		if (! feedback()->wasError()) {
			// You must have permission to do this
			if (! auth()->getRecord()->canCreateAdmit($facility)) {
				feedback()->error("Permission denied; you do not have permission to create new Admit Requests.");
			}
		}
		
		// permission is okay. did you enter everything else okay?
		if (! feedback()->wasError()) {
			if (input()->datetime_admit == '') {
				feedback()->error("You did not enter a Re-Admit date.");
			} else {
				$datetime_admit = datetime(strtotime(input()->datetime_admit));
			}
			if (input()->hospital == '') {
				feedback()->error("Please enter the hospital from which the patient will be re-admitted.");
			} 
			if (input()->patient == '') {
				feedback()->error("No patient has been selected for Re-Admisison.  Please try again.");
			} 
			if (input()->other_diagnosis == '') {
				feedback()->error("Please enter the admission diagnosis.");
			}
			
			// make sure not already scheduled
			$existingSchedule = current(CMS_Schedule::getScheduleByPerson(input()->patient, $datetime_admit));
			if ($existingSchedule != false) {
				feedback()->error("Unable to add {$existingSchedule->getPatient()->fullName()} to schedule; he/she is already scheduled for admission to {$existingSchedule->getFacility()->name} on {$existingSchedule->admitDatetimeFormatted()}");
			}
		}

		// Go away on error
		if (feedback()->wasError()) {
			$this->redirect();
			// $this->redirect(SITE_URL . "/?page=coord&action=admit");
		}
		$readmit_type = "From Hospital";
		$record = CMS_Patient_Admit::newAdmitExisting(input()->patient, input()->patient_id, input()->datetime_admit, $readmit_type);
		$record->hospital = input()->hospital;
		$record->icd9_id = input()->icd9;		
		$record->other_diagnosis = input()->other_diagnosis;
		$record->save();
		
		if ($record != false) {
			if ($record->valid()) {
				// update hospital
				//$record->hospital = $hospital->id;
				//$record->save();
				
				// add to the schedule
				$schedule = new CMS_Schedule;
				$schedule->datetime_admit = input()->datetime_admit;
				$schedule->facility = $facility->id;
				$schedule->datetime_admit = $datetime_admit;
				$schedule->patient_admit = $record->id;
				$schedule->status = 'Under Consideration';
				$schedule->save();
				
				feedback()->conf($record->fullName() . " has been scheduled for re-admission to " . $facility->name . "  on " . $datetime_admit . ".");
			}
		}
		$this->redirect(SITE_URL . "/?page=coord");



	}

	public function notes() {
		if (input()->schedule != '') {
			$schedule = new CMS_Schedule(input()->schedule);
			smarty()->assignByRef("schedule", $schedule);
			if ($schedule->valid()) {
				$patient_admit = $schedule->patient_admit;
			}	
		} elseif (input()->id != '') {
			$patient_admit = input()->id;
		}
		
		if ($patient_admit != '') {
			$patient = new CMS_Patient_Admit($patient_admit) ;
			smarty()->assign("patient", $patient);
		} else {
			feedback()->error("Invalid patient/admit requested.");
			$this->redirect(SITE_URL . "/?page=");
		}
	}

	public function setFinalOrders() {
		// You must be a coordinator to do this.
		if (auth()->getRecord()->isAdmissionsCoordinator() == 0) {
			feedback()->error("Permission denied.");
			$this->redirect(auth()->getRecord()->homeURL());
		}		
		$patient = new CMS_Patient_Admit(input()->patient);
		if ($patient->valid()) {
			if ($patient->readyForNotes() == false) {
				feedback()->error("Unable to perform this action to this record -- please make sure the Inquiry Form has been completed for this patient.");
				$this->redirect();
			}
			try {
				$final_orders = (input()->final_orders == 1) ? 1 : 0; 
				$patient->final_orders = $final_orders;
				$patient->save();
				feedback()->conf("Your changes have been saved.");
			} catch (ORMException $e) {
				feedback()->error($e->getMessage());
			}
		} else {
			feedback()->error("Invalid patient specified.");
		}
		$this->redirect();
		
	}
	
	public function addNotes() {
		// You must be a coordinator to do this.
		if (auth()->getRecord()->isAdmissionsCoordinator() == 0) {
			feedback()->error("Permission denied.");
			$this->redirect(auth()->getRecord()->homeURL());
		}		
		$patient = new CMS_Patient_Admit(input()->patient);
		if ($patient->valid()) {
			if ($patient->readyForNotes() == false) {
				feedback()->error("Unable to add medical records file to this record -- please make sure the Inquiry Form has been completed for this patient.");
				$this->redirect();
			}
			for ($i=1;$i<9;$i++) {
				if ($_FILES["notes_file_upload{$i}"]['name'] != '') {
					try {
						$patient->attachNotes("notes_file_upload{$i}", input()->{"notes_name{$i}"});
												
						// send notification if there are active schedulings
						$schedule = new CMS_Schedule(input()->schedule);
						if ($schedule !== false) {
								if ($schedule->facility != '') {
									CMS_Notify_Event::trigger("notes_uploaded", $schedule);
								}
						}
						feedback()->conf("Medical records file has been attached.");
					} catch (ORMException $e) {
						feedback()->error($e->getMessage());
					}
				}
			}
		} else {
			feedback()->error("Invalid patient specified.");
		}
		$this->redirect();
	}
	
	public function removeNotes() {
		// You must be a coordinator to do this.
		if (auth()->getRecord()->isAdmissionsCoordinator() == 0) {
			feedback()->error("Permission denied.");
			$this->redirect(auth()->getRecord()->homeURL());
		}		
		
		if (input()->idx == '') {
			feedback()->error("Unable to remove medical records file -- an error occurred.");
			$this->redirect();
		}
		
		$patient = new CMS_Patient_Admit(input()->patient);
		if ($patient->valid()) {
			try {
				$notes_field = "notes_file" . input()->idx;
				$patient->removeNotes($notes_field);
				feedback()->conf("Medical records file has been removed.");
			} catch (ORMException $e) {
				feedback()->error($e->getMessage());
			}
		} else {
			feedback()->error("Invalid patient specified.");
		}
		$this->redirect();
	}
	
	
	public function downloadNotesFile() {
		$schedule = new CMS_Schedule(input()->schedule);
		if ($schedule->valid()) {
			$patient = $schedule->getPatient();
			if ($patient->valid()) {
				$idx = input()->idx;
				if ($patient->{"notes_file{$idx}"} != '') {
					$widget = $patient->getFieldWidget("notes_file{$idx}");				
					$path = $widget->getAssetPath() . "/" . $patient->{"notes_file{$idx}"};
					header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
					header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
					header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0", false);
					//header("X-Download-Options: noopen "); // For IE8
					//header("X-Content-Type-Options: nosniff"); // For IE8					
					header("Pragma: public");
					header("Content-Type: application/pdf");
					$filename = "AHC.Medical." . time() . ".pdf" ;
					header("Content-Disposition: attachment; filename=\"{$filename}\"");
					readfile($path);
					exit;
				}
			}
		}
		
	}
	
	public function previewNotesFileImage() {
		
		// get the page offset we'll start at, or default to 0
		$offset = (input()->offset != '' && is_numeric(input()->offset) && input()->offset > 0) ? input()->offset : 0;
		
		// get the number of pages from the request, or default to 5
		$numPages = (input()->numPages != '' && is_numeric(input()->numPages) && input()->numPages > 0) ? input()->numPages : 5;

		// we should be told when invoked how many total pages there are
		$totalPages = input()->totalPages;
		
		// the schedule record
		$schedule = new CMS_Schedule(input()->schedule);

		// schedule must be valid
		if ($schedule->valid()) {
			// the associated patient record
			$patient = $schedule->getPatient();

			// which notes file?
			$idx = input()->idx;
			if ($patient->{"notes_file{$idx}"} != '') {
				// widget will dynamically return the path to the asset
				$widget = $patient->getFieldWidget("notes_file{$idx}");
				
				// resolve the filename out of the DB record
				$pdfname = $patient->{"notes_file{$idx}"};
				$pdfPath = $widget->getAssetPath() . "/{$pdfname}";
				// must exist on disk
				if (file_exists($pdfPath)) {
					$width = input()->width;
					try {
						// construct an Imagick instance
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
							$image_sub->readImage($pdfPath . "[{$i}]");
							$image_sub->thumbnailImage($width, 0);
							$image->addImage($image_sub);
						}
						$image->resetIterator();
						// stack them horizontally
						$image_multi = $image->appendImages(false);
			
						// add in the  blob back to the item and output as a PNG
						$image_multi->setImageFormat('png');
						header("Content-type: image/png");
						echo $image_multi->getImageBlob();
					} catch (ImagickException $e) {
						// TODO ouput a 'not available' image
					}
				} else {
					// TODO ouput a 'not available' image
				}
			}
		}
		exit;		
	}
	
	public function previewNotesFile() {
		
		// get the page offset we'll start at, or default to 0
		$offset = (input()->offset != '' && is_numeric(input()->offset) && input()->offset > 0) ? input()->offset : 0;
		smarty()->assign("offset", $offset);

		// number of pages in a "chunk"
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

		$schedule = new CMS_Schedule(input()->schedule);

		if (! $schedule->valid()) {
			feedback()->error("Invalid schedule ID.");
			// TODO where to redirect?
			$this->redirect(SITE_URL . "/?page=coord");
		} else {
			// figure out how many total pages there are:
			
			// the associated patient record
			$patient = $schedule->getPatient();
			
			// which notes file?
			$idx = input()->idx;
			smarty()->assign("idx", $idx);
			if ($patient->{"notes_file{$idx}"} != '') {
				// widget will dynamically return the path to the asset
				$widget = $patient->getFieldWidget("notes_file{$idx}");
				
				// resolve the filename out of the DB record
				$pdfname = $patient->{"notes_file{$idx}"};
				$pdfPath = $widget->getAssetPath() . "/{$pdfname}";

				// must exist on disk
				if (file_exists($pdfPath)) {
					try {
						// construct an Imagick instance
						$image = new Imagick($pdfPath);
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
						$this->redirect(urldecode(input()->b));
					}
				} else {
					feedback()->error("File not found on disk.");
					$this->redirect(urldecode(input()->b));
				}
			}
		}
		smarty()->assignByRef("schedule", $schedule);
		
	}
	
	/*
	public function viewNotesFile() {
		$schedule = new CMS_Schedule(input()->schedule);
		if ($schedule->valid()) {
			$patient = $schedule->getPatient();
			$idx = input()->idx;
			if ($patient->{"notes_file{$idx}"} != '') {
				$widget = $patient->getFieldWidget("notes_file{$idx}");
				$pdfname = $patient->{"notes_file{$idx}"};
				$basename = basename($pdfname, ".pdf");
				$bucketPath = $widget->getAssetPath() . "/{$basename}";
				
				if (! file_exists($bucketPath) ) {
					feedback()->error("Unable to display this file. If this was a recently-uploaded file, you may need to wait up to 15 minutes for it to become available for online viewing.");
					$this->redirect(SITE_URL . "/?page=patient&action=notes&schedule={$schedule->pubid}");
				}
				
				$relpaths = array();
				$dir = dir($bucketPath);
				while (false !== ($entry = $dir->read())) {
					if ($entry == '.' || $entry == '..' || ! preg_match("/\.png/", $entry)) {
						continue;
					}
					$relpaths[] = $widget->getAssetSubPath() . "/{$basename}/{$entry}";
				}
				smarty()->assignByRef("schedule", $schedule);
				smarty()->assign("relpaths", $relpaths);
				smarty()->assignByRef("patient", $patient);
				smarty()->assign("idx", $idx);
			}
		}
	}
	
	public function notesImage() {
		if (input()->max_width != '' || input()->max_height != '') {
			define(SOURCE_IS_PROTECTED, TRUE);
			require_once ENGINE_PUBLIC_PATH . "/image.php";			
		} else {
			header("Content-type: image/png");
			echo file_get_contents(APP_PATH . "/protected/" . input()->_image);
		}
	}
	
	public function notesSWF() {
		header("Content-type: application/x-shockwave-flash");
		$patient = new CMS_Patient_Admit(input()->patient);
		if ($patient->valid()) {
			$idx = input()->idx;
			echo file_get_contents(APP_PATH . "/protected/assets/patient_notes_swf{$idx}/" . basename($patient->{"notes_file{$idx}"}) . ".swf");
		}
		exit;
	}
	*/
	
	public function inquiry() {

		// You must be a coordinator to do this.
		//if (auth()->getRecord()->isAdmissionsCoordinator() == 0) {
		//	feedback()->error("Permission denied.");
		//	$this->redirect(auth()->getRecord()->homeURL());
		//}
		
		if (auth()->getRecord()->isAdmissionsCoordinator() == 1) {
			$user = true; // if this returns true the user can enter/edit the physician info
		} else {
			$user = false;
		}
		smarty()->assign('user', $user);
		
		smarty()->assign('weekSeed', date('Y-m-d', strtotime(input()->weekSeed)));
		
		if (input()->schedule != '') {
			$schedule = new CMS_Schedule(input()->schedule);
			$facility = new CMS_Facility($schedule->facility);
			smarty()->assignByRef("schedule", $schedule);
			if ($schedule->valid()) {
				$patient_admit = $schedule->patient_admit;
			}	
		} elseif (input()->id != '') {
			$patient_admit = input()->id;
		}

		if ($patient_admit != '') {
			$patient = new CMS_Patient_Admit($patient_admit) ;
			if ($patient->valid() == false) {
				feedback()->error("Invalid patient/admit record requested.");
				$this->redirect(SITE_URL . "/?page=coord");
			}
			// Get list of physician names
			// commented out on 2015-08-11 by kwh appeared to be breaking the page
			// and did not seem to be used.
			// $physicians = new CMS_Physician();
			// $pNames = $physicians->getPhysicians();

			smarty()->assign("facility", $facility);
			// smarty()->assign("pNames", $pNames);
			smarty()->assignByRef("schedule", $schedule);
			smarty()->assignByRef("patient", $patient);
			smarty()->assign("mode", input()->mode);
		} else {
			feedback()->error("Invalid patient/admit record requested.");
			$this->redirect(SITE_URL . "/?page=coord");
		}
						
	}
	
	public function submitInquiry() {
				
		if (input()->weekSeed != '') {
			$weekSeed = input()->weekSeed;
		}	
			
		if (input()->schedule != '') {
			// if you specified a schedule record, that schedule has a facility and you must have access for that facility.
			$schedule = new CMS_Schedule(input()->schedule);
			if ($schedule->valid()) {
				if (! auth()->getRecord()->canEditInquiry($schedule->getFacility())) {
					feedback()->error("Permission denied; you do not have permission to modify inquiry records for {$schedule->getFacility()->name}.");
				}
			} else {
				feedback()->error("Invalid schedule record specified.");
			}
		} else {
			// otherwise you need coordinator access
			if (auth()->getRecord()->isAdmissionsCoordinator() == 0) {
				feedback()->error("Permission denied.");
			}
		}
		
		if (auth()->getRecord()->isAdmissionsCoordinator() == 1) {
			$coord = true; // if this returns true the user can enter/edit the physician info
		} else {
			$coord = false;
		}
		
		// Load facility info so the page can redirect appropriately
		$facility = CMS_Facility::generate();
		$facility->load($schedule->facility);
		

		if (feedback()->wasError()) {		
			$this->redirect(auth()->getRecord()->homeURL());
		}
		
		global $availOptions;
		
		// initialize inquiry record for saving
		if (input()->id != '') {
			$obj = new CMS_Patient_Admit(input()->id) ;
			if ($obj->valid() == false) {
				feedback()->error("Invalid patient/admit record requested.");
				$this->redirect(SITE_URL . "/?page=patient&action=inquiry");
			}			
		} else {
			$obj = new CMS_Patient_Admit;
			$obj->datetime_created = datetime();			
		}
			
		// number to call for nursing report
		$obj->nursing_report_phone = input()->nursing_report_phone;
		
		// referral sources
		/*if (input()->admit_from == '__OTHER__') {
			if (input()->referral_org_name_OTHER == '') {
				feedback()->error("Please select a valid referral source.");
			} else {
				$obj->admit_from = input()->referral_org_name_OTHER;
			}
		} else { */
			if (input()->admit_from == '') {
				// feedback()->error("Please select a valid referral source.");
			} else {
				$obj->admit_from = input()->admit_from;
			}
		// }
		
		$obj->case_manager_id = input()->case_manager_id;
		
		// referral nurse name
		$obj->referral_nurse_name = input()->referral_nurse_name;

		// referral phone
		$obj->referral_phone = input()->referral_phone;
		
		
		// referral contact name
		if (input()->referral_org_contact_name == '' && $schedule->facility != 4) {
			feedback()->error("Please provide the name of your organizational referral contact.");
		} else {
			$obj->referral_org_contact_name = input()->referral_org_contact_name;
		}
		
		// Transportation
		if (input()->trans == '') {
			//feedback()->error("Missing value: Transportation");
		} else {
			$obj->trans = input()->trans;
		}
		
		// transportation provider (optional)
		$obj->trans_provider = input()->trans_provider;

		// Oxygen
		if (input()->o2 == 1 && input()->o2_liters == '') {
			feedback()->error("Missing value: O2 liters");
		} else {
			if (input()->o2 == 1) {
				$obj->o2 = 1;
				$obj->o2_liters = input()->o2_liters;
			} else {
				$obj->o2 = 0;
				$obj->o2_liters = '';				
			}
		}
		
		// pick-up time
		if (input()->datetime_pickup != '') {
			$obj->datetime_pickup = input()->datetime_pickup;
		} else {
			$obj->datetime_pickup = '';
		}
				
		// Last name
		if (input()->last_name == '') {
			//feedback()->error("Missing value: last name");
		} else {
			$obj->last_name = input()->last_name;
		}
		
		// First name
		if (input()->first_name == '') {
			//feedback()->error("Missing value: first name");
		} else {
			$obj->first_name = input()->first_name;
		}
		
		// Middle name
		$obj->middle_name = input()->middle_name;
		
		// Preferred Name
		$obj->preferred_name = input()->preferred_name;
		
		// Address
		if (input()->address == '') {
			//feedback()->error("Missing value: street address");
		} else {
			$obj->address = input()->address;
		}
		
		// City
		if (input()->city == '') {
			//feedback()->error("Missing value: city");
		} else {
			$obj->city = input()->city;
		}
		
		// State
		if (input()->state == '') {
			//feedback()->error("Missing value: state");
		} else {
			$validate = Validate::is_USAState(input()->state);
			if ($validate->success() == false) {
				feedback()->error("Please correct: State " . $validate->message());
			} else {
				$obj->state = input()->state;
			}
		}
		
		// zip code
		if (input()->zip == '') {
			//feedback()->error("Missing value: zip code");
		} else {
			$validate = Validate::is_zipcode(input()->zip);
			if ($validate->success() == false) {
				feedback()->error("Please correct: Zipcode " . $validate->message());
			} else {
				$obj->zip = input()->zip;
			}
		}
		
		// Phone number
		if (input()->phone == '') {
			//feedback()->error("Missing value: phone number");
		} else {
			$validate = Validate::is_phone(input()->phone);
			if ($validate->success() == false) {
				feedback()->error("Please correct: Phone number " . $validate->message());
			} else {
				$obj->phone = input()->phone;
				$obj->phone_type = input()->phone_type;
			}
		}
		
		// Phone number (secondary)
		if (input()->phone_alt == '') {
			//feedback()->error("Missing value: phone number (secondary)");
		} else {
			$validate = Validate::is_phone(input()->phone_alt);
			if ($validate->success() == false) {
				feedback()->error("Please correct: Phone number (secondary) " . $validate->message());
			} else {
				$obj->phone_alt = input()->phone_alt;
				$obj->phone_alt_type = input()->phone_alt_type;
			}
		}
		
		
		// Birthdate
		if (input()->birthday == '') {
			//feedback()->error("Missing value: birthday");
		} else {
			$validate = Validate::is_american_date(input()->birthday);
			if ($validate->success() == false) {
				feedback()->error("Please correct: Birthday " . $validate->message());
			} else {
				$obj->birthday = date("Y-m-d", strtotime(input()->birthday));
			}
		}
		
		// Sex
		if (input()->sex != 'Male' && input()->sex != 'Female' && $schedule->facility != 4) {
			feedback()->error("Please select either Male or Female for the patient's sex.");
		} else {
			$obj->sex = input()->sex;
		}
		
		// Ethnicity
		if (input()->ethnicity == '') {
			//feedback()->error("Missing value: ethnicity");
		} else {
			if (! in_array(input()->ethnicity, $availOptions['ethnicities'])) {
				feedback()->error("Please correct: invalid ethnicity.");
			} else {
				$obj->ethnicity = input()->ethnicity;
			}
		}
		
		// Marital status
		if (input()->marital_status == '') {
			//feedback()->error("Missing value: marital status");
		} else {
			if (! in_array(input()->marital_status, $availOptions['maritalStatus'])) {
				feedback()->error("Please correct: invalid marital status");
			} else {
				$obj->marital_status = input()->marital_status;
			}
		}
		
		// State Born
		if (input()->state_born == '') {
			//feedback()->error("Missing value: state born");
		} else {
			$validate = Validate::is_USAState(input()->state_born);
			if ($validate->success() == false) {
				feedback()->error("Please correct: State born " . $validate->message());
			} else {
				$obj->state_born = input()->state_born;
			}
		}
		
		// Religion
		if (input()->religion != '') {
			$obj->religion = input()->religion;
		}
		
		// SSN
		if (input()->ssn == '') {
			//feedback()->error("Missing value: SSN");
		} else {
			$validate == Validate::is_SSN(input()->ssn);
			//if ($validate->success() == false) {
				// feedback()->error("Please correct: SSN " . $validate->message());
			//} else {
				$obj->ssn = input()->ssn;
			//}
		}
				
		// validate hospital
		if (input()->hospital_id != '') {
			$obj->hospital_id = input()->hospital_id;
		} 
		
		// Attending Physician
		if (auth()->getRecord()->isAdmissionsCoordinator() == 1) {
			if (input()->physician != '') {
				$obj->physician_id = input()->physician;
			}
		} 
		
		// Orthopedic Surgeon
		if (input()->ortho != '') {
			$obj->ortho_id = input()->ortho;
		}
		
		// Primary Doctor
		if (input()->doctor != '') {
			$obj->doctor_id = input()->doctor;
		}
				
		if (input()->pharmacy != '') {
			$obj->pharmacy_id = input()->pharmacy;
		}
				
		// Hospital room #
		if (input()->hospital_room == '') {
			//feedback()->error("Missing value: hospital room number");
		} else {
			$obj->hospital_room = input()->hospital_room;
		}
		
		// Hospital start date
		if (input()->hospital_date_start == '') {
			//feedback()->error("Missing value: hospital stay start date");
		} else {
			$validate = Validate::is_american_date(input()->hospital_date_start);
			if ($validate->success() == false) {
				feedback()->error("Hospital stay start date " . $validate->message());
			} else {
				$obj->hospital_date_start = date("Y-m-d", strtotime(input()->hospital_date_start));
			}
		}
		
		// Hospital end date
		if (input()->hospital_date_end == '') {
			//feedback()->error("Missing value: hospital stay end date");
		} else {
			$validate = Validate::is_american_date(input()->hospital_date_end);
			if ($validate->success() == false) {
				feedback()->error("Hospital stay end date " . $validate->message());
			} else {
				$obj->hospital_date_end = date("Y-m-d", strtotime(input()->hospital_date_end));
			}
		}
		
		// Paymethod, Medicare and dependent fields
		if (input()->paymethod == '') {
			//feedback()->error("Missing value: payment method");
		} else {
			$obj->paymethod = input()->paymethod;

			// Medicare number
			$obj->medicare_number = input()->medicare_number;
			
			// Days used
			if (input()->medicare_days_used != '') {
				if (! Validate::is_natural(input()->medicare_days_used, true)->success()) {
					feedback()->error("Invalid entry: Medicare days used must be an integer.");
				} else {
					$obj->medicare_days_used = input()->medicare_days_used;
				}
			} else {
				$obj->medicare_days_used = '';
			}
						
			// Days available
			if (input()->medicare_days_available != '') {
				if (! Validate::is_natural(input()->medicare_days_available, true) ) {
					feedback()->error("Invalid entry: Medicare days available must be an integer.");
				} else {
					$obj->medicare_days_available = input()->medicare_days_available;
				}
			} else {
				$obj->medicare_days_available = '';
			}
										
		}
		
		// Three night hospital stay
		$obj->three_night = (input()->three_night == 1) ? 1 : 0;
		
		

		// ICD-9 Code
		if (input()->icd9_code != '') {
				$obj->icd9_id = input()->icd9_code;
			} else {
				$obj->icd9_id = '';
			}
		
		// X-Rays received (these are not required in states other than AZ - KWH)
		if ($schedule->getFacility()->state == 'AZ') {
			if (input()->x_rays_received == '0') {
				feedback()->error("Chest X-Rays have not been received.");
			} else {
				$obj->x_rays_received = input()->x_rays_received;
			}
		}

		
		// toured
		if (input()->toured == '') {
			//feedback()->error("Missing value: toured (yes/no).");
		} else {
			$obj->toured = input()->toured;
		}
		
				
		// Supplemental insurance
		$obj->supplemental_insurance_name = input()->supplemental_insurance_name;
		$obj->supplemental_insurance_number = input()->supplemental_insurance_number;
		
		// HMO
		$obj->hmo_name = input()->hmo_name;
		$obj->hmo_number = input()->hmo_number;
		$obj->hmo_auth_number = input()->hmo_auth_number;
		
		// Patient type -- long-term or short-term?
		$schedule->long_term = input()->patient_type;
		
		// Private pay guarantor
		$obj->private_pay_guarantor_name = input()->private_pay_guarantor_name;
		$obj->private_pay_guarantor_relationship = input()->private_pay_guarantor_relationship;
		$obj->private_pay_guarantor_address = input()->private_pay_guarantor_address;
		$obj->private_pay_guarantor_phone = input()->private_pay_guarantor_phone;
		
		// Emergency Contacts
		$obj->emergency_contact_name1 = input()->emergency_contact_name1;
		$obj->emergency_contact_relationship1 = input()->emergency_contact_relationship1;
		$obj->emergency_contact_address1 = input()->emergency_contact_address1;
		$obj->emergency_contact_phone1 = input()->emergency_contact_phone1;
		$obj->emergency_contact_phone_alt1 = input()->emergency_contact_phone_alt1;
		
		$obj->emergency_contact_name2 = input()->emergency_contact_name2;
		$obj->emergency_contact_relationship2 = input()->emergency_contact_relationship2;
		$obj->emergency_contact_address2 = input()->emergency_contact_address2;
		$obj->emergency_contact_phone2 = input()->emergency_contact_phone2;
		$obj->emergency_contact_phone_alt2 = input()->emergency_contact_phone_alt2;
		
		
		if (input()->home_health != '') {
			$obj->scheduled_home_health = 1;
		}
		
		if (input()->home_health != '') {
			$schedule->service_disposition = "Other Home Health";
			$schedule->home_health_id = input()->home_health;
		}
		
		$schedule->elective = input()->elective;
		
		// Admission diagnosis
		$obj->other_diagnosis = input()->other_diagnosis;
		
		// Discharge Plan
		$obj->discharge_plan = input()->discharge_plan;
		
		// Comments
		$obj->comments = input()->comments;
		
		// Elective
		$schedule->confirmed = input()->confirmed;
		$schedule->datetime_confirmed = date('Y-m-d H:i', strtotime("now"));
		$schedule->site_user_confirmed = auth()->getRecord()->id;
		
		// Final Orders (moved by KWH per request)
		if (input()->final_orders == 1) {
			$obj->final_orders = 1;
		} else {
			$obj->final_orders = 0;
		}
		
		if (input()->datetime_dc_summary != '') {
			$obj->datetime_dc_summary = date('Y-m-d H:i:s', strtotime(input()->datetime_dc_summary));
		}
		
		if (input()->referral == 1) {
			$obj->referral = 1;
		} else {
			$obj->referral = 0;
		}
		
		try {
			$obj->save();
			$schedule->save();
			feedback()->conf("Inquiry record for %s has been saved and entered into the system.", "<b>{$obj->first_name} {$obj->last_name}</b>");
		} catch (ORMException $e) {
			feedback()->error("An error was encountered while trying to save this patient record.");
		}
				
		if (feedback()->wasError()) {
			if (!$coord) {
				$this->redirect(SITE_URL . "/?page=facility&id={$facility->pubid}&weekSeed={$weekSeed}");
			} else {
				if (input()->add_case_manager == "add-case-manager") {
					$this->redirect(SITE_URL . "/?page=caseManager&action=add&schedule=$schedule->pubid");
				} elseif (input()->add_physician == "add-physician") {
					$this->redirect(SITE_URL . "/?page=physician&action=add&type=physician&schedule=$schedule->pubid");
				} elseif (input()->add_surgeon == "add-surgeon") {
					$this->redirect(SITE_URL . "/?page=physician&action=add&type=surgeon&schedule=$schedule->pubid");
				} elseif (input()->add_doctor == "add-doctor") {
					$this->redirect(SITE_URL . "/?page=physician&action=add&type=doctor&schedule=$schedule->pubid");
				} elseif (input()->add_pharmacy == "add-pharmacy") {
					$this->redirect(SITE_URL . "/?page=pharmacy&action=add&schedule=$schedule->pubid");
				} else {
					$this->redirect(SITE_URL . "/?page=coord");	
				}							
			}	
		} else {
			// need to redirect based on permissions
			if (!$coord) {
				$this->redirect(SITE_URL . "/?page=facility&id={$facility->pubid}&weekSeed={$weekSeed}");
			} else {
				if (input()->add_case_manager == "add-case-manager") {
					$this->redirect(SITE_URL . "/?page=caseManager&action=add&schedule=$schedule->pubid");
				} elseif (input()->add_physician == "add-physician") {
					$this->redirect(SITE_URL . "/?page=physician&action=add&type=physician&schedule=$schedule->pubid");
				} elseif (input()->add_surgeon == "add-surgeon") {
					$this->redirect(SITE_URL . "/?page=physician&action=add&type=surgeon&schedule=$schedule->pubid");
				} elseif (input()->add_doctor == "add-doctor") {
					$this->redirect(SITE_URL . "/?page=physician&action=add&type=doctor&schedule=$schedule->pubid");
				} else {
					$this->redirect(SITE_URL . "/?page=coord");	
				}			
			}
				
		}
		
	}
	
	public function onsite_assessment() {
	
		if (input()->schedule != '') {
			$schedule = new CMS_Schedule(input()->schedule);
			smarty()->assignByRef("schedule", $schedule);
			if ($schedule->valid()) {
				$onsite_visits = CMS_Onsite_Visit::generate();
				$onsite_visit = $onsite_visits->findVisitByPatientAdmit($schedule->id);	
				if (empty ($onsite_visit)) {
					smarty()->assign('onsite_visit', '');
				} else {
					smarty()-> assignByRef('onsite_visit', $onsite_visit);
				}
			} else {
				feedback()->error("Invalid patient schedule selected.");
				$this->redirect(SITE_URL . "/?page=coord");
			}
			if (input()->id != '') {
				$patient = new CMS_Patient_Admit(input()->id);
			} else {
				$patient = new CMS_Patient_Admit($schedule->patient_admit);
			}
			smarty()->assign('location', $location);
			smarty()->assign("pNames", $pNames);
			smarty()->assignByRef("patient", $patient);

		} else {
			feedback()->error("Invalid patient schedule selected.");
			$this->redirect(SITE_URL . "/?page=coord");
		}
					
	}
	
	public function submitOnsiteAssessment() {
							
		if (input()->schedule != '') {
			$schedule = new CMS_Schedule(input()->schedule);
		}
				
		// initialize onsite_visit for saving
		$obj = new CMS_Onsite_Visit();

		// set onsite_visit.schedule equal to the schedule id		
		$obj->schedule = $schedule->id;
		
		// check visit location
		if (input()->visit_location == '') {
			feedback()->error("Please enter the location where you visited the patient and try again.");
			$this->redirect();
		} else {
			$obj->visit_location = input()->visit_location;
		}
			
		// check date and time
		if (input()->datetime_visit == '') {
			feedback()->error("You must enter the date and time you visited the patient");
			$this->redirect();
		} else {
			$obj->datetime_visit = date('Y-m-d H:i:s', strtotime(input()->datetime_visit));
		}
		
		// check disposition
		if (input()->disposition == '') {
			feedback()->error("You must enter the disposition of the patient");
			$this->redirect();
		} else {
			$obj->disposition = input()->disposition;
		}
		
		// if disposition is other_location require the discharge_location field
		if (input()->disposition == 'other_location') {
			if (input()->discharge_location_id != '') {
				$obj->discharge_location = input()->discharge_location_id;
			}
		}

		
		$obj->comments = input()->comments;
		$obj->site_user_visited = auth()->getRecord()->id;
		
		try {
			$obj->save();
			feedback()->conf("The on-site assessment for %s has been saved.", "<b>{$patient->first_name} {$patient->last_name}</b>");
		} catch (ORMException $e) {
			feedback()->error("An error was encountered while trying to save the on-site assessment.");
		}
				
		if (feedback()->wasError()) {
			$this->redirect();
		} else {
			$this->redirect(SITE_URL . "/?page=coord");			
		}

		
		
		
			
	}
	
	public function nursing() {
		$patient = new CMS_Patient_Admit(input()->patient);
		if ($patient->valid()) {
			smarty()->assignByRef("patient", $patient);
			if ($patient->hasNursingReport()) {
				$nursing = $patient->getNursingReport();
				if ($nursing != false) {
					smarty()->assignByRef("nursing", $nursing);
				}
				smarty()->assign("mode", input()->mode);
			}
		} else {
			feedback()->error("Invalid patient record.");
			$this->redirect(auth()->getRecord()->homeURL());
		}
	}
	
	public function printNursing() {
		$patient = new CMS_Patient_Admit(input()->patient);
		if ($patient->valid()) {
			smarty()->assignByRef("patient", $patient);
			if ($patient->hasNursingReport()) {
				$nursing = $patient->getNursingReport();
				if ($nursing != false) {
					smarty()->assignByRef("nursing", $nursing);
				}
				smarty()->assign("mode", input()->mode);
			}
		} else {
			feedback()->error("Invalid patient record.");
			$this->redirect(auth()->getRecord()->homeURL());
		}
	}
	
	public function submitNursing() {
		
		$patient = new CMS_Patient_Admit(input()->patient_admit);
		
		if (! auth()->getRecord()->canEditNursing()) {
			$this->redirect(SITE_URL . "/?page=patient&action=nursing&patient={$patient->pubid}");
		}
		$obj = $patient->getNursingReport();
		if ($obj == false) {
			$obj = new CMS_Patient_Admit_Nursing;
			$obj->patient_admit = $patient->id;
		}
		
		$obj->diagnosis = input()->diagnosis;
						
		$obj->height = input()->height;
						
		$obj->weight = input()->weight;				
		if (input()->orientation_alert != 1) {
			$obj->orientation_alert = 0;
		} else {
			$obj->orientation_alert = 1;
		}
						
		if (input()->orientation_confused != 1) {
			$obj->orientation_confused = 0;
		} else {
			$obj->orientation_confused = 1;
		}
						
		if (input()->orientation_disoriented != 1) {
			$obj->orientation_disoriented = 0;
		} else {
			$obj->orientation_disoriented = 1;
		}
						
		if (input()->orientation_forgetful != 1) {
			$obj->orientation_forgetful = 0;
		} else {
			$obj->orientation_forgetful = 1;
		}
						
		if (input()->orientation_fall_hx != 1) {
			$obj->orientation_fall_hx = 0;
		} else {
			$obj->orientation_fall_hx = 1;
		}
		
						
		$obj->orientation_fall_hx_detail = input()->orientation_fall_hx_detail;
						
		$obj->diet_type = input()->diet_type;				
		if (input()->diet_swallowing_difficulty != 1) {
			$obj->diet_swallowing_difficulty = 0;
		} else {
			$obj->diet_swallowing_difficulty = 1;
		}
						
		if (input()->diet_feeding_tube != 1) {
			$obj->diet_feeding_tube = 0;
		} else {
			$obj->diet_feeding_tube = 1;
		}
		
						
		$obj->diet_appetite = input()->diet_appetite;				
		if (input()->diet_feeds_self != 1) {
			$obj->diet_feeds_self = 0;
		} else {
			$obj->diet_feeds_self = 1;
		}
						
		if (input()->diet_must_be_fed != 1) {
			$obj->diet_must_be_fed = 0;
		} else {
			$obj->diet_must_be_fed = 1;
		}
						
		if (input()->bowel_continent != 1) {
			$obj->bowel_continent = 0;
		} else {
			$obj->bowel_continent = 1;
		}
						
		if (input()->bowel_incontinent != 1) {
			$obj->bowel_incontinent = 0;
		} else {
			$obj->bowel_incontinent = 1;
		}
						
		if (input()->bowel_colostomy != 1) {
			$obj->bowel_colostomy = 0;
		} else {
			$obj->bowel_colostomy = 1;
		}
		
						
		$obj->bowel_last_bm = input()->bowel_last_bm;				
		if (input()->bladder_continent != 1) {
			$obj->bladder_continent = 0;
		} else {
			$obj->bladder_continent = 1;
		}
						
		if (input()->bladder_incontinent != 1) {
			$obj->bladder_incontinent = 0;
		} else {
			$obj->bladder_incontinent = 1;
		}
						
		if (input()->bladder_catheter != 1) {
			$obj->bladder_catheter = 0;
		} else {
			$obj->bladder_catheter = 1;
		}
						
		if (input()->bathing_total != 1) {
			$obj->bathing_total = 0;
		} else {
			$obj->bathing_total = 1;
		}
						
		if (input()->bathing_moderate_assist != 1) {
			$obj->bathing_moderate_assist = 0;
		} else {
			$obj->bathing_moderate_assist = 1;
		}
						
		if (input()->bathing_minimal_assist != 1) {
			$obj->bathing_minimal_assist = 0;
		} else {
			$obj->bathing_minimal_assist = 1;
		}
						
		if (input()->dressing_total != 1) {
			$obj->dressing_total = 0;
		} else {
			$obj->dressing_total = 1;
		}
						
		if (input()->dressing_moderate_assist != 1) {
			$obj->dressing_moderate_assist = 0;
		} else {
			$obj->dressing_moderate_assist = 1;
		}
						
		if (input()->dressing_minimal_assist != 1) {
			$obj->dressing_minimal_assist = 0;
		} else {
			$obj->dressing_minimal_assist = 1;
		}
						
		if (input()->vision_wnl != 1) {
			$obj->vision_wnl = 0;
		} else {
			$obj->vision_wnl = 1;
		}
						
		if (input()->vision_blind != 1) {
			$obj->vision_blind = 0;
		} else {
			$obj->vision_blind = 1;
		}
						
		if (input()->vision_glasses != 1) {
			$obj->vision_glasses = 0;
		} else {
			$obj->vision_glasses = 1;
		}
						
		if (input()->hearing_wnl != 1) {
			$obj->hearing_wnl = 0;
		} else {
			$obj->hearing_wnl = 1;
		}
						
		if (input()->hearing_deaf != 1) {
			$obj->hearing_deaf = 0;
		} else {
			$obj->hearing_deaf = 1;
		}
						
		if (input()->hearing_hearingaids != 1) {
			$obj->hearing_hearingaids = 0;
		} else {
			$obj->hearing_hearingaids = 1;
		}
						
		if (input()->services_pt != 1) {
			$obj->services_pt = 0;
		} else {
			$obj->services_pt = 1;
		}
						
		if (input()->services_ot != 1) {
			$obj->services_ot = 0;
		} else {
			$obj->services_ot = 1;
		}
						
		if (input()->services_st != 1) {
			$obj->services_st = 0;
		} else {
			$obj->services_st = 1;
		}
						
		if (input()->services_nivt != 1) {
			$obj->services_nivt = 0;
		} else {
			$obj->services_nivt = 1;
		}
		
						
		$obj->ssinfection_yesno = input()->ssinfection_yesno;				
		if (input()->ssinfection_cough != 1) {
			$obj->ssinfection_cough = 0;
		} else {
			$obj->ssinfection_cough = 1;
		}
						
		if (input()->ssinfection_temp != 1) {
			$obj->ssinfection_temp = 0;
		} else {
			$obj->ssinfection_temp = 1;
		}
		
						
		$obj->ssinfection_temp_detail = input()->ssinfection_temp_detail;				
		if (input()->ssinfection_mrsa != 1) {
			$obj->ssinfection_mrsa = 0;
		} else {
			$obj->ssinfection_mrsa = 1;
		}
						
		if (input()->ssinfection_vre != 1) {
			$obj->ssinfection_vre = 0;
		} else {
			$obj->ssinfection_vre = 1;
		}
						
		if (input()->ssinfection_cdiff != 1) {
			$obj->ssinfection_cdiff = 0;
		} else {
			$obj->ssinfection_cdiff = 1;
		}
						
		if (input()->equipment_cane != 1) {
			$obj->equipment_cane = 0;
		} else {
			$obj->equipment_cane = 1;
		}
						
		if (input()->equipment_walker != 1) {
			$obj->equipment_walker = 0;
		} else {
			$obj->equipment_walker = 1;
		}
						
		if (input()->equipment_other != 1) {
			$obj->equipment_other = 0;
		} else {
			$obj->equipment_other = 1;
		}
		
						
		$obj->equipment_other_detail = input()->equipment_other_detail;				
		if (input()->wheelchair_standard != 1) {
			$obj->wheelchair_standard = 0;
		} else {
			$obj->wheelchair_standard = 1;
		}
						
		if (input()->wheelchair_bariatric != 1) {
			$obj->wheelchair_bariatric = 0;
		} else {
			$obj->wheelchair_bariatric = 1;
		}
						
		if (input()->wheelchair_reclining != 1) {
			$obj->wheelchair_reclining = 0;
		} else {
			$obj->wheelchair_reclining = 1;
		}
		
						
		$obj->vital_temp = input()->vital_temp;
						
		$obj->vital_hr = input()->vital_hr;
						
		$obj->vital_bp = input()->vital_bp;
						
		$obj->vital_lungs = input()->vital_lungs;
						
		$obj->vital_o2sat = input()->vital_o2sat;				
		if (input()->transfers_independent != 1) {
			$obj->transfers_independent = 0;
		} else {
			$obj->transfers_independent = 1;
		}
						
		if (input()->transfers_assisted1 != 1) {
			$obj->transfers_assisted1 = 0;
		} else {
			$obj->transfers_assisted1 = 1;
		}
						
		if (input()->transfers_assisted2 != 1) {
			$obj->transfers_assisted2 = 0;
		} else {
			$obj->transfers_assisted2 = 1;
		}
						
		if (input()->transfers_slideboard != 1) {
			$obj->transfers_slideboard = 0;
		} else {
			$obj->transfers_slideboard = 1;
		}
						
		if (input()->transfers_hoyer != 1) {
			$obj->transfers_hoyer = 0;
		} else {
			$obj->transfers_hoyer = 1;
		}
						
		if (input()->weightbearing_wbat != 1) {
			$obj->weightbearing_wbat = 0;
		} else {
			$obj->weightbearing_wbat = 1;
		}
						
		if (input()->weightbearing_30lbwb != 1) {
			$obj->weightbearing_30lbwb = 0;
		} else {
			$obj->weightbearing_30lbwb = 1;
		}
						
		if (input()->weightbearing_ttwb != 1) {
			$obj->weightbearing_ttwb = 0;
		} else {
			$obj->weightbearing_ttwb = 1;
		}
						
		if (input()->weightbearing_nwb != 1) {
			$obj->weightbearing_nwb = 0;
		} else {
			$obj->weightbearing_nwb = 1;
		}
						
		if (input()->weightbearing_cpm != 1) {
			$obj->weightbearing_cpm = 0;
		} else {
			$obj->weightbearing_cpm = 1;
		}
						
		if (input()->weightbearing_teds != 1) {
			$obj->weightbearing_teds = 0;
		} else {
			$obj->weightbearing_teds = 1;
		}
						
		if (input()->weightbearing_pwb != 1) {
			$obj->weightbearing_pwb = 0;
		} else {
			$obj->weightbearing_pwb = 1;
		}
		
						
		$obj->weightbearing_pwb_detail = input()->weightbearing_pwb_detail;				
		if (input()->weightbearing_other != 1) {
			$obj->weightbearing_other = 0;
		} else {
			$obj->weightbearing_other = 1;
		}
		
						
		$obj->weightbearing_other_detail = input()->weightbearing_other_detail;
						
		$obj->ulcers_wounds_location = input()->ulcers_wounds_location;
						
		$obj->ulcers_wounds_stage = input()->ulcers_wounds_stage;
						
		$obj->ulcers_wounds_size = input()->ulcers_wounds_size;
						
		$obj->ulcers_wounds_treatment = input()->ulcers_wounds_treatment;
						
		$obj->accuchecks = input()->accuchecks;
						
		$obj->inr = input()->inr;
						
		$obj->allergy = input()->allergy;
						
		$obj->o2_litersmin = input()->o2_litersmin;
						
		$obj->o2_mask = input()->o2_mask;
						
		$obj->o2_nc = input()->o2_nc;
						
		$obj->iv = input()->iv;
						
		$obj->pharmacokinetics = input()->pharmacokinetics;
						
		$obj->heplock = input()->heplock;
						
		$obj->peripheral = input()->peripheral;
						
		$obj->groshong = input()->groshong;
						
		$obj->portacath = input()->portacath;
						
		$obj->picc_line = input()->picc_line;
						
		$obj->hickman = input()->hickman;
						
		$obj->additional_notes = input()->additional_notes;	
		
		try {
			$obj->save();
		} catch (ORMException $e) {
			feedback()->error("An error occurred while attempting to save your changes.");
		}
		
		$this->redirect(SITE_URL . "/?page=patient&action=nursing&patient={$patient->pubid}");
		
	}
	
	public function printInquiry() {
		// You must be a coordinator to do this.
		//if (auth()->getRecord()->isAdmissionsCoordinator() == 0) {
		//	feedback()->error("Permission denied.");
		//	$this->redirect(auth()->getRecord()->homeURL());
		//}
		if (input()->schedule != '') {
			$schedule = new CMS_Schedule(input()->schedule);
			smarty()->assignByRef("schedule", $schedule);
			if ($schedule->valid()) {
				$patient_admit = $schedule->patient_admit;
			}	
		} elseif (input()->id != '') {
			$patient_admit = input()->id;
		}
		
		if ($patient_admit != '') {
			$patient = new CMS_Patient_Admit($patient_admit) ;
			if ($patient->valid() == false) {
				feedback()->error("Invalid patient/admit record requested.");
				$this->redirect(SITE_URL . "/?page=coord");
			}
			smarty()->assignByRef("patient", $patient);
			smarty()->assign("mode", input()->mode);
		} else {
			feedback()->error("Invalid patient/admit record requested.");
			$this->redirect(SITE_URL . "/?page=coord");
		}
		
	}
	
	public function sendScheduleNotification() {
		$schedule = new CMS_Schedule(input()->schedule);
		if ($schedule->valid()) {
			CMS_Notify_Event::trigger("schedule_general", $schedule);
			$schedule->notify_sent = 1;
			$schedule->save();
			feedback()->conf("Admit notification has been sent.");
		}
		$this->redirect();
	}

	public function visit() {
		$patient = new CMS_Patient_Admit(input()->patient);
		if ($patient->valid()) {
			$schedule = new CMS_Schedule(input()->schedule);			
			smarty()->assignByRef("patient", $patient);
			smarty()->assignByRef("schedule", $schedule);
		} else {
			feedback()->error("Invalid patient record.");
			$this->redirect(auth()->getRecord()->homeURL());
		}
	}
	
	public function submitVisit() {
		// validate fields
		if (input()->schedule != '') {
			$schedule = new CMS_Schedule(input()->schedule);	
			
			if ($schedule->valid()) {
				$patient = new CMS_Patient_Admit(input()->id);
				if (input()->post("physician") != '') {
					$physician = input()->post("physician");
				} else {
					feedback()->error("You must select the Physician that saw the patient.");
					$this->redirect();
				}
				if (input()->post("datetime_first_seen") != '' ) {
					$datetimeFirstSeen = date('Y-m-d H:i:s', strtotime(input()->post("datetime_first_seen")));
					
				} else {
					feedback()->error("You must enter the date and time the patient was first visited.");
					$this->redirect();
				}
				$schedule->datetime_first_seen = $datetimeFirstSeen;
				$schedule->first_seen_by_id = $physician;
				
				try {
					$schedule->save();
					feedback()->conf("The first visit for %s has been saved.", "<b>{$patient->first_name} {$patient->last_name}</b>");
				} catch (ORMException $e) {
					feedback()->error("An error was encountered while trying to save this patient record.");
				}
						
				if (feedback()->wasError()) {
					$this->redirect();
				} else {
					$this->redirect(SITE_URL . "/?page=coord");			
				}
			}
			
		} else {
			feedback()->error('Invalid patient schedule selected.');
			$this->redirect();
		}
		
	}
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  TRANSFER REQUEST PAGES
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function transferRequest() {
		if (input()->schedule != '') {
			$schedule = new CMS_Schedule(input()->schedule);
			smarty()->assignByRef("schedule", $schedule);
			if ($schedule->valid()) {
				$patient_admit = $schedule->patient_admit;
			}	
		} 
		
		if ($patient_admit != '') {
			$patient = new CMS_Patient_Admit($patient_admit) ;
			if ($patient->valid() == false) {
				feedback()->error("Invalid patient/admit record requested.");
				$this->redirect(SITE_URL . "/?page=coord");
			}
			// Get list of physician names
			$physicians = new CMS_Physician();
			$pNames = $physicians->getPhysicians();
			
			smarty()->assign("pNames", $pNames);
			smarty()->assignByRef("schedule", $schedule);
			smarty()->assignByRef("patient", $patient);
			smarty()->assign("mode", input()->mode);
		} else {
			feedback()->error("Invalid patient/admit record requested.");
			$this->redirect(SITE_URL . "/?page=coord");
		}
		
		$facilities = auth()->getRecord()->getFacilities();
		
		smarty()->assign('schedule', $schedule);
		smarty()->assign('patient', $patient);
		smarty()->assign('facilities', $facilities);
				
	}
	
	
	public function submitTransferRequest() {
		if (input()->schedule != '') {
			$schedule = new CMS_Schedule(input()->schedule);
			$transferFrom = new CMS_Facility(input()->transfer_from_facility);
			if ($schedule->valid()) {
				$patient_admit = $schedule->patient_admit;
			}	
		} 
		
		
		$schedule->transfer_from_facility = $transferFrom->id;
				
		if (input()->transfer_to_facility != '') {
			$transferTo = new CMS_Facility(input()->transfer_to_facility);
			$schedule->transfer_to_facility = $transferTo->id;
		} else {
			feedback()->error('You must select the facility to which the patient would like to transfer.');
		}
		
		if (input()->transfer_comment != '') {
			$schedule->transfer_comment = input()->transfer_comment;
		}
		
		$schedule->transfer_request = true;
				
		try {
			$schedule->save();
			feedback()->conf('The requested transfer has been saved.');
		} catch (ORMException $e) {
			feedback()->error("An error was encountered while trying to save the transfer request.");
		}
					
		if (feedback()->wasError()) {
			$this->redirect();
		} else {
			$this->redirect(SITE_URL . "/?page=coord");			
		}
	}
	
	
	public function deletePatient() {
		
		if (input()->data["patient_pubid"] != "") {
			$patient_pubid = input()->data["patient_pubid"];
		}
		if (input()->data["schedule_pubid"] != "") {
			$schedule_pubid = input()->data["schedule_pubid"];
		}

		
		$patient_admit = new CMS_Patient_Admit($patient_pubid);
		$schedule = new CMS_Schedule($schedule_pubid);
		// need to get the schedule using patient_admit.id
		//$schedule = $patient_admit->fetchSchedule($patient_admit->id);
		
		
		if ($patient_admit->delete($patient_admit)) {
			if ($schedule->delete($schedule)) {
				return true;
			}
		}

		return false;
	}
	
	
	public function upload() {
		$facilities = auth()->getRecord()->getFacilities();
		smarty()->assign('facilities', $facilities);
	}
	
	public function uploadData() {
		// Get CSV info and assign to the correct variables prior to saving
		if ($_FILES["patient_data"]["tmp_name"] != '') {
			$filename = $_FILES["patient_data"]["tmp_name"];
		} else {
			$filename = false;
		}
				
		$facility = new CMS_Facility(input()->facility);
		
		// Get data from the uploaded csv file		
		$data = $this->csv_to_array($filename);
						
		// Instantiate new objects and save data
		foreach ($data as $d) {
			if (!empty($d)) {
			
				// Need to find the id for the room number entered
				$obj = new CMS_Room();		
				$room = $obj->fetchRoom($d['room'], $facility->id);
				
				// Check if room is available
				$availability = $obj->checkRoomStatus($room[0]->id, $d['datetime_admit']);
				
				if ($availability) {
					// the room is currently available
					
					$patient = new CMS_Patient_Admit();
					
					$patient->last_name = $d['last_name'];
					$patient->first_name = $d['first_name'];
					$patient->middle_name = $d['middle_name'];
					$patient->datetime_created = datetime();
					$patient->site_user_created = auth()->getRecord()->id;
					$patient->person_id = generate_pubid();
					$patient->address = $d['address'];
					$patient->city = $d['city'];
					$patient->state = $d['state'];
					$patient->zip = $d['zip'];
					$patient->phone = $d['phone'];
					$patient->birthday = $d['birthday'];
					$patient->sex = $d['sex'];
					$patient->ssn = $d['ssn'];
					$patient->paymethod = $d['paymethod'];
					$patient->medicare_number = $d['medicare_number'];
					
					
					try {
						$patient->save();
						feedback()->conf("The patient admit for {$patient->first_name} {$patient->last_name} has been saved.");
					} catch (ORMException $e) {
						feedback()->error("There was an error while attempting to save this new patient admit request.");
					}
					
					
					// Set items for the new patient schedule
					$schedule = new CMS_Schedule();
					$schedule->patient_admit = $patient->id;
					$schedule->facility = $facility->id;
					$schedule->status = "Approved";
					$schedule->room = $room[0]->id;
					$schedule->datetime_admit = date('Y-m-d 11:00:00', strtotime($d['datetime_admit']));
					$schedule->long_term = $d['long_term'];	
					
					try {
						$schedule->save();
						feedback()->conf("The patient schedule for {$patient->first_name} {$patient->last_name} has been saved");
					} catch (ORMException $e) {
						feedback()->error("There was an error while attempting to add this new patient to the schedule");
						CMS_Patient_Admit::delete($patient);
					}
	
						
				} else {
					// the room is currently occupied
					feedback()->error("Room " . $room[0]->number . " is currently occupied.");
				}
					
			}
					
		}
		
		$this->redirect();
		
	}
	
	public function csv_to_array($filename = '', $delimiter = ',') {
		
		if(file_exists($filename) || is_readable($filename)) {
			$f = fopen($filename, "r");
			
			$i = 0;
			$header = array();
			while ($row = fgetcsv($f)) {
				foreach ($row as $key => $value) {
					if ($i == 0) {
						$header[] = $value;
					} else {
						foreach ($header as $k => $v) {
							if ($k == $key) {
								$data[$i][$v] = $value;
								
							}								
						}						
					}					
				}
				$i++;
			}
			fclose($f);	
			return $data;
		} else {
			return false;
		}
        
	}
	
}
