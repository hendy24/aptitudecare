<?php


class CMS_Patient_Admit extends CMS_Table {
	protected $_facility = false;
	
	public static $table = "patient_admit";
	protected static $metadata = array(
		"notes_file0" => array(
			"widget" => "file",
			"options" => array(
				"mime_types" => array(
					"application/pdf"
				),
				"protected" => true
			)
		),
		"notes_file1" => array(
			"widget" => "file",
			"options" => array(
				"mime_types" => array(
					"application/pdf"
				),
				"protected" => true
			)
		),
		"notes_file2" => array(
			"widget" => "file",
			"options" => array(
				"mime_types" => array(
					"application/pdf"
				),
				"protected" => true
			)
		),
		"notes_file3" => array(
			"widget" => "file",
			"options" => array(
				"mime_types" => array(
					"application/pdf"
				),
				"protected" => true
			)
		),
		"notes_file4" => array(
			"widget" => "file",
			"options" => array(
				"mime_types" => array(
					"application/pdf"
				),
				"protected" => true
			)
		),
		"notes_file5" => array(
			"widget" => "file",
			"options" => array(
				"mime_types" => array(
					"application/pdf"
				),
				"protected" => true
			)
		),
		"notes_file6" => array(
			"widget" => "file",
			"options" => array(
				"mime_types" => array(
					"application/pdf"
				),
				"protected" => true
			)
		),
		"notes_file7" => array(
			"widget" => "file",
			"options" => array(
				"mime_types" => array(
					"application/pdf"
				),
				"protected" => true
			)
		),
		"notes_file8" => array(
			"widget" => "file",
			"options" => array(
				"mime_types" => array(
					"application/pdf"
				),
				"protected" => true
			)
		),
		"notes_file9" => array(
			"widget" => "file",
			"options" => array(
				"mime_types" => array(
					"application/pdf"
				),
				"protected" => true
			)
		)
	);
	
	public function fullName() {
		
		$str = trim($this->last_name) . ", ";
		$str .= trim($this->first_name);
		if ($this->middle_name != '') {
			$str .= " " . trim($this->middle_name);
		}
		if (strlen($this->middle_name) == 1) {
			$str .= ".";
		}
		/*
		if ($this->sex != '') {
			if ($this->sex == 'Male') {
				$sex = "M.";
			} elseif ($this->sex == 'Female') {
				$sex = "F.";	
			}
			$str .= " ({$sex}";
		}
		if ($this->birthday != '') {
			if ($this->sex == '') {
				$str .= ", (";
			}
			$str .= " b. " . date("m/d/Y", strtotime($this->birthday));
		}
		if ($this->sex != '' || $this->birthday != '') {
			$str .= ")";
		}
		*/
		return $str;
		
	}
	
	
	public function save($log = true) {
		
		
		if ($log == true) {
			$old = new CMS_Patient_Admit($this->id);
			$message = serialize(array(
				"diff" => array_diff( array_keys((Array) $old->getRecord()), array_keys((Array) $this->getRecord()) ),
				"class" => "patient_admit_save"		
			));
		}
		$this->datetime_modified = datetime();
		
		if (auth()->valid()) {
			$this->site_user_modified = auth()->getRecord()->id;
		}
		parent::save();
		
	}
	
	public function siteUserModified() {
		if ($this->site_user_modified != '') {
			return new CMS_Site_User($this->site_user_modified);
		}
		return false;
	}
	
	public function siteUserCreated() {
		if ($this->site_user_created != '') {
			return new CMS_Site_User($this->site_user_created);
		}
		return false;
	}
	
	
	public static function searchByName($last, $first, $middle) {

/*
		$sql = "select max(patient_admit.id) as patient_id, patient_admit.pubid, person_id, first_name, middle_name,
					last_name, birthday, sex, ssn, max(schedule.patient_admit), max(schedule.datetime_discharge) as datetime_discharge, 
					max(schedule.datetime_admit) as datetime_admit, schedule_hospital.is_complete from `" . static::$table . "`, `schedule` 
					left join `schedule_hospital` on `schedule_hospital`.`schedule` = `schedule`.`id` where patient_admit.id=schedule.patient_admit and last_name like :last";
*/
		$sql = "select max(patient_admit.id) as patient_id, patient_admit.pubid, patient_admit.person_id, patient_admit.last_name, patient_admit.first_name, patient_admit.middle_name, patient_admit.birthday, patient_admit.sex, patient_admit.ssn, schedule.datetime_discharge, schedule.datetime_admit, schedule.flag_readmission, schedule_hospital.is_complete from patient_admit inner join schedule on schedule.patient_admit = patient_admit.id left join schedule_hospital on schedule_hospital.schedule = schedule.id where patient_admit.last_name like :last and schedule.status = 'Discharged'";
		$params = array(
			":last" => "%{$last}%"
		);
		if ($first != '') {
			$sql .= " and first_name like :first";
			$params[":first"] = "%{$first}%";
		}
		if ($middle != '') {
			$sql .= " and middle_name like :middle";
			$params[":middle"] = "%{$middle}%";
		}
		$sql .= " group by `person_id`";
				
		$obj = static::generate();
		$results = $obj->fetchCustom($sql, $params);
		return $results;
	}
	
	
	public static function getRecentAdmitByPerson($person_id) {
		$obj = static::generate();
		$table = static::$table;
		$sql = "select `{$table}`.* from `{$table}` inner join 
		`schedule` on `schedule`.`{$table}`=`{$table}`.`id`
		 where `person_id`=:personid order by schedule.datetime_admit DESC LIMIT 1";
		$params = array(
			":personid" => $person_id
		);

		$record = current($obj->fetchCustom($sql, $params));
		return $record;
	}
	
	public static function getPatientByPubid($pubid) {
		$obj = static::generate();
		$sql = "select * from `patient_admit`  
			inner join `schedule` on `schedule`.`patient_admit` = `patient_admit`.`id`
			where `patient_admit`.`pubid` = '{$pubid}' limit 1";
		$record = current($obj->fetchCustom($sql, $params));
		return $record;
	}
	
	private static function getPatientById($id) {
		$obj = static::generate();
		$sql = "select * from `patient_admit`  
			inner join `schedule` on `schedule`.`patient_admit` = `patient_admit`.`id`
			where `patient_admit`.`id` = :id limit 1";
		$params[":id"] = $id;
		$record = current($obj->fetchCustom($sql, $params));
		return $record;
	}
	
	
	public static function cloneTransferPatient($patient_id, $facility_id) {
		
		$record = static::getPatientById($patient_id);
		
		$cloneFields = array(
			"person_id", "o2_liters", "last_name", "first_name", "middle_name", "preferred_name", "address", "city", "state", "zip",
			"phone", "phone_alt", "phone_type", "birthday", "sex", "ethnicity", "marital_status", "religion", "state_born", "ssn", "facility", "medicare_days_used", "medicare_days_available", "case_manager_id", "doctor_id", "ortho_id", "physician_name",
			"surgeon_name", "medicare_number", "paymethod", "three_night", "x_rays_received", "pharmacy_id", "hospital_id",  "supplemental_insurance_name", "supplemental_insurance_number", "hmo_name", "hmo_number", "private_pay_guarantor_name",
			"private_pay_guarantor_relationship", "private_pay_guarantor_address", "private_pay_guarantor_phone", "emergency_contact_name1", 
			"emergency_contact_relationship1", "emergency_contact_address1", "emergency_contact_phone1", "emergency_contact_name2", 
			"emergency_contact_relationship2", "emergency_contact_address2", "emergency_contact_phone2", "comments", "final_orders", "referral", "notes_file0", "notes_file1", "notes_file2", "notes_file3", "notes_file4", "notes_file5", "notes_file6", "notes_file7", "notes_file8", "notes_file9", "notes_name0", "notes_name1","notes_name2", "notes_name3", "notes_name4", "notes_name5", "notes_name6", "notes_name7", "notes_name8", "notes_name9", "notes_converted0", "notes_converted1", "notes_converted2", "notes_converted3", "notes_converted4", "notes_converted5", "notes_converted6", "notes_converted7", "notes_converted8", "notes_converted9"
		);
		
		$obj = static::generate();
		foreach ($cloneFields as $field) {
			$obj->{$field} = $record->{$field};
		}
		
		$obj->datetime_created = datetime();
		$obj->admit_from = CMS_Facility::getFacilityHospitalId($facility_id);
		$obj->site_user_created = auth()->getRecord()->id;
		
		return $obj;
	}
	
	public static function newAdmitExisting($person_id, $patient_id = false, $datetime_admit, $readmit_type = false) {
		// find the most recent admit record for this person and clone the important details from it.
		$record = static::getRecentAdmitByPerson($person_id);

				
		// fields to clone:
		$cloneFields = array(
		
		"person_id", "trans", "o2_liters", "last_name", "first_name", "middle_name", "preferred_name", "address", "city", "state", "zip",
		"phone", "birthday", "sex", "ethnicity", "marital_status", "religion", "state_born", "ssn", "facility", "doctor_id", "physician_name",
		"surgeon_name", "medicare_number", "supplemental_insurance_name", "supplemental_insurance_number", "hmo_name", "hmo_number", "private_pay_guarantor_name",
		"private_pay_guarantor_relationship", "private_pay_guarantor_address", "private_pay_guarantor_phone", "emergency_contact_name1", 
		"emergency_contact_relationship1", "emergency_contact_address1", "emergency_contact_phone1", "emergency_contact_name2", 
		"emergency_contact_relationship2", "emergency_contact_address2", "emergency_contact_phone2"/*, "other_diagnosis"*/ 
		);
		
		$obj = static::generate();
		foreach ($cloneFields as $field) {
			$obj->{$field} = $record->{$field};
		}
		$obj->datetime_created = datetime();
		// $obj->save();
		
		// find old AHRs for this patient; we want to end them since the whole point of hospital-stay tracking
		// is to get patients to come back after being at the hospital
		// $schedules = CMS_Schedule::fetchAdmitsByPersonID($person_id);  // removed on 8/23/12 by kwh to prevent bhd saving to multiple schedules
		$schedules = CMS_Schedule::fetchAdmitsByPatientID($patient_id);
		if ($schedules != false && is_array($schedules)) {
			foreach ($schedules as $schedule) {
				if ($schedule->datetime_discharge_bedhold_end != '') {
					// $schedule->datetime_discharge_bedhold_end = date("Y-m-d G:i:s", strtotime($datetime_admit)); Don't want bhd changed to new time
					$schedule->discharge_comment = "Re-admitted " . $datetime_admit;
				}
				$schedule->readmit_type = $readmit_type;
				$schedule->save();
			}
		} 
		$ahrs = CMS_Schedule_Hospital::fetchActiveByPersonId($person_id);
		if ($ahrs != false && is_array($ahrs)) {
			foreach ($ahrs as $ahr) {
				$ahr->is_complete = 1;
				$ahr->datetime_returned = date("Y-m-d G:i:s", strtotime($datetime_admit));
				$ahr->stop_tracking_reason = 'Re-admitted at ' . $datetime_admit;
				$ahr->save();
			} 
		} 

		return $obj;
	}
	
	public function attachNotes($formField, $name) {
		$i = $this->canAddNotes();
		$field = "notes_file{$i}";
		$name_field = "notes_name{$i}";
		$converted_field = "notes_converted{$i}";
		if ($field == false) {
			throw new ORMException("The maximum number of notes have already been attached to this patient file.");
		}
		$this->addFile($field, $formField);
		if ($name == '') {
			// if you didn't provide a description, take the original filename
			$name = $_FILES[$formField]['name'];
		}
		$this->{$name_field} = $name;
		$this->{$converted_field} = 0;
		
		$this->save();
	}
	
	public function removeNotes($dbField) {		//eg, removeNotes("notes_field4")
		$this->removeFile($dbField);
	}
	
	public function readyForNotes() {
		return true;
	}
	
	public function readyForApproval(&$msg, $facility = false) {
		$msg = array();
		
		// Medicare patients must have a 3 night stay
		if ($this->paymethod == 'Medicare' && $this->three_night == 0) {
			$msg[] = " Medicare patients must complete a 3-night minimum hospital stay"; 
		}
				
		// We need chest x-rays from AZ facilities
		// $schedule = CMS_Schedule::
		// $facility = CMS_Facility::generate();
		// $facility->load($schedule->facility);
		// if ($facility->state == 'AZ') {
			// if ($this->x_rays_received == 0) {
			// 	$msg[] = "This facility is " . $this->facility;
			// }
		// }
		
		// We need notes with final orders
		if ($facility != 4) { // Remove this requirement for Albuquerque. 2013-03-06 by kwh
			if ($this->final_orders == 0) { 
				$msg[] = " Final orders have not been received";
			}
		}
		
		if (count($msg) > 0) {
			return false;
		}
		
		// otherwise return true
		return true;
	}
	
	public function hasNotes() {
		for ($i=0; $i<10; $i++) {
			if ($this->{"notes_file{$i}"} != '') {
				return true;
			}
		}
		return false;		
	}
	
	// returns the first notes field that is empty, or false
	public function canAddNotes() {
		for ($i=0; $i<10; $i++) {
			if ($this->{"notes_file{$i}"} == '') {
				return $i;
			}
		}
		return false;		
	}
	
	public function getNotes() {		
		$retval = array();
		for ($i=0; $i<10; $i++) {
			if ($this->{"notes_file{$i}"} != '') {
				$retval["notes"][$i] = $this->{"notes_file{$i}"};
				$retval["names"][$i] = $this->{"notes_name{$i}"};
			}
		}
		return $retval;
	}
	
	public static function fetchForNotesConversion() {
		$sql = "select * from `patient_admit` where
		
		(`notes_file0` is not null and `notes_converted0`=0) or
		(`notes_file1` is not null and `notes_converted1`=0) or
		(`notes_file2` is not null and `notes_converted2`=0) or
		(`notes_file3` is not null and `notes_converted3`=0) or
		(`notes_file4` is not null and `notes_converted4`=0) or
		(`notes_file5` is not null and `notes_converted5`=0) or
		(`notes_file6` is not null and `notes_converted6`=0) or
		(`notes_file7` is not null and `notes_converted7`=0) or
		(`notes_file8` is not null and `notes_converted8`=0) or
		(`notes_file9` is not null and `notes_converted9`=0)		
		";
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, array());
	}
	
	public function notesAreConverted($idx) {
		$filename = basename($this->{"notes_file{$idx}"}, ".pdf");
		if (file_exists(APP_PATH . "/protected/assets/patient_admit_notes_file{$idx}/{$filename}")) {
			return true;
		}
		return false;
	}
	
	public function hasNursingReport() {
		return true;
	}
	
	public function getNursingReport() {
		$rows = $this->related("patient_admit_nursing");
		if ($rows != false) {
			return current($rows);
		}
	}
	
	public static function referralOrgs() {
		$sql = "select distinct referral_org_name from `" . static::$table . "` where `referral_org_name` != '' and `referral_org_name` is not null order by referral_org_name";
		return db()->getRowsCustom($sql, array());
	}
	
	public function hospitalName() {
				
		if ($this->hospital_id != '') { // kwh - changed from $this->hospital
			$hospital = $this->related("hospital");
			return $hospital->name;
		}
		/* 2012-02-27 - kwh - returned to hospital table as above
		return $this->referral_org_name;*/
	}

	public static function getICD9CodeByID($id) {
		if ($id != '') {
			$sql = "select * from icd9_codes where id = {$id} limit 1";
			$params = array();
			$obj = static::generate();
			return $obj->fetchCustom($sql, $params);
		} else {
			return false;
		} 
		
	}
		
	// may be able to remove this function	
	public static function fetchFilteredReport($dateStart = false, $dateEnd = false, $facility = false, $filterby = false, $viewby = false) {
		$params = array();	
		$params[":filterby"] = $filterby;
		$params[":filterby_id"] = $filterby . "_id";
		$sql = "select * FROM `patient_admit`
			inner join `schedule` on `schedule`.`patient_admit`=`patient_admit`.`id`
			left join `:filterby` on `:filterby`.`id`=`patient_admit`.`:filterby_id`";
			
		if ($dateStart != '') {
			$dateStart = date("Y-m-d G:i:s", strtotime($dateStart) . " 00:00:00");
		}
		if ($dateEnd != '') {
			$dateEnd = date("Y-m-d G:i:s", strtotime($dateEnd) . " 23:59:59");
		}

		if ($dateStart != false && $dateEnd == false) {
			$sql .= " and `schedule`.`datetime_admit` >= :dateStart";
			$params[":dateStart"] = $dateStart;
		} elseif ($dateStart == false && $dateEnd != false) {
			$sql .= "and `schedule`.`datetime_admit` <= :dateEnd";
			$params[":dateEnd"] = $dateEnd;
		} elseif ($dateStart != false && $dateEnd != false) {
			$sql .= " and `schedule`.`datetime_admit` >= :dateStart && `schedule`.`datetime_admit` <= :dateEnd";
			$params[":dateStart"] = $dateStart;
			$params[":dateEnd"] = $dateEnd;
		} else {
			$sql .= " and `schedule`.`datetime_admit` >= '1970-01-01 00:00:01'";
		}
		
		if ($facility != false) {
			$sql .= " AND `schedule`.`facility`=:facility";
			$params[":facility"] = $facility;
		}
		
		if ($viewby != false) {
			$sql .= " AND `:filterby`.`id`=`:viewby`";
			$params[":viewby"] = $viewby;
		}
		
		$sql .= " GROUP BY `:filterby`.`id`";
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	public static function summaryReport ($date_start = false, $date_end = false, $facility_id = false, $data_id = false, $filterby = false) {
		$params = array(
			":date_start" => $date_start,
			":date_end" => $date_end,
			":facility_id" => $facility_id,
			":data_id" => $data_id
		);
		
		if ($filterby == "surgeon") {
			$filterby = "ortho";
		}
		if ($filterby == "pcp") {
			$filterby = "doctor";
		}
								
		$sql = "SELECT count(patient_admit.id) AS num_admits,";
		
		if ($filterby == "hospital") {
			$sql .= " hospital.id, hospital.name FROM patient_admit LEFT JOIN hospital ON patient_admit.hospital_id = hospital.id";
		} elseif ($filterby == "case_manager") {
			$sql .= " case_manager.id, case_manager.last_name, case_manager.first_name from patient_admit LEFT JOIN case_manager ON case_manager.id = patient_admit.{$filterby}_id";
		} else {
			$sql .= " physician.id, physician.last_name, physician.first_name FROM patient_admit LEFT JOIN physician ON physician.id = patient_admit.{$filterby}_id";
		}
		
		$sql .= " INNER JOIN schedule ON patient_admit.id = schedule.patient_admit WHERE schedule.datetime_admit >= :date_start AND schedule.datetime_admit <= :date_end AND schedule.facility = :facility_id";
		
		if ($filterby == "hospital") {
			$sql .= " AND hospital.id = :data_id"; 
		} elseif ($filterby == "case_manager") {
			$sql .= " AND case_manager.id = :data_id";
		} else {
			$sql .= " AND physician.id = :data_id"; 
		}
		
		$sql .= " AND (schedule.status = 'Approved' OR schedule.status = 'Discharged')";
								
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
/*
	public static function summaryReport($filterby = false, $dataId = false, $dateStart = false, $dateEnd = false, $facility = false) {
		$params = array();
		if ($filterby == 'ortho') {
			$filterby = "physician";
			$filterby_id = "ortho_id";
		} else  {
			$table = $filterby;
			$filterby_id = $filterby . "_id";
		}
		
		if ($dataId != false) {
			$params[":dataid"] = $dataId;
		} 
		
		$sql = "select * from `{$filterby}`"; 
		
		if ($filterby == "hospital") {
			$sql .= " left join `patient_admit` AS hospital_id ON `hospital_id`.`hospital_id` = `hospital`.`id` left join `patient_admit` AS admit_from_id ON `admit_from_id`.`admit_from` = `hospital`.`id` inner join `schedule` on `schedule`.`patient_admit`=`hospital_id`.`id`";
		} else {
			$sql .= "inner join `patient_admit` on `patient_admit`.{$filterby_id}=`{$filterby}`.`id` inner join `schedule` on `schedule`.`patient_admit`=`patient_admit`.`id`"; 
		}		
			
		if ($dateStart != '') {
			$dateStart = date("Y-m-d G:i:s", strtotime($dateStart) . " 00:00:00");
		}
		if ($dateEnd != '') {
			$dateEnd = date("Y-m-d G:i:s", strtotime($dateEnd) . " 23:59:59");
		}

		if ($dateStart != false && $dateEnd == false) {
			$sql .= " where `schedule`.`datetime_admit` >= :dateStart";
			$params[":dateStart"] = $dateStart;
		} elseif ($dateStart == false && $dateEnd != false) {
			$sql .= "where `schedule`.`datetime_admit` <= :dateEnd";
			$params[":dateEnd"] = $dateEnd;
		} elseif ($dateStart != false && $dateEnd != false) {
			$sql .= " where `schedule`.`datetime_admit` >= :dateStart AND `schedule`.`datetime_admit` <= :dateEnd";
			$params[":dateStart"] = $dateStart;
			$params[":dateEnd"] = $dateEnd;
		} else {
			$sql .= " where `schedule`.`datetime_admit` >= '1970-01-01 00:00:01'";
		}
		
		if ($facility != false) {
			$sql .= " AND `schedule`.`facility`=:facility";
			$params[":facility"] = $facility;
		}
		
		if ($filterby == "hospital") {
			$sql .= " AND (`hospital_id`.`hospital_id`=:dataid OR `admit_from_id`.`admit_from`=:dataid)";
		} elseif ($dataId != '') {
			$sql .= " AND `patient_admit`.{$filterby_id}=:dataid";
		}
				
		$sql .= " AND `schedule`.`status` != 'Cancelled'";
										
		$obj = static::generate();
		$result = $obj->fetchCustom($sql, $params);
		return count($result);

	
	}
*/
	
	public function searchPatients($name = false, $facility_id) {
		
		if ($name != '') {
			$tokens = explode(" ", $name);
			
			foreach ($tokens as $key => $token) {
				if (strstr($token, ',')) {
					$tokens[$key] = str_replace(',', '', $token);
				}
			}
			
						
			$params[":facilityid"] = $facility_id;
									
			$sql = "select 
						schedule.id as schedule_id,
						schedule.pubid as schedule_pubid,
						patient_admit.pubid as patient_pubid,
						patient_admit.last_name,
						patient_admit.first_name,
						schedule.datetime_admit,
						schedule.datetime_discharge,
						schedule.status,
						physician.first_name as physicianFirst,
						physician.last_name as physicianLast,
						hospital.name as hospitalName,
						facility.name as facilityName,
						schedule.discharge_to,
						schedule.discharge_disposition,
						schedule.service_disposition
				from patient_admit 
				inner join schedule on schedule.patient_admit = patient_admit.id 
				left join physician on physician.id = patient_admit.physician_id
				left join hospital on hospital.id = patient_admit.hospital_id
				inner join facility on facility.id = schedule.facility where";
			$sql .= " schedule.facility = :facilityid";
			
/*
			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$sql .= " CONCAT_WS(' ', physician.first_name, physician.last_name) LIKE :term{$idx}";
				$sql .= " OR CONCAT_WS(', ', physician.last_name, physician.first_name) LIKE :term{$idx}";
				$sql .= " last_name like :term{$idx} OR first_name like :term{$idx}";
				$params[":term{$idx}"] = "%{$token}%";
			}
*/

			$sql .= " AND patient_admit.first_name LIKE '%" . $tokens[0] . "%' AND patient_admit.last_name LIKE '%" . $tokens[1] . "%' OR";
			$sql .= " patient_admit.last_name LIKE '%" . $tokens[0] . "%' AND patient_admit.first_name LIKE '%" . $tokens[1] . "%' AND";
			
			$sql = rtrim($sql, " AND");
						
			$sql .= " AND schedule.facility = :facilityid";
			$sql .= " order by schedule.datetime_admit DESC";
			
																											
			$result = $this->fetchCustom($sql, $params);
			
			if (!empty ($result)) {
				return $result;
			}
		} 
		
		return false;
		
	}



	
}