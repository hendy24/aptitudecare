<?php

class AdmissionDashboard extends AppModel {

	protected $table = 'schedule';


	public function fetchDischarges($datetime_start, $datetime_end, $location_id, $areas) {

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
			//	Check if the patient data has already been synchronized

			$scheduleObj = new HomeHealthSchedule();
			$patientObj = new Patient();

			//	If the items have already been saved to the db then get them.
			$schedule = $scheduleObj->fetchById($r->schedule_pubid);
			$patient = $patientObj->fetchById($r->patient_pubid);

			//	If the schedule is empty then this is a new object, we are going to set the start of care
			//	date to be equal to the discharge date
			if (empty ($schedule)) {
				$schedule = new HomeHealthSchedule();
				$schedule->start_of_care = $r->datetime_discharge;
			} else {
				//	it is possible that the start of care date has been modified in the home health application
				//	and the discharge date has been changed in the admission application.  If that has happened
				//	we need to reset the start of care date to match the new discharge date
				if ($schedule->referral_date !== $r->datetime_discharge) {
					$schedule->start_of_care = $r->datetime_discharge;
				}
			}

			//	This will update the referral date if the discharge date changes
			$schedule->referral_date = $r->datetime_discharge;

			

			if (empty ($patient)) {
				$patient = new Patient();
			}

			$schedule->public_id = $r->schedule_pubid;

			// need to find the healthcare facility id by the location name
			$healthcare_facility = $this->loadTable("Location", $r->facility)->fetchHealthcareFacilityId();
			
			if (isset ($healthcare_facility->id)) {
				$schedule->admit_from_id = $healthcare_facility->id;
			}
			
			$schedule->referred_by_type = "ahc_facility";
			$schedule->location_id = $r->facility;
			$schedule->datetime_created = mysql_datetime();
			$schedule->datetime_modified = mysql_datetime();
			$schedule->inpatient_diagnosis = $r->other_diagnosis;
			$schedule->specialist_id = $r->ortho_id;
			$schedule->primary_insurance = $r->paymethod;
			$schedule->primary_insurance_number = $r->medicare_number;

			if ($r->service_disposition == "AHC Home Health") {
				$schedule->confirmed = true;
			} else {
				$schedule->confirmed = false;
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
			




			$patient_id = $patient->save();
			if (isset ($patient->id)) {
				$patient_id = $patient->id;
				$schedule->patient_id = $patient->id;
			} else {
				$schedule->patient_id = $patient_id;
			}

			for ($i = 0; $i <= 9; $i++) {
				$file = "notes_file{$i}";
				$name = "notes_name{$i}";
				
				$patient_notes = new PatientNote;

				// check for already existing files and save
				
				$patient_notes->patient_id = $patient->id;
				if (isset ($r->$name) && $r->$name != null) {
					$patient_notes->name = $r->$name;
				}

				if (isset ($r->$file) && $r->$file != null) {
					$patient_notes->file = $r->$file;
					if (!$patient_notes->checkExisting()) {
						$patient_notes->save();
					}
				}				
			}

			$schedule->save();
			

		}
		
		return true;
		
	}


	public function fetchCurrentPatients($location_id = false) {
		if ($location_id) {		
			$params = array(
				":locationid" => $location_id,
				":datetime" => date('Y-m-d H:i:s', strtotime('now')),
			);
		
		
			$sql = "select distinct "
				. db()->dbname2 . ".`room`.*,"
				. db()->dbname2 . ".`patient_admit`.`pubid` as `patient_pubid`,"
				. db()->dbname2 . ".`patient_admit`.`physician_id`,"
				. db()->dbname2 . ".`patient_admit`.`last_name`,"
				. db()->dbname2 . ".`patient_admit`.`first_name`,"
				. db()->dbname2 . ".`patient_admit`.`address`,"
				. db()->dbname2 . ".`patient_admit`.`city`,"
				. db()->dbname2 . ".`patient_admit`.`state`,"
				. db()->dbname2 . ".`patient_admit`.`zip`,"
				. db()->dbname2 . ".`patient_admit`.`phone`,"
				. db()->dbname2 . ".`patient_admit`.`sex`,"
				. db()->dbname2 . ".`patient_admit`.`ethnicity`,"
				. db()->dbname2 . ".`patient_admit`.`marital_status`,"
				. db()->dbname2 . ".`patient_admit`.`ssn`,"
				. db()->dbname2 . ".`patient_admit`.`birthday`,"
				. db()->dbname2 . ".`patient_admit`.`religion`,"
				. db()->dbname2 . ".`patient_admit`.`medicare_number`,"
				. db()->dbname2 . ".`schedule`.`pubid` as `schedule_pubid`,"
				. db()->dbname2 . ".`schedule`.`facility`,"
				. db()->dbname2 . ".`schedule`.`datetime_admit` as `datetime_admit`,"
				. db()->dbname2 . ".`schedule`.`datetime_discharge` as `datetime_discharge`,"
				. db()->dbname2 . ".`schedule`.`discharge_to` as `discharge_to`,"
				. db()->dbname2 . ".`schedule`.`datetime_discharge_bedhold_end` as `datetime_discharge_bedhold_end`,"
				. db()->dbname2 . ".`schedule`.`status` as `status`,"
				. db()->dbname2 . ".`patient_admit_nursing`.`height`,"
				. db()->dbname2 . ".`patient_admit_nursing`.`weight`,
				`schedule`.`transfer_request`,"
				. db()->dbname2 . ".`schedule_hospital`.`is_complete`,
				`schedule_hospital`.`datetime_sent`
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

				if (empty ($patient)) {
					$patient = new Patient();
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

				// not getting all the patient info returned into an array that can be used.
				// need to fix the $patientResults array


				if ($patient->save()) {
					// check for 
					$obj = new PatientInfo;
					$patientInfo = $obj->fetchDietInfo($patient->id);
					// $patientInfo->public_id = null;
					$patientInfo->patient_id = $patient->id;
					if (isset ($r->height)) {
						$patientInfo->height = $r->height;
					}
					if (isset ($r->weight)) {
						$patientInfo->weight = $r->weight;
					}
					$patientInfo->location_id = $r->facility;
					$patientInfo->save();
				} 

				$patient->number = $r->number;
				$patientResults[$k] = $patient;
				
			}

			return $patientResults;

		}
	
		return false;
	}



	public function fetchSchedule($patient_id) {
		$sql = "SELECT schedule.*, room.number AS room_number FROM " . db()->dbname2 . ".schedule INNER JOIN " . db()->dbname2 . ".room ON room.id = schedule.room WHERE schedule.patient_admit = (SELECT patient_admit.id FROM " . db()->dbname2 . ".patient_admit WHERE patient_admit.pubid = :patient_id) ORDER BY datetime_admit DESC LIMIT 1";
		$params[":patient_id"] = $patient_id;
		return $this->fetchOne($sql, $params);
	}
}