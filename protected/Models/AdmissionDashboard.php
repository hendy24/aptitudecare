<?php

class AdmissionDashboard extends AppModel {

	protected $prefix = false;
	protected $table = 'schedule';
	protected $dbname = null;

	public function __construct() {
		$this->dbname = db()->dbname2;
	}



	public function syncDischarges($datetime_start, $datetime_end, $location_id, $areas) {

		$params = array(
			":datetime_start" => date('Y-m-d 00:00:01', strtotime($datetime_start)),
			":datetime_end" => date('Y-m-d 23:59:59', strtotime($datetime_end)),
		);

		$sql = "SELECT " . db()->dbname2 . ".patient_admit.*, " . db()->dbname2 . ".schedule.*, " . db()->dbname2 . ".schedule.pubid as schedule_pubid, " . db()->dbname2 . ".patient_admit.pubid as patient_pubid, " . db()->dbname2 . ".facility.name AS healthcare_facility_name FROM " . db()->dbname2 . ".`patient_admit` INNER JOIN " . db()->dbname2 . ".`schedule` ON " . db()->dbname2 . ".`schedule`.`patient_admit` = " . db()->dbname2 . ".`patient_admit`.`id` INNER JOIN " . db()->dbname2 . ".facility ON " . db()->dbname2 . ".facility.id = " . db()->dbname2 . ".schedule.facility WHERE " . db()->dbname2 . ".`schedule`.datetime_discharge >= :datetime_start AND " . db()->dbname2 . ".`schedule`.datetime_discharge <= :datetime_end AND " . db()->dbname2 . ".`schedule`.facility IN (";

		foreach ($areas as $key => $area) {
			$params[":area{$key}"] = $area->id;
			$sql .= ":area{$key}, ";
		}

		$sql = trim($sql, ", ");

		$sql .= ")";

		$result =  $this->fetchAll($sql, $params);

		//	Map data to correct columns for the new db and save the rows, they can then be fetched with a single query
		foreach ($result as $r) {
			//	If the items have already been saved to the db then get them.
			$hhScheduleObj = new HomeHealthSchedule;
			$hhSchedule = $hhScheduleObj->checkExisting($r->facility, $r->patient_pubid);
			$patient = $this->fetchById($r->patient_pubid, 'Patient');
			$schedule = $this->fetchById($r->schedule_pubid, 'Schedule');

			if (empty ($hhSchedule)) {
				$hhSchedule = new HomeHealthSchedule;
			}
			if (empty($patient)) {
				$patient = new Patient;
			}
			if (empty ($schedule)) {
				$schedule = new Schedule;
			}




			//	If the hhSchedule is empty then this is a new object, we are going to set the start of care
			//	date to be equal to the discharge date
			if (empty ($hhSchedule)) {
				$hhSchedule = new HomeHealthhhSchedule();
				$hhSchedule->start_of_care = $r->datetime_discharge;
			} else {
				//	it is possible that the start of care date has been modified in the home health application
				//	and the discharge date has been changed in the admission application.  If that has happened
				//	we need to reset the start of care date to match the new discharge date
				if (isset ($hhSchedule->referral_date) && $hhSchedule->referral_date !== $r->datetime_discharge) {
					$hhSchedule->start_of_care = $r->datetime_discharge;
				}
			}

			//	This will update the referral date if the discharge date changes
			$hhSchedule->referral_date = $r->datetime_discharge;



			if (empty ($patient)) {
				$patient = new Patient();
			}

			$hhSchedule->public_id = $r->schedule_pubid;

			// need to find the healthcare facility id by the location name
			$healthcare_facility = $this->loadTable("Location", $r->facility)->fetchHealthcareFacilityId();

			if (isset ($healthcare_facility->id)) {
				$hhSchedule->admit_from_id = $healthcare_facility->id;
			}

			$hhSchedule->referred_by_type = "ahc_facility";
			$hhSchedule->location_id = $r->facility;
			$hhSchedule->datetime_created = mysql_datetime();
			$hhSchedule->datetime_modified = mysql_datetime();
			$hhSchedule->inpatient_diagnosis = $r->other_diagnosis;
			$hhSchedule->specialist_id = $r->ortho_id;
			$hhSchedule->primary_insurance = $r->paymethod;
			$hhSchedule->primary_insurance_number = $r->medicare_number;

			if ($r->service_disposition == "AHC Home Health") {
				$hhSchedule->confirmed = true;
			} else {
				$hhSchedule->confirmed = false;
			}

			$patient->public_id = $r->patient_pubid;
			$patient->first_name = $r->first_name;
			$patient->last_name = $r->last_name;
			$patient->middle_name = $r->middle_name;


			// If the patient has an alternate discharge address, use that
			if ($r->discharge_address != '') {
				$patient->address = $r->discharge_address;
			} else {
				$patient->address = $r->address;
			}

			if ($r->discharge_city != '') {
				$patient->city = $r->discharge_city;
			} else {
				$patient->city = $r->city;
			}

			if ($r->discharge_state != '') {
				$patient->state = $r->discharge_state;
			} else {
				$patient->state = $r->state;
			}

			if ($r->discharge_zip != '') {
				$patient->zip = $r->discharge_zip;
			} else {
				$patient->zip = $r->zip;
			}

			if ($r->discharge_phone != '') {
				$patient->phone = $r->discharge_phone;
			} else {
				$patient->phone = $r->phone;
			}

			$patient->sex = $r->sex;
			$patient->date_of_birth = $r->birthday;
			$patient->ethnicity = $r->ethnicity;
			$patient->marital_status = $r->marital_status;
			$patient->religion = $r->religion;
			$patient->ssn = $r->ssn;
			$patient->emergency_contact = $r->emergency_contact_name1;
			$patient->emergency_phone = $r->emergency_contact_phone1;


			$schedule->public_id = $r->schedule_pubid;
			$schedule->location_id = $r->facility;
			$schedule->room_id = $r->room;
			$schedule->datetime_admit = $r->datetime_admit;
			$schedule->datetime_discharge = $r->datetime_discharge;
			$schedule->discharge_to = $r->discharge_to;
			$schedule->discharge_disposition = $r->discharge_disposition;
			if ($r->service_disposition == "Other Home Health" || $r->service_disposition == "AHC Home Health") {
				$schedule->service_disposition = "Home Health";
			} else {
				$schedule->service_disposition = $r->service_disposition;
			}
			$schedule->discharge_location_id = $r->discharge_location_id;
			$schedule->home_health_id = $r->home_health_id;
			$schedule->discharge_address = $r->discharge_address;
			$schedule->discharge_city = $r->discharge_city;
			$schedule->discharge_state = $r->discharge_state;
			$schedule->discharge_zip = $r->discharge_zip;
			$schedule->discharge_phone = $r->discharge_phone;
			$schedule->datetime_discharge_bedhold_end = $r->datetime_discharge_bedhold_end;
			$schedule->discharge_comment = $r->discharge_comment;
			$schedule->readmit_type = $r->readmit_type;
			$schedule->elective = $r->elective;
			$schedule->confirmed = $r->confirmed;
			$schedule->datetime_confirmed = $r->datetime_confirmed;
			$schedule->user_confirmed = $r->site_user_confirmed;
			$schedule->discharge_transfer_schedule = $r->discharge_transfer_schedule;
			$schedule->transfer_request = $r->transfer_request;
			$schedule->transfer_from_facility = $r->transfer_from_facility;
			$schedule->transfer_to_facility = $r->transfer_to_facility;
			$schedule->transfer_comment = $r->transfer_comment;
			$schedule->admit_order = $r->admit_order;
			$schedule->status = $r->status;
			$schedule->discharge_datetime_modified = $r->discharge_datetime_modified;
			$schedule->discharge_user_modified = $r->discharge_site_user_modified;

			if ($patient->save()) {
				$hhSchedule->patient_id = $patient->id;
				$hhSchedule->save();
				$schedule->patient_id = $patient->id;
				$schedule->save();

				// Per request, patient note files are no longer pulled from the admission dashboard
				// removed on 2015-08-25 by kwh

				// for ($i = 0; $i <= 9; $i++) {
				// 	$file = "notes_file{$i}";
				// 	$name = "notes_name{$i}";

				// 	$patient_notes = new PatientNote;

				// 	// check for already existing files and save

				// 	$patient_notes->patient_id = $patient->id;
				// 	if (isset ($r->$name) && $r->$name != null) {
				// 		$patient_notes->name = $r->$name;
				// 	}

				// 	if (isset ($r->$file) && $r->$file != null) {
				// 		$patient_notes->file = $r->$file;
				// 		if (!$patient_notes->checkExisting()) {
				// 			$patient_notes->save();
				// 		}
				// 	}
				// }
			}


		}

		return true;

	}


	public function syncCurrentPatients($location_id = false) {
		if ($location_id) {
			$params = array(
				":locationid" => $location_id,
				":datetime" => date('Y-m-d H:i:s', strtotime('now')),
			);


			$sql = "select distinct "
				. db()->dbname2 . ".`room`.*, "
				. db()->dbname2 . ".`patient_admit`.`pubid` as `patient_pubid`, "
				. db()->dbname2 . ".`patient_admit`.`physician_id`, "
				. db()->dbname2 . ".`patient_admit`.`last_name`, "
				. db()->dbname2 . ".`patient_admit`.`first_name`, "
				. db()->dbname2 . ".`patient_admit`.`address`, "
				. db()->dbname2 . ".`patient_admit`.`city`, "
				. db()->dbname2 . ".`patient_admit`.`state`, "
				. db()->dbname2 . ".`patient_admit`.`zip`, "
				. db()->dbname2 . ".`patient_admit`.`phone`, "
				. db()->dbname2 . ".`patient_admit`.`sex`, "
				. db()->dbname2 . ".`patient_admit`.`ethnicity`, "
				. db()->dbname2 . ".`patient_admit`.`marital_status`, "
				. db()->dbname2 . ".`patient_admit`.`ssn`, "
				. db()->dbname2 . ".`patient_admit`.`birthday`, "
				. db()->dbname2 . ".`patient_admit`.`religion`, "
				. db()->dbname2 . ".`patient_admit`.`medicare_number`, "
				. db()->dbname2 . ".`schedule`.`pubid` as `schedule_pubid`, "
				. db()->dbname2 . ".`schedule`.`facility`, "
				. db()->dbname2 . ".`schedule`.`datetime_admit`, "
				. db()->dbname2 . ".`schedule`.`datetime_discharge`, "
				. db()->dbname2 . ".`schedule`.`discharge_to`, "
				. db()->dbname2 . ".`schedule`.`discharge_disposition`, "
				. db()->dbname2 . ".`schedule`.`service_disposition`, "
				. db()->dbname2 . ".`schedule`.`discharge_location_id`, "
				. db()->dbname2 . ".`schedule`.`home_health_id`, "
				. db()->dbname2 . ".`schedule`.`discharge_address`, "
				. db()->dbname2 . ".`schedule`.`discharge_city`, "
				. db()->dbname2 . ".`schedule`.`discharge_state`, "
				. db()->dbname2 . ".`schedule`.`discharge_zip`, "
				. db()->dbname2 . ".`schedule`.`discharge_phone`, "
				. db()->dbname2 . ".`schedule`.`datetime_discharge_bedhold_end` as `datetime_discharge_bedhold_end`, "
				. db()->dbname2 . ".`schedule`.`discharge_comment`, "
				. db()->dbname2 . ".`schedule`.`readmit_type`, "
				. db()->dbname2 . ".`schedule`.`elective`, "
				. db()->dbname2 . ".`schedule`.`confirmed`, "
				. db()->dbname2 . ".`schedule`.`datetime_confirmed`, "
				. db()->dbname2 . ".`schedule`.`site_user_confirmed`, "
				. db()->dbname2 . ".`schedule`.`discharge_transfer_schedule`, "
				. db()->dbname2 . ".`schedule`.`transfer_request`, "
				. db()->dbname2 . ".`schedule`.`transfer_from_facility`, "
				. db()->dbname2 . ".`schedule`.`transfer_to_facility`, "
				. db()->dbname2 . ".`schedule`.`transfer_comment`, "
				. db()->dbname2 . ".`schedule`.`admit_order`, "
				. db()->dbname2 . ".`schedule`.`status`, "
				. db()->dbname2 . ".`schedule`.`discharge_datetime_modified`, "
				. db()->dbname2 . ".`schedule`.`discharge_site_user_modified`, "
				. db()->dbname2 . ".`patient_admit_nursing`.`height`, "
				. db()->dbname2 . ".`patient_admit_nursing`.`weight`, "
				. db()->dbname2 . ".`schedule_hospital`.`is_complete`, "
				. db()->dbname2 . ".`schedule_hospital`.`datetime_sent`
				from " . db()->dbname2 . ".`room`
				inner join " . db()->dbname2 . ".`schedule` on " . db()->dbname2 . ".`schedule`.`room`=" . db()->dbname2 . ".`room`.`id`
				inner join " . db()->dbname2 . ".`patient_admit` on " . db()->dbname2 . ".`schedule`.`patient_admit`=" . db()->dbname2 . ".`patient_admit`.`id`
				left join " . db()->dbname2 . ".`schedule_hospital` on " . db()->dbname2 . ".`schedule_hospital`.`schedule`=" . db()->dbname2 . ".`schedule`.`id`
				left join " . db()->dbname2 . ".`patient_admit_nursing` on " . db()->dbname2 . ".`patient_admit_nursing`.`patient_admit`=" . db()->dbname2 . ".`patient_admit`.`id`
				where " . db()->dbname2 . ".`room`.`facility`=:locationid
				and (" . db()->dbname2 . ".`schedule`.`status`='Approved' OR " . db()->dbname2 . ".`schedule`.`status`='Under Consideration' OR " . db()->dbname2 . ".`schedule`.`status` = 'Discharged')
				and :datetime >= " . db()->dbname2 . ".schedule.`datetime_admit`
				and
				(
					(" . db()->dbname2 . ".schedule.`datetime_discharge` IS NULL)
					OR
					(
					" . db()->dbname2 . ".schedule.`datetime_discharge` >= :datetime
					)
					OR
					(
					" . db()->dbname2 . ".schedule.`discharge_to`!='Discharge to Hospital (Bed Hold)' and (:datetime < " . db()->dbname2 . ".schedule.`datetime_discharge`)
					)
					or
					(
					" . db()->dbname2 . ".schedule.`discharge_to`='Discharge to Hospital (Bed Hold)' and :datetime < " . db()->dbname2 . ".schedule.`datetime_discharge_bedhold_end`
					)
				)";

			$sql .= " GROUP BY " . db()->dbname2 . ".room.`id`
				ORDER BY " . db()->dbname2 . ".room.`number`
				";

			$result = $this->fetchAll($sql, $params);
			$patientResults = array();

			foreach ($result as $k => $r) {

				//	If the items have already been saved to the db then get them.
				$patient = $this->fetchById($r->patient_pubid, 'Patient');
				$schedule = $this->fetchById($r->schedule_pubid, 'Schedule');

				if (empty ($patient)) {
					$patient = new Patient;
				}

				if (empty ($schedule)) {
					$schedule = new Schedule;
				}

				$patient->public_id = $r->patient_pubid;
				if ($r->last_name != "") {
					$patient->last_name = $r->last_name;
				}
				if ($r->first_name != "") {
					$patient->first_name = $r->first_name;
				}
				if (isset ($r->middle_name)) {
					$patient->middle_name = $r->middle_name;
				}
				if (isset ($r->address)) {
					$patient->address = $r->address;
				}
				if (isset ($r->city)) {
					$patient->city = $r->city;
				}
				if (isset ($r->state)) {
					$patient->state = $r->state;
				}
				if (isset ($r->zip)) {
					$patient->zip = $r->zip;
				}
				if (isset ($r->phone)) {
					$patient->phone = $r->phone;
				}
				if (isset ($r->sex)) {
					$patient->sex = $r->sex;
				}
				if (isset ($r->birthday)) {
					$patient->date_of_birth = $r->birthday;
				}
				if (isset ($r->ethnicity)) {
					$patient->ethnicity = $r->ethnicity;
				}
				if (isset ($r->marital_status)) {
					$patient->marital_status = $r->marital_status;
				}
				if (isset ($r->religion)) {
					$patient->religion = $r->religion;
				}
				if (isset ($r->ssn)) {
					$patient->ssn = $r->ssn;
				}
				if (isset ($r->medicare_number)) {
					$patient->medicare_number = $r->medicare_number;
				}
				if (isset ($r->physician_id)) {
					$patient->pcp_id = $r->physician_id;
				}


				$schedule->public_id = $r->schedule_pubid;
				$schedule->location_id = $r->facility;
				$schedule->room_id = $r->id;
				$schedule->datetime_admit = $r->datetime_admit;
				$schedule->datetime_discharge = $r->datetime_discharge;
				$schedule->discharge_to = $r->discharge_to;
				$schedule->discharge_disposition = $r->discharge_disposition;
				$schedule->service_disposition = $r->service_disposition;
				$schedule->discharge_location_id = $r->discharge_location_id;
				$schedule->home_health_id = $r->home_health_id;
				$schedule->discharge_address = $r->discharge_address;
				$schedule->discharge_city = $r->discharge_city;
				$schedule->discharge_state = $r->discharge_state;
				$schedule->discharge_zip = $r->discharge_zip;
				$schedule->discharge_phone = $r->discharge_phone;
				$schedule->datetime_discharge_bedhold_end = $r->datetime_discharge_bedhold_end;
				$schedule->discharge_comment = $r->discharge_comment;
				$schedule->readmit_type = $r->readmit_type;
				$schedule->elective = $r->elective;
				$schedule->confirmed = $r->confirmed;
				$schedule->datetime_confirmed = $r->datetime_confirmed;
				$schedule->user_confirmed = $r->site_user_confirmed;
				$schedule->discharge_transfer_schedule = $r->discharge_transfer_schedule;
				$schedule->transfer_request = $r->transfer_request;
				$schedule->transfer_from_facility = $r->transfer_from_facility;
				$schedule->transfer_to_facility = $r->transfer_to_facility;
				$schedule->transfer_comment = $r->transfer_comment;
				$schedule->admit_order = $r->admit_order;
				$schedule->status = $r->status;
				$schedule->discharge_datetime_modified = $r->discharge_datetime_modified;
				$schedule->discharge_user_modified = $r->discharge_site_user_modified;

				if ($patient->save()) {
					$schedule->patient_id = $patient->id;

					if ($schedule->save()) {
						// Save the dietary patient info
						$obj = new PatientInfo;
						$patientInfo = $obj->fetchPatientInfoByPatient($patient->id, $schedule->location_id);
						if (empty ($patientInfo)) {
							$patientInfo = new PatientInfo;
							$patientInfo->patient_id = $patient->id;
						}

						// $patientInfo->public_id = null;

						if (isset ($r->height)) {
							$patientInfo->height = $r->height;
						}
						if (isset ($r->weight)) {
							$patientInfo->weight = $r->weight;
						}
						$patientInfo->location_id = $r->facility;
						$patientInfo->save();
					}
				}

				$patient->number = $r->number;
				$patientResults[$k] = $patient;

			}

			return $patientResults;

		}

		return false;
	}


	public function syncDBs($location_id) {
		if ($location_id) {
			$params[":locationid"] = $location_id;

			$sql = "SELECT DISTINCT "
				.db()->dbname2 . ".`room`.*, "
				. db()->dbname2 . ".`patient_admit`.`pubid` as `patient_pubid`, "
				. db()->dbname2 . ".`patient_admit`.`physician_id`, "
				. db()->dbname2 . ".`patient_admit`.`last_name`, "
				. db()->dbname2 . ".`patient_admit`.`first_name`, "
				. db()->dbname2 . ".`patient_admit`.`address`, "
				. db()->dbname2 . ".`patient_admit`.`city`, "
				. db()->dbname2 . ".`patient_admit`.`state`, "
				. db()->dbname2 . ".`patient_admit`.`zip`, "
				. db()->dbname2 . ".`patient_admit`.`phone`, "
				. db()->dbname2 . ".`patient_admit`.`sex`, "
				. db()->dbname2 . ".`patient_admit`.`ethnicity`, "
				. db()->dbname2 . ".`patient_admit`.`marital_status`, "
				. db()->dbname2 . ".`patient_admit`.`ssn`, "
				. db()->dbname2 . ".`patient_admit`.`birthday`, "
				. db()->dbname2 . ".`patient_admit`.`religion`, "
				. db()->dbname2 . ".`patient_admit`.`medicare_number`, "
				. db()->dbname2 . ".`schedule`.`pubid` as `schedule_pubid`, "
				. db()->dbname2 . ".`schedule`.`facility`, "
				. db()->dbname2 . ".`schedule`.`datetime_admit`, "
				. db()->dbname2 . ".`schedule`.`datetime_discharge`, "
				. db()->dbname2 . ".`schedule`.`discharge_to`, "
				. db()->dbname2 . ".`schedule`.`discharge_disposition`, "
				. db()->dbname2 . ".`schedule`.`service_disposition`, "
				. db()->dbname2 . ".`schedule`.`discharge_location_id`, "
				. db()->dbname2 . ".`schedule`.`home_health_id`, "
				. db()->dbname2 . ".`schedule`.`discharge_address`, "
				. db()->dbname2 . ".`schedule`.`discharge_city`, "
				. db()->dbname2 . ".`schedule`.`discharge_state`, "
				. db()->dbname2 . ".`schedule`.`discharge_zip`, "
				. db()->dbname2 . ".`schedule`.`discharge_phone`, "
				. db()->dbname2 . ".`schedule`.`datetime_discharge_bedhold_end` as `datetime_discharge_bedhold_end`, "
				. db()->dbname2 . ".`schedule`.`discharge_comment`, "
				. db()->dbname2 . ".`schedule`.`readmit_type`, "
				. db()->dbname2 . ".`schedule`.`elective`, "
				. db()->dbname2 . ".`schedule`.`confirmed`, "
				. db()->dbname2 . ".`schedule`.`datetime_confirmed`, "
				. db()->dbname2 . ".`schedule`.`site_user_confirmed`, "
				. db()->dbname2 . ".`schedule`.`discharge_transfer_schedule`, "
				. db()->dbname2 . ".`schedule`.`transfer_request`, "
				. db()->dbname2 . ".`schedule`.`transfer_from_facility`, "
				. db()->dbname2 . ".`schedule`.`transfer_to_facility`, "
				. db()->dbname2 . ".`schedule`.`transfer_comment`, "
				. db()->dbname2 . ".`schedule`.`admit_order`, "
				. db()->dbname2 . ".`schedule`.`status`, "
				. db()->dbname2 . ".`schedule`.`discharge_datetime_modified`, "
				. db()->dbname2 . ".`schedule`.`discharge_site_user_modified`, "
				. db()->dbname2 . ".`patient_admit_nursing`.`height`, "
				. db()->dbname2 . ".`patient_admit_nursing`.`weight`, "
				. db()->dbname2 . ".`schedule_hospital`.`is_complete`, "
				. db()->dbname2 . ".`schedule_hospital`.`datetime_sent`
				FROM " . db()->dbname2 . ".`room`
				INNER JOIN " . db()->dbname2 . ".`schedule` on " . db()->dbname2 . ".`schedule`.`room`=" . db()->dbname2 . ".`room`.`id`
				INNER JOIN " . db()->dbname2 . ".`patient_admit` on " . db()->dbname2 . ".`schedule`.`patient_admit`=" . db()->dbname2 . ".`patient_admit`.`id`
				LEFT JOIN " . db()->dbname2 . ".`schedule_hospital` on " . db()->dbname2 . ".`schedule_hospital`.`schedule`=" . db()->dbname2 . ".`schedule`.`id`
				LEFT JOIN " . db()->dbname2 . ".`patient_admit_nursing` on " . db()->dbname2 . ".`patient_admit_nursing`.`patient_admit`=" . db()->dbname2 . ".`patient_admit`.`id`
				WHERE " . db()->dbname2 . ".`room`.`facility`=:locationid";

			$sql .= " ORDER BY " . db()->dbname2 . ".room.`number`
				";

			$result = $this->fetchAll($sql, $params);
			$patientResults = array();

			pr ($result); exit;

			foreach ($result as $k => $r) {

				//	If the items have already been saved to the db then get them.
				$patient = $this->fetchById($r->patient_pubid, 'Patient');
				$schedule = $this->fetchById($r->schedule_pubid, 'Schedule');

				if (empty ($patient)) {
					$patient = new Patient;
				}

				if (empty ($schedule)) {
					$schedule = new Schedule;
				}

				$patient->public_id = $r->patient_pubid;
				if ($r->last_name != "") {
					$patient->last_name = $r->last_name;
				}
				if ($r->first_name != "") {
					$patient->first_name = $r->first_name;
				}
				if (isset ($r->middle_name)) {
					$patient->middle_name = $r->middle_name;
				}
				if (isset ($r->address)) {
					$patient->address = $r->address;
				}
				if (isset ($r->city)) {
					$patient->city = $r->city;
				}
				if (isset ($r->state)) {
					$patient->state = $r->state;
				}
				if (isset ($r->zip)) {
					$patient->zip = $r->zip;
				}
				if (isset ($r->phone)) {
					$patient->phone = $r->phone;
				}
				if (isset ($r->sex)) {
					$patient->sex = $r->sex;
				}
				if (isset ($r->birthday)) {
					$patient->date_of_birth = $r->birthday;
				}
				if (isset ($r->ethnicity)) {
					$patient->ethnicity = $r->ethnicity;
				}
				if (isset ($r->marital_status)) {
					$patient->marital_status = $r->marital_status;
				}
				if (isset ($r->religion)) {
					$patient->religion = $r->religion;
				}
				if (isset ($r->ssn)) {
					$patient->ssn = $r->ssn;
				}
				if (isset ($r->medicare_number)) {
					$patient->medicare_number = $r->medicare_number;
				}
				if (isset ($r->physician_id)) {
					$patient->pcp_id = $r->physician_id;
				}


				$schedule->public_id = $r->schedule_pubid;
				$schedule->location_id = $location_id;
				$schedule->room_id = $r->id;
				$schedule->datetime_admit = $r->datetime_admit;
				$schedule->datetime_discharge = $r->datetime_discharge;
				$schedule->discharge_to = $r->discharge_to;
				$schedule->discharge_disposition = $r->discharge_disposition;
				$schedule->service_disposition = $r->service_disposition;
				$schedule->discharge_location_id = $r->discharge_location_id;
				$schedule->home_health_id = $r->home_health_id;
				$schedule->discharge_address = $r->discharge_address;
				$schedule->discharge_city = $r->discharge_city;
				$schedule->discharge_state = $r->discharge_state;
				$schedule->discharge_zip = $r->discharge_zip;
				$schedule->discharge_phone = $r->discharge_phone;
				$schedule->datetime_discharge_bedhold_end = $r->datetime_discharge_bedhold_end;
				$schedule->discharge_comment = $r->discharge_comment;
				$schedule->readmit_type = $r->readmit_type;
				$schedule->elective = $r->elective;
				$schedule->confirmed = $r->confirmed;
				$schedule->datetime_confirmed = $r->datetime_confirmed;
				$schedule->user_confirmed = $r->site_user_confirmed;
				$schedule->discharge_transfer_schedule = $r->discharge_transfer_schedule;
				$schedule->transfer_request = $r->transfer_request;
				$schedule->transfer_from_facility = $r->transfer_from_facility;
				$schedule->transfer_to_facility = $r->transfer_to_facility;
				$schedule->transfer_comment = $r->transfer_comment;
				$schedule->admit_order = $r->admit_order;
				$schedule->status = $r->status;
				$schedule->discharge_datetime_modified = $r->discharge_datetime_modified;
				$schedule->discharge_user_modified = $r->discharge_site_user_modified;

				if ($patient->save()) {
					$schedule->patient_id = $patient->id;

					if ($schedule->save()) {
						// Save the dietary patient info
						$obj = new PatientInfo;
						$patientInfo = $obj->fetchPatientInfoByPatient($patient->id, $schedule->location_id);
						if (empty ($patientInfo)) {
							$patientInfo = new PatientInfo;
							$patientInfo->patient_id = $patient->id;
						}

						// $patientInfo->public_id = null;

						if (isset ($r->height)) {
							$patientInfo->height = $r->height;
						}
						if (isset ($r->weight)) {
							$patientInfo->weight = $r->weight;
						}
						$patientInfo->location_id = $r->facility;
						$patientInfo->save();
					}
				}

				$patient->number = $r->number;
				$patientResults[$k] = $patient;

			}

			echo "Success!"; exit;

		}

		echo "Failed"; exit;

	}



	public function fetchSchedule($patient_id) {
		$sql = "SELECT schedule.*, room.number AS room_number FROM " . db()->dbname2 . ".schedule INNER JOIN " . db()->dbname2 . ".room ON room.id = schedule.room WHERE schedule.patient_admit = (SELECT patient_admit.id FROM " . db()->dbname2 . ".patient_admit WHERE patient_admit.pubid = :patient_id) ORDER BY datetime_admit DESC LIMIT 1";
		$params[":patient_id"] = $patient_id;
		return $this->fetchOne($sql, $params);
	}





	public function fetchEmptyRooms($location_id) {
		$sql = "select * from " . db()->dbname2 . ".`room` where facility=:facilityid and id not in (
			select `room`.`id` from " . db()->dbname2 . ".`room`
			inner join " . db()->dbname2 . ".`schedule` on `schedule`.`room`=`room`.`id`
			where `room`.`facility`=:facilityid
			and :datetime >= `datetime_admit`
			and (`schedule`.`status`='Approved' || `schedule`.`status`='Under Consideration' || `schedule`.`status` = 'Discharged')
			and
			(
				(`datetime_discharge` IS NULL)
				OR
				(
				`datetime_discharge` >= :datetime
				)
				OR
				(
				`discharge_to`!='Discharge to Hospital (Bed Hold)' and :datetime < `datetime_discharge`
				)
				or
				(
				`discharge_to`='Discharge to Hospital (Bed Hold)' and :datetime < `datetime_discharge_bedhold_end`
				)
			)
		)
		ORDER BY `number`
		";
		$params = array(":facilityid" => $location_id, ":datetime" => date('Y-m-d H:i:s', strtotime('now')));
		return $this->fetchAll($sql, $params);

	}




	public function mergeRooms($empty, $scheduled) {
		$temp = array();
		$index = array();
		foreach ($empty as $k => $v) {
			$temp[$v->number] = $v->number;
			$index[$v->number] = array("empty", $k);
		}
		foreach ($scheduled as $k => $v) {
			$temp[$v->number] = $v->number;
			$index[$v->number] = array("scheduled", $k);
		}
		sort($temp);

		$retval = array();
		foreach ($temp as $number) {
			$which = $index[$number][0];
			$idx = $index[$number][1];
			$retval[] = ${$which}[$idx];
		}

		return $retval;

	}








}
