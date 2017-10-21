<?php

class PageControllerCoord extends PageController {

	public function init() {
		Authentication::disallow();	
		
	}
	
	public function index() {
		$_facilities = auth()->getRecord()->getFacilities();			// my facilities

		// You must be a coordinator to do this.
		if (auth()->getRecord()->isAdmissionsCoordinator() == 0) {
			$this->redirect(auth()->getRecord()->homeURL());
		}
		
		$user = auth()->getRecord();
		
		smarty()->assign('user', $user);
		
		
		// Optionally use a different week
		if (input()->weekSeed != '') {
			$weekSeed = input()->weekSeed;
			if (Validate::is_american_date($weekSeed)->success() || Validate::is_standard_date($weekSeed)->success()) {
				//$week = Calendar::getDateSequence($weekSeed, 7);
				$week = Calendar::getWeek($weekSeed);
			}
		} else {
			// Default to "this week"
			$weekSeed = date("Y-m-d");
			//$week = Calendar::getDateSequence($weekSeed, 7);
			$week = Calendar::getWeek($weekSeed);
		}
				
		// for the top list -- everyone under consideration, regardless of date
		$_dateStart = false;		// any start
		$_dateEnd = false;			// any end
		$_status = 'Under Consideration';			// any status
		$_orderby = "`facility`, `datetime_admit` ASC";	// order by datetime, sooner at the top.
		$pendingAdmits = CMS_Schedule::fetchAdmits($_dateStart, $_dateEnd, $_status, $_facilities, $_orderby);
 		// smarty()->assignByRef("pendingAdmits", $pendingAdmits);

		// for the calendar grid -- all admits on the docket this week, regardless of status
		$_dateStart = $week[0];		// Sunday of this week
		$_dateEnd = $week[6];		// Saturday	of this week
		$_status = 'Approved';			// any status
		$_orderby = "facility, datetime_admit ASC";	// order by datetime, sooner at the top.
		$completedAdmits = CMS_Schedule::fetchAdmits($_dateStart, $_dateEnd, $_status, $_facilities, $_orderby);
		if ($completedAdmits == false) {
			$completedAdmits = array();
		}
		
		
		// split them up by facility
		$completedAdmitsByFacilityAndDate = array();
		$pendingAdmitsByFacilityAndDate = array();
		
		foreach ($completedAdmits as $a) {
			if (!isset($completedAdmitsByFacilityAndDate[$a->facility])) {
				$completedAdmitsByFacilityAndDate[$a->facility] = array();
			}
			$date = date("Y-m-d", strtotime($a->datetime_admit));
			if (!isset($completedAdmitsByFacilityAndDate[$a->facility][$date])) {
				$completedAdmitsByFacilityAndDate[$a->facility][$date] = array();
			}
			$completedAdmitsByFacilityAndDate[$a->facility][$date][] = $a; 
		}
		smarty()->assignByRef("completedAdmitsByFacilityAndDate", $completedAdmitsByFacilityAndDate);

		foreach ($pendingAdmits as $a) {
			if (!isset($pendingAdmitsByFacilityAndDate[$a->facility])) {
				$pendingAdmitsByFacilityAndDate[$a->facility] = array();
			}
			$date = date("Y-m-d", strtotime($a->datetime_admit));
			if (!isset($pendingAdmitsByFacilityAndDate[$a->facility][$date])) {
				$pendingAdmitsByFacilityAndDate[$a->facility][$date] = array();
			}
			$pendingAdmitsByFacilityAndDate[$a->facility][$date][] = $a; 
		}
		smarty()->assignByRef("pendingAdmitsByFacilityAndDate", $pendingAdmitsByFacilityAndDate);
				
		// for the calendar grid -- all discharges on the docket this week, regardless of status
		$_dateStart = $week[0];		// Sunday of this week
		$_dateEnd = $week[6];		// Saturday	of this week
		$_facilities = auth()->getRecord()->getFacilities();			// my facilities
		$_orderby = "facility, datetime_discharge ASC";	// order by datetime, sooner at the top.
		$allDischarges = CMS_Schedule::fetchDischarges($_dateStart, $_dateEnd, $_facilities, $_orderby);
		if ($allDischarges == false) {
			$allDischarges = array();
		}
		
		// split them up by facility
		$dischargesByFacilityAndDate = array();
		foreach ($allDischarges as $d) {
			if (!isset($dischargesByFacilityAndDate[$d->facility])) {
				$dischargesByFacilityAndDate[$d->facility] = array();
			}
			$date = date("Y-m-d", strtotime($d->datetime_discharge));
			if (!isset($dischargesByFacilityAndDate[$d->facility][$date])) {
				$dischargesByFacilityAndDate[$d->facility][$date] = array();
			}
			$dischargesByFacilityAndDate[$d->facility][$date][] = $d;
			
			// add this discharge to the next few days' visible discharge schedule if it's a bed hold
			if ($d->discharge_to == 'Discharge to Hospital (Bed Hold)') {
				// init tracking var to the discharge date and start adding days from there.
				$bhd = date("Y-m-d H:i:s", strtotime($d->datetime_discharge));

				while(1) {
					// if we made it this far, add this record to the calendar day
					// represented by $bhd
					if (! isset($dischargesByFacilityAndDate[$d->facility][date("Y-m-d", strtotime($bhd))]) ) {
						$dischargesByFacilityAndDate[$d->facility][date("Y-m-d", strtotime($bhd))] = array();
					}
					if (! in_array($d, $dischargesByFacilityAndDate[$d->facility][date("Y-m-d", strtotime($bhd))])) {
						$dischargesByFacilityAndDate[$d->facility][date("Y-m-d", strtotime($bhd))][] = $d;
					}

					// make sure that we haven't crossed over into a calendar day too far...
					$check1 = date("Y-m-d", strtotime($d->datetime_discharge_bedhold_end)) . " 00:00:00";
					$check2 = date("Y-m-d", strtotime("+1 day", strtotime($bhd))) . " 00:00:00";
					if (strtotime($check2) > strtotime($check1)) {
						break;
					}
					
					// add a day to the tracking var and loop...
					$bhd = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($bhd)));
					
				}
			}
			
			
			
		}
		smarty()->assignByRef("dischargesByFacilityAndDate", $dischargesByFacilityAndDate);
		
		// Get patients who have been sent to the hospital
		$sentToHospital = CMS_Schedule::fetchSent($_dateStart, $_dateEnd, $_facilities);
		if ($sentToHospital == false) {
			$sentToHospital = array();
		}

		smarty()->assignByRef("sentToHospital", $sentToHospital);

		// split them up by facility
		$sentToHospitalByFacilityAndDate = array();
		foreach ($sentToHospital as $s) {
			if (!isset($sentToHospitalByFacilityAndDate[$s->facility])) {
				$sentToHospitalByFacilityAndDate[$s->facility] = array();
			}
			$date = date("Y-m-d", strtotime($s->datetime_sent));
			if (!isset($sentToHospitalByFacilityAndDate[$s->facility][$date])) {
				$sentToHospitalByFacilityAndDate[$s->facility][$date] = array();
			}
			$sentToHospitalByFacilityAndDate[$s->facility][$date][] = $s; 
		}
		smarty()->assignByRef("sentToHospitalByFacilityAndDate", $sentToHospitalByFacilityAndDate);
		

		// this week in the calendar
		smarty()->assignByRef("week", $week);


		// next, previous weeks (use 6 days so that the beginning or end day of this week remains visible)
		smarty()->assign(array(
			"nextWeekSeed" => date("Y-m-d", strtotime("+7 days", strtotime($weekSeed))),
			"prevWeekSeed" => date("Y-m-d", strtotime("-7 days", strtotime($weekSeed))),
		));


		// cycle through the facilities and get a count of empty rooms for each
		$emptyRoomCountByFacility = array();
		foreach ($_facilities as $f) {
			$emptyRoomCountByFacility[$f->id] = count(CMS_Room::fetchEmptyByFacility($f->id, datetime()));
		}
		
		smarty()->assignByRef("emptyRoomCountByFacility", $emptyRoomCountByFacility);		
	}
	
	public function admit() {
	}

	public function readmit() {
		$facility = new CMS_Facility(input()->facility);
		$schedule = new CMS_Schedule(input()->schedule);
		$atHospitalRecord = $schedule->atHospitalRecord();

		smarty()->assign("facility", $facility);
		smarty()->assign("schedule", $schedule);
		smarty()->assign("atHospitalRecord", $atHospitalRecord);
	}



	public function room() {
		$schedule = new CMS_Schedule(input()->schedule);
		if ($schedule->valid()) {
			smarty()->assignByRef("schedule", $schedule);

			if (input()->facility != '') {
				$facility = new CMS_Facility(input()->facility);
				if ($facility->valid() == false) {
					$facility = $schedule->related('facility');
				}
			} else {
				$facility = $schedule->related('facility');
			}

			if (input()->goToApprove == 1) {
				$datetime = $schedule->datetime_admit;
			} elseif (input()->datetime == '') {
				$datetime = date('m/d/y h:i a', strtotime('now'));
			} else {
				$datetime = input()->datetime;
			}

			$datetime = datetime(strtotime($datetime));
			


			/*
			 * Note: Get rooms which are or will be empty on the admission date & time
			 *
			 */

			$empty = CMS_Room::fetchEmptyByFacility($facility->id, $datetime);
			$discharges = CMS_Room::fetchScheduledDischargesByFacility($facility->id, $datetime);
			$rooms = CMS_Room::mergeFetchedRooms($empty, $discharges);
					
			smarty()->assignByRef("rooms", $rooms);
			smarty()->assignByRef("facility", $facility);
			smarty()->assign("goToApprove", input()->goToApprove);
			smarty()->assign("datetime", $datetime);
		} else {
			feedback()->error("Invalid scheduling selected.");
			$this->redirect(SITE_URL . "/?page=coord");
		}
	}



	public function setScheduleFacilityAndRoom() {
		$msg = array();
		$facility = input()->facility;
		if (input()->goToApprove == 1) {
			$approved = true;
		} else {
			$approved = false;
		}
		
		$result = CMS_Schedule::assignRoom(input()->schedule, input()->facility, input()->room, input()->datetime_admit, $approved);

		//$result = CMS_Schedule::setFacilityAndRoom(input()->schedule, input()->facility, input()->room, input()->previous_room, input()->datetime_admit, $newAdmit);
		if ($result[0] == false) {
			foreach ($result[1] as $m) {
				feedback()->error($m);
			}
			$this->redirect();
		} else {
			if (input()->goToApprove == 1) {
				$this->redirect(SITE_URL . '/?page=coord&action=approveSchedule&id=' . input()->schedule);
			} else {
				feedback()->conf("Room assignment has been saved.");
				$this->redirect(SITE_URL . '/?page=facility&action=census&facility=' . $facility);
			}

		}
	}

	public function setScheduleFacility() {
		$schedule = new CMS_Schedule(input()->schedule);
		$facility = new CMS_Facility(input()->facility);
		if ($schedule->valid() && $facility->valid()) {
			$schedule->facility = $facility->id;
			$schedule->save();
			json_return(array("status" => true));
		}
		json_return(array("status" => false));
	}

	public function cancelSchedule() {
		
		$schedule = new CMS_Schedule(input()->id);
		if ($schedule->valid()) {
			$schedule->status = 'Cancelled';
			$schedule->save();
			CMS_Notify_Event::trigger("schedule_cancelled", $schedule);
		}
		$this->redirect(SITE_URL . "/?page=coord");

	}
	public function approveSchedule() {
		
		$schedule = new CMS_Schedule(input()->id);
				
		if ($schedule->valid()) {
			if ($schedule->getPatient()->readyForApproval($msg, $schedule->facility)) {
				$schedule->status = 'Approved';
				$schedule->save();
				feedback()->conf("{$schedule->getPatient()->fullName()} has been approved");// and locked into the {$schedule->related('facility')->name()} schedule."); // the facililty->name() is not working.
			} else {
				pr ($msg);
				die();
				feedback()->error("Could not approve the schedule: {$msg}");
			}
		}
		$this->redirect(SITE_URL . "/?page=coord");

	}

	public function setScheduleDatetimeAdmit() {
		$schedule = new CMS_Schedule(input()->id);
		if ($schedule->valid()) {
			if (input()->datetime != '') {							
				$schedule->datetime_admit = datetime(strtotime(input()->datetime));
				$schedule->save();
			}
		}
		if (input()->path != '') {
			$this->redirect(urldecode(input()->path));
		} else {
			$this->redirect(SITE_URL . "/?page=coord");
		}
	}

	public function setSchedulePending() {
		$schedule = new CMS_Schedule(input()->schedule);
		if ($schedule->valid()) {
			if ($schedule->status == 'Approved') {
				$schedule->status = 'Under Consideration';
				$schedule->save();
			}
		}
		if (input()->path != '') {
			$this->redirect(urldecode(input()->path));
		} else {
			$this->redirect(SITE_URL . "/?page=coord");
		}

	}

	public function searchCodes() {
		$term = input()->term;
		if ($term != '') {
			$tokens = explode(" ", $term);
			$params = array();
			$sql = "select * from icd9_codes where ";
			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$sql .= " short_desc like :term{$idx} OR code like :term{$idx} AND";
				$params[":term{$idx}"] = "%{$token}%";
			}
			$sql = rtrim($sql, " AND");
			$results = db()->getRowsCustom($sql, $params);
		} else {
			$results = array();
		}

		json_return($results);

	}


	public function trackHospitalVisits() {
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		}
		$facilities = auth()->getRecord()->getFacilities();
		
		if (input()->orderby == '') {
			$orderbySQL = 'datetime_updated DESC';
		} else {
			switch (input()->orderby) {
				case "datetime_updated_DESC":
					$orderbySQL = "datetime_updated DESC";
					break;
				case "datetime_updated_ASC":
					$orderbySQL = "datetime_updated ASC";
					break;
				case "datetime_created_DESC":
					$orderbySQL = "datetime_created DESC";
					break;
				case "datetime_created_ASC":
					$orderbySQL = "datetime_created ASC";
					break;
				case "datetime_sent_DESC":
					$orderbySQL = "datetime_sent DESC";
					break;
				case "datetime_sent_ASC":
					$orderbySQL = "datetime_sent ASC";
					break;
				case "hospital_name":
					$orderbySQL = "hospital_name";
					break;
				case "facility":
					$orderbySQL = "facility_name";
					break;
				case "discharge_nurse":
					$orderbySQL = "discharge_nurse_name";
					break;
				
			}
		}

		// $stopTrackingOptions = array_filter(db()->enumOptions("schedule_hospital", "stop_tracking_reason"));

		if (isset ($facility)) {
			$atHospitalRecords = CMS_Schedule::getAtHospitalRecords($facility, $orderbySQL);
		}
		
		smarty()->assignByRef("facility", $facility);
		smarty()->assign("facilities", $facilities);
		smarty()->assignByRef("atHospitalRecords", $atHospitalRecords);
		smarty()->assign("orderby", input()->orderby);
		smarty()->assign("stopTrackingOptions", $stopTrackingOptions);
		
	}
	
	
	public function stopTrackingHospitalVisit() {
		if (input()->schedule_hospital != "") {
			$schedule_hospital_pubid = input()->schedule_hospital;
		}
		
		$hospital_visit = new CMS_Schedule_Hospital($schedule_hospital_pubid);
		$hospital_visit->is_complete = 1;
			
		if ($hospital_visit->save()) {
			json_return(array('status' => true));
		}
		
		json_return(array('status' => false));

	}
	
	
	public function pending_admissions() {
		if (input()->facility != '') {
			$facility = new CMS_Facility(input()->facility);
		} else {
		$defaultFacility = auth()->getRecord()->getDefaultFacility();
		$facility = new CMS_Facility($defaultFacility->id);
		}
		smarty()->assign('facility', $facility);
		
		$_facilities = auth()->getRecord()->getFacilities();			// my facilities
				
		if ($facility != '') {
					// You must be a coordinator to do this.
			if (auth()->getRecord()->isAdmissionsCoordinator() == 0) {
				$this->redirect(auth()->getRecord()->homeURL());
			}
			
			// Optionally use a different week
			if (input()->weekSeed != '') {
				$weekSeed = input()->weekSeed;
				if (Validate::is_american_date($weekSeed)->success() || Validate::is_standard_date($weekSeed)->success()) {
					//$week = Calendar::getDateSequence($weekSeed, 7);
					$week = Calendar::getWeek($weekSeed);
				}
			} else {
				// Default to "this week"
				$weekSeed = date("Y-m-d");
				//$week = Calendar::getDateSequence($weekSeed, 7);
				$week = Calendar::getWeek($weekSeed);
			}
			
			// for the top list -- everyone under consideration, regardless of date
			if (input()->status != '') {
				$_status = input()->status;
				
				// set default dates if not under consideration
				if (input()->status != 'Under Consideration') {
					if (input()->date_start != '') {
						$_dateStart = input()->date_start;
					} else {
						$_dateStart = date('Y-m-d 00:00:01', strtotime("now - 30 days"));
					}
					if (input()->date_end != '') {
						$_dateEnd = input()->date_end;
					} else {
						$_dateEnd = date('Y-m-d 23:59:59', strtotime("today"));
					}
				
				// allow all dates by default for under consideration
				} elseif (input()->status == 'Under Consideration') {
					if (input()->date_start != '') {
						$_dateStart = input()->date_start;
					} else {
						$_dateStart = false; // any start
					}
					if (input()->date_end != '') {
						$_dateEnd = input()->date_end;
					} else {
						$_dateEnd = false; // any end
					}
					
				}
			} else {
				$_status = 'Under Consideration';
			}
												
			smarty()->assign('selectedStatus', $_status);
						
			// any status
			$_orderby = "`facility`, `datetime_admit` ASC";	// order by datetime, sooner at the top.
			$pendingAdmits = CMS_Schedule::fetchPendingAdmits($_dateStart, $_dateEnd, $_status, $facility, $_orderby);
			smarty()->assignByRef("pendingAdmits", $pendingAdmits);
			smarty()->assign("countAdmits", count($pendingAdmits));
			smarty()->assign("facilities", $_facilities);
	
		}

	}
	
	
	public function pending_transfers() {
		$user = auth()->getRecord();
		smarty()->assign('user', $user);
						
		$facilities = auth()->getRecord()->getFacilities();
		
		$findFacilities = array();
		foreach ($facilities as $f) {
			$findFacilities[] = $f->id;
		}
				
		$obj = CMS_Schedule::generate();
		foreach ($findFacilities as $ff) {
			$pendingTransfers[] = $obj->fetchTransfers($ff);
		}

		smarty()->assign('pendingTransfers', $pendingTransfers);
		
		// Get list of all pending patients
		
		
	
	}
	
	
	public function approve_transfer() {
		if (input()->schedule != '') {
			$schedule = new CMS_Schedule(input()->schedule);
			$patient = new CMS_Patient_Admit($schedule->patient_admit);
		} else {
			feedback()->error('Could not find the selected patient record.');
		}
		
		$facilities = auth()->getRecord()->getFacilities();
		
		smarty()->assign('schedule', $schedule);
		smarty()->assign('patient', $patient);
		smarty()->assign('facilities', $facilities);
	}
	
	
	public function submitTransferApproval() {
		if (input()->schedule != '') {
			$schedule = new CMS_Schedule(input()->schedule);
			$patient = new CMS_Patient_Admit(input()->id);
			$transferFrom = new CMS_Facility(input()->transfer_from);
			$transferTo = new CMS_Facility(input()->transfer_to);
		} else {
			feedback()->error('Could not find the selected patient record.');			
		}
		
		$schedule->transfer_from_facility = $facility->id;
		
		if (input()->datetime_discharge != '') {
			$schedule->datetime_discharge = date('Y-m-d G:i:s', strtotime(input()->datetime_discharge));
		} else {
			feedback()->error("You must select a date and time to discharge the patient");
		}
		
		$schedule->discharge_to = 'Transfer to another AHC Facility';
		$schedule->discharge_comment = input()->discharge_comment;
		$schedule->transfer_comment = '';
		$schedule->transfer_to_facility = $transferTo->id;
		
		
		$transfer_schedule = new CMS_Schedule();
		$transfer_schedule->status = "Under Consideration";
		
		if (input()->datetime_admit != '') {
			$transfer_schedule->datetime_admit = date('Y-m-d G:i:s', strtotime(input()->datetime_admit));
		} else {
			feedback()->error("You must select a date and time to admit the patient");
		}
		
		$transfer_schedule->patient_admit = $patient->id;
		$transfer_schedule->facility = $transferTo->id;
		$transfer_schedule->transfer_from_facility = $transferFrom->id;
		$transfer_schedule->transfer_comment = input()->transfer_comment;	
		
		try {
			$schedule->save();
			$transfer_schedule->save();
			feedback()->conf("The facility transfer was successfully saved for {$patient->first_name} {$patient->last_name}.");
		} catch (ORMException $e) {
			feedback()->error("An error was encountered while trying to save this patient record.");
		}
				
		if (feedback()->wasError()) {
			$this->redirect();
		} else {
			$this->redirect(SITE_URL . "/?page=coord&action=pending_transfers");			
		}

		
	}
	
	
	public function cancel_transfer() {
		if (input()->schedule == "") {
			feedback()->error("Could not find the patient.");
			$this->redirect(SITE_URL . "/?page=coord&action=pending_transfers");
		} else {
			$schedule = new CMS_Schedule(input()->schedule);
		}
		
		$schedule->transfer_request = 0;
		$schedule->transfer_to_facility = '';
		$schedule->transfer_from_facility = '';
		$schedule->transfer_comment = '';
		
		try {
			$schedule->save();
			feedback()->conf("The transfer facility request was cancelled.");
		} catch (ORMException $e) {
			feedback()->error("Could not cancel the transfer facility request.  Please try again.");
		}
		
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=coord&action=approve_transfer&schedule=$schedule->pubid");
		} else {
			$this->redirect(SITE_URL . "/?page=coord&action=pending_transfers");
		}
	}
	
	
	public function order_pending_admits() {
		
		$data = input()->data;
		
		foreach ($data as $d) {
			$schedule = new CMS_Schedule($d["pubid"]);
			$schedule->admit_order = $d["order"];
			$schedule->save();
		}
		
		return true;
		
				
		// Need to save the admission order for each schedule id
		
	}
		
	
/*
	public function discharged_patient_info() {
		$schedule = new CMS_Schedule(input()->schedule);
		
		smarty()->assign('schedule', $schedule);			
	}
*/

}




