<?php
class CMS_Schedule extends CMS_Table {
	
	public static $table = "schedule";
	
	protected static $metadata = array();
	protected $_patient = false;
	protected $_room = false;
	protected $_hospital = false;
	protected $_physician = false;
	protected $_surgeon = false;
	
	public function save() {
		// make a copy of the pre-save state straight from the db
		$schedule_before = new CMS_Schedule($this->id);
		
		// write to the db
		parent::save();
	
		// send notification
/*
		if ($schedule_before->valid() && static::notifyWorthyChange($this, $schedule_before)) {
			CMS_Notify_Event::trigger("schedule_changed", $this, $schedule_before);
		} else {
			if (! $schedule_before->valid() ) {
				CMS_Notify_Event::trigger("schedule_general", $this);
			}
		}
*/
	}
	
	public static function notifyWorthyChange($schedule, $schedule_before) {
		if ($schedule->facility != $schedule_before->facility) {
				return true;
		}
		if ($schedule->room != $schedule_before->room) {
				return true;
		}
		if ($schedule->datetime_admit != $schedule_before->datetime_admit) {
				return true;
		}
		return false;
	}
	
	public function admitDatetimeFormatted() {
		return date("m/d/Y g:i a", strtotime($this->datetime_admit));
	}
	
	public function dischargeDatetimeFormatted() {
		if ($this->datetime_discharge != '') {
			return date("m/d/Y g:i a", strtotime($this->datetime_discharge));
		} else {
			return 'No discharge currently scheduled.';
		}
	}
	
	public function getPatient() {
		if ($this->_patient == false) {
			$this->_patient = new CMS_Patient_Admit($this->patient_admit);
		}
		return $this->_patient;
	}
	
	public function getDischargeLocation() {
		if ($this->_hospital == false) {
			$this->_hospital = new CMS_Hospital($this->hospital_id);
		}
		return $this->_hospital->name;
	}
	
	public function getDischargeHospital() {
		if ($this->_hospital == false) {
			$this->_hospital = new CMS_Hospital($this->hospital);
		}
		return $this->_hospital->name;
	}
	
	public function getPhysicianName() {
		if ($this->_physician == false) {
			$physician_id = $this->getPatient()->physician_id;
			$this->_physician = new CMS_Physician($physician_id);
		}
		
		if ($this->_physician->last_name != '') {
			return $this->_physician->last_name . ', ' . $this->_physician->first_name;
		}
		
		return '';
	}

	public function getPhysician() {
		if ($this->_physician == false) {
			$this->_physician = $this->related("physician_name");
		}
		return $this->_physician;
	}

	public function getSurgeon() {
		if ($this->_surgeon == false) {
			$this->_surgeon = $this->related("surgeon_name");
		}
		return $this->_surgeon;
	}
	
	public function getFacility() {
		if ($this->_facility == false) {
			$this->_facility = $this->related("facility");
		}
		return $this->_facility;
	}

	public function getRoom() {
		if ($this->_room == false) {
			$this->_room = $this->related("room");
		}	
		return $this->_room;
	}
	
	public function getRoomNumber() {
		$number = $this->getRoom()->number;
		return ($number != '') ? ($number) : "(None)";
	}

	public static function getPatientsSentToHospital($dateStart = false, $dateEnd = false, $facility = false, $orderby = false) {
		$sql = "SELECT `schedule_hospital`.*, `patient_admit`.`id` AS 'patient_admit', `patient_admit`.`last_name`, `patient_admit`.`first_name`, `schedule`.`datetime_admit` FROM `schedule_hospital`,`schedule`,`patient_admit` WHERE `schedule_hospital`.`schedule`=`schedule`.`id` AND `schedule`.`patient_admit`=`patient_admit`.`id`";

		if ($dateStart != false) {
			$dateStart = date("Y-m-d", strtotime($dateStart)) . " 00:00:00";
		}
		if ($dateEnd != false) {
			$dateEnd = date("Y-m-d", strtotime($dateEnd)) . " 23:59:59";
		}
		if( $dateStart != false && $dateEnd == false) {
			$sql .= " and datetime_sent>=:dateStart";
			$params[":dateStart"] = $dateStart;
		}
		elseif ($dateStart == false && $dateEnd != false) {
			$sql .= " and datetime_sent<=:dateEnd";
			$params[":dateEnd"] = $dateEnd;
		}
		elseif ($dateStart != false && $dateEnd != false) {
			$sql .= " and datetime_sent>=:dateStart && datetime_sent<=:dateEnd";
			$params[":dateStart"] = $dateStart;
			$params[":dateEnd"] = $dateEnd;
		} else {
			$sql .= " and datetime_sent >= '1978-02-17 00:00:01";
		}
		if ($facility != false) {
			$sql .= " AND `schedule`.`facility`=:facility";
			$params[":facility"] = $facility;
		}
		
		$sql .= " AND `schedule_hospital`.`scheduled_visit`= 0 AND `schedule_hospital`.`was_admitted`=1 ";
		if ($orderby == false) {
			$sql .= " order by datetime_sent DESC";
		} else {
			$sql .= " order by {$orderby}";
		}
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);

	}


	public static function assignRoom($schedule_id = false, $facility_id = false, $room = false, $datetime_admit = false, $approved = false) {
		$schedule = new CMS_Schedule($schedule_id);
		$facility = new CMS_Facility($facility_id);
		$room = new CMS_Room($room);

		$schedule->room = $room->id;
		$schedule->facility = $facility->id;
		$schedule->datetime_admit = date('Y-m-d g:i:s', strtotime($datetime_admit));
		if ($status == 1) {
			$schedule->status = "Approved";
		} else {
			$schedule->status = "Under Consideration";
		}

		try {
			$schedule->save();
			return array(true);
		} catch (Exception $e) {
			return array(false, array("Could not save room assignment."));
		}
	}

	
	// public static function setFacilityAndRoom($s, $f, $r, $pr, $d, $a) {
	// 	$schedule = new CMS_Schedule($s);
	// 	$facility = new CMS_Facility($f);
	// 	$room = new CMS_Room($r);
				
	// 	// validate
	// 	if ($schedule->valid() == false) {
	// 		$msg[] = "Scheduling record not found.";
	// 	}
	// 	if ($facility->valid() == false) {
	// 		$msg[] = "Invalid facility record.";
	// 	}
	// 	if ($room->valid() == false) {
	// 		$msg[] = "Invalid room record.";
	// 	}
		
		
	// 	/*
	// 	 * Check if the room to which the patient is being transferred is currently occupied.  If true then
	// 	 * the patient in the new room needs to be moved to the room from which the patient is being transferred.
	// 	 *	
	// 	 */
	// 	//if (! $room->isEmpty($schedule->datetime_admit)) {
	// 	//	$msg[] = "Room {$r->number} is not available for " . datetime_format($schedule->datetime_admit);
	// 	//}
		
		
	// 	$occupied = $schedule->checkAvailability(date('Y-m-d H:i:s', strtotime('now')), $room->id, $facility->id);
	// 	$current_occupant = $occupied[0];
						
	// 	if (!empty ($occupied)) {
	// 		// Need to transfer this patient to the room the transfer patient is coming from
	// 		$current_occupant->room = $pr;
	// 		$current_occupant->datetime_room_transfer = datetime(strtotime($d));
	// 		$current_occupant->previous_room = $room->id;
			
	// 		try {
	// 			$current_occupant->save();
	// 		} catch (Exception $e) {
	// 			return array(false, array("Unable to switch the occupant of the transfer to room to the transfer from room."));
	// 		}			
	// 	}
		
	// 	if (count($msg) == 0) {
	// 		$schedule->room = $room->id;
	// 		$schedule->facility = $facility->id;
	// 		if ($a == 1) {
	// 			$schedule->datetime_admit = datetime(strtotime($d));
	// 		} else {
	// 			$schedule->datetime_room_transfer = datetime(strtotime($d));
	// 			$schedule->previous_room = $pr;
	// 		}
			
	// 		try {
	// 			$schedule->save();
	// 			return array(true);
	// 		} catch (Exception $e) {
	// 			return array(false, array("Unknown error while saving room assignment."));
	// 		}
	// 	} else {
	// 		return array(false, $msg);
	// 	}
	// }
	
	
	
	
	public function checkAvailability($datetime, $room, $facility) {
		$sql = "select `schedule`.* from `schedule` inner join `room` on `room`.`id` = `schedule`.`room` where :datetime >= `schedule`.`datetime_admit` and (:datetime < `schedule`.`datetime_discharge` or `datetime_discharge` IS NULL)  and `room`.`id`=:roomid  and `schedule`.`facility` = :facility and `schedule`.`status` != 'Discharged' order by `schedule`.`datetime_admit` desc limit 1";
		$params[":datetime"] = $datetime;
		$params[":roomid"] = $room;
		$params[":facility"] = $facility;
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	
	
	
	
	public function readyForDischarge() {
		if ($this->status == 'Under Consideration') {
			return false;
		}
		if ($this->status == 'Cancelled') {
			return false;
		}
	}
	
	
	
	
	
	
	public static function fetchAdmits($dateStart = false, $dateEnd = false, $status = false, $facilities = false, $orderby = false) {
		
		
		if ($facilities != false) {
			$sql .= "select `schedule`.*, `patient_admit`.`hospital_id`, `patient_admit`.`admit_from`, `patient_admit`.`case_manager_id`, `patient_admit`.`datetime_pickup`, `patient_admit`.`other_diagnosis`, `patient_admit`.`paymethod`, `facility`.`name` as `facility_name`, `patient_admit`.`referral`,  `patient_admit`.`notes_file0` from `schedule` inner join `facility` on `schedule`.`facility`=`facility`.`id` inner join `patient_admit` on `patient_admit`.`id`=`schedule`.`patient_admit`";
		} else {
			$sql = "select * from `schedule`";
		}
		
		$params = array();
		
		
		if ($dateStart != false) {
			$dateStart = date("Y-m-d", strtotime($dateStart)) . " 00:00:00";
		}
		if ($dateEnd != false) {
			$dateEnd = date("Y-m-d", strtotime($dateEnd)) . " 23:59:59";
		}
		
		
		if ($dateStart != false && $dateEnd == false) {
			$sql .= " where datetime_admit >= :dateStart";
			$params[":dateStart"] = $dateStart;
		} elseif ($dateStart == false && $dateEnd != false) {
			$sql .= " where datetime_admit <= :dateEnd";
			$params[":dateEnd"] = $dateEnd;
		} elseif ($dateStart != false && $dateEnd != false) {
			$sql .= " where datetime_admit >= :dateStart && datetime_admit <= :dateEnd";
			$params[":dateStart"] = $dateStart;
			$params[":dateEnd"] = $dateEnd;
		} else {
			$sql .= " where datetime_admit >= '1970-01-01 00:00:01'";
		}

		
		if ($status !== false) {
			$sql .= " and status=:status";
			$params[":status"] = $status;
		} else {
			$sql .= " and status != 'Cancelled'";
		}
		
		if ($facilities != false) {
			$sql .= " and (";
			foreach ($facilities as $idx => $f) {
				$sql .= " `schedule`.`facility`=:facility{$idx} OR";
				$params[":facility{$idx}"] = $f->id;
			}
			$sql = rtrim($sql, " OR");
			$sql .= ")";
		}
		
		if ($orderby == false) {
			$sql .= " order by schedule.admit_order ASC, datetime_admit ASC, ";
		} else {
			$sql .= " order by schedule.admit_order ASC, {$orderby}";
		}
								
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
		
	public static function fetchPendingAdmits($dateStart = false, $dateEnd = false, $status = false, $facilities = false, $orderby = false) {	
		
		if ($facilities != false) {
			$sql .= "select `schedule`.*, `patient_admit`.`hospital_id`, `patient_admit`.`admit_from`, `patient_admit`.`physician_id`, `patient_admit`.`referral`, `facility`.`name` as `facility_name` from `schedule` inner join `facility` on `schedule`.`facility`=`facility`.`id`
				inner join `patient_admit` on `patient_admit`.`id`=`schedule`.`patient_admit`";
		} else {
			$sql = "select * from `schedule`";
		}
		
		$params = array();
		
		
		if ($dateStart != false) {
			$dateStart = date("Y-m-d", strtotime($dateStart)) . " 00:00:00";
		}
		if ($dateEnd != false) {
			$dateEnd = date("Y-m-d", strtotime($dateEnd)) . " 23:59:59";
		}
		
		
		if ($dateStart != false && $dateEnd == false) {
			$sql .= " where datetime_admit >= :dateStart";
			$params[":dateStart"] = $dateStart;
		} elseif ($dateStart == false && $dateEnd != false) {
			$sql .= " where datetime_admit <= :dateEnd";
			$params[":dateEnd"] = $dateEnd;
		} elseif ($dateStart != false && $dateEnd != false) {
			$sql .= " where datetime_admit >= :dateStart && datetime_admit <= :dateEnd";
			$params[":dateStart"] = $dateStart;
			$params[":dateEnd"] = $dateEnd;
		} else {
			$sql .= " where datetime_admit >= '1970-01-01 00:00:01'";
		}

		
		if ($status !== false) {
			$sql .= " and status=:status";
			$params[":status"] = $status;
		} else {
			$sql .= " and status != 'Cancelled'";
		}
				
		if ($facilities != false) {
			$sql .= " and (";
			foreach ($facilities as $idx => $f) {
				$sql .= " `schedule`.facility=:facility{$idx} OR";
				$params[":facility{$idx}"] = $f->id;
			}
			$sql = rtrim($sql, " OR");
			$sql .= ")";
		}
				
		if ($orderby == false) {
			$sql .= " order by datetime_admit ASC";
		} else {
			$sql .= " order by {$orderby}";
		}
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}

	public static function fetchDischarges($dateStart = false, $dateEnd = false, $facilities = false, $orderby = false) {
		$dateStart = date("Y-m-d", strtotime($dateStart)) . " 00:00:00";
		$dateEnd = date("Y-m-d", strtotime($dateEnd)) . " 23:59:59";
		$sql = "select * from `" . static::$table . "` where (status='Approved' || status = 'Discharged')
		and (
			(datetime_discharge >= :dateStart AND datetime_discharge <= :dateEnd)
			or
			(discharge_to='Discharge to Hospital (Bed Hold)' and datetime_discharge_bedhold_end between :dateStart and :dateEnd)
			)";
		// default to this week
		if ($dateStart == false || $dateEnd == false) {
			$week = Calendar::thisWeek();
			$dateStart = $week[0];		// Sunday of this week
			$dateEnd = $week[6];		// Saturday	of this week
		}
		$params = array(
			":dateStart" => $dateStart,
			":dateEnd" => $dateEnd
		);
		
		if ($facilities != false) {
			$sql .= " and (";
			foreach ($facilities as $idx => $f) {
				$sql .= " facility=:facility{$idx} OR";
				$params[":facility{$idx}"] = $f->id;
			}
			$sql = rtrim($sql, " OR");
			$sql .= ")";
		}
				
		if ($orderby == false) {
			$sql .= " order by datetime_discharge ASC";
		} else {
			$sql .= " order by {$orderby}";
		}
		
		$obj = static::generate();
		$results = $obj->fetchCustom($sql, $params);
		return $results;

	}	
	
	
	public static function fetchCurrentDischarges($facility_id) {
		$params[":facilityid"] = $facility_id;
		
		// The datetime_discharge is hard coded in because after this datetime all completed discharges will get a status
		// of "Discharged"; we will want all others to appear in this list
		$sql = "SELECT 
			`patient_admit`.`first_name`, 
			`patient_admit`.`last_name`, 
			`room`.`number`, 
			`schedule`.`pubid` AS schedule_id,
			`schedule`.`datetime_admit`, 
			`schedule`.`datetime_discharge` 
		FROM `schedule` 
			INNER JOIN `patient_admit`
				ON `patient_admit`.`id` = `schedule`.`patient_admit`
			INNER JOIN `room`
				ON `room`.`id` = `schedule`.`room`
		WHERE `schedule`.`facility` = :facilityid
		AND `schedule`.`status` = 'Approved'
		AND `schedule`.`datetime_discharge` >= '2013-07-16 00:00:01'
		ORDER BY `schedule`.`datetime_discharge` ASC";
					
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
		
	}
		
	public function getTransferSchedule() {
		if ($this->discharge_to == 'Transfer to another AHC facility' && $this->discharge_transfer_schedule != '') {
			$schedule = new CMS_Schedule($this->discharge_transfer_schedule);
			return $schedule;
		}
		return false;
	}

	public static function fetchSent($dateStart = false, $dateEnd = false, $facilities = false, $orderby = false) {
		$dateStart = date("Y-m-d", strtotime($dateStart)) . " 00:00:00";
		$dateEnd = date("Y-m-d", strtotime($dateEnd)) . " 23:59:59";
		$sql = "select *,`schedule`.`pubid` from `patient_admit`,`schedule` 
				inner join `schedule_hospital` on `schedule_hospital`.`schedule`=`schedule`.`id`
				where `schedule`.`patient_admit`=`patient_admit`.`id`
				and `schedule_hospital`.`is_complete` != 1";
		// default to this week
		if ($dateStart == false || $dateEnd == false) {
			$week = Calendar::thisWeek();
			$dateStart = $week[0];		// Sunday of this week
			$dateEnd = $week[6];		// Saturday	of this week
		}
		$sql .= " and schedule_hospital.datetime_sent >= :dateStart && schedule_hospital.datetime_sent <= :dateEnd";
		$params = array(
			":dateStart" => $dateStart,
			":dateEnd" => $dateEnd
		);
		
		if ($facilities != false) {
			$sql .= " and (";
			foreach ($facilities as $idx => $f) {
				$sql .= " schedule.facility=:facility{$idx} OR";
				$params[":facility{$idx}"] = $f->id;
			}
			$sql = rtrim($sql, " OR");
			$sql .= ")";
		}
				
		if ($orderby == false) {
			$sql .= " order by datetime_sent ASC";
		} else {
			$sql .= " order by {$orderby}";
		}
		
		$obj = static::generate();
		$results = $obj->fetchCustom($sql, $params);
		return $results;
	}
	
	public static function fetchAdmitsByFacility($date_start = false, $date_end = false, $facility_id = false, $filterby = false, $viewby = false, $orderby = false) {
					
		if ($date_start != '') {
			$params[":date_start"] = $date_start;
		}
		if ($date_end != '') {
			$params[":date_end"] = $date_end;
		}
		if ($facility_id != '') {
			$params[":facility_id"] = $facility_id;
		}
		if ($filterby == "surgeon") {
			$filterby = "ortho";
		}

		
		$sql = "SELECT patient_admit.id, patient_admit.last_name, patient_admit.first_name, pcp.last_name AS pcp_last, pcp.first_name AS pcp_first, room.number, hospital.name AS hospital_name, physician.last_name AS physician_last, physician.first_name AS physician_first, surgeon.last_name AS surgeon_last, surgeon.first_name AS surgeon_first, schedule.datetime_admit, case_manager.last_name AS cm_last, case_manager.first_name AS cm_first FROM patient_admit LEFT JOIN hospital ON patient_admit.hospital_id=hospital.id LEFT JOIN physician AS pcp ON patient_admit.doctor_id = pcp.id LEFT JOIN physician ON patient_admit.physician_id=physician.id LEFT JOIN physician AS surgeon ON patient_admit.ortho_id = surgeon.id INNER JOIN schedule ON patient_admit.id = schedule.patient_admit INNER JOIN room ON schedule.room=room.id LEFT JOIN case_manager on case_manager.id = patient_admit.case_manager_id WHERE schedule.datetime_admit >= :date_start AND schedule.datetime_admit <= :date_end AND schedule.facility = :facility_id AND (schedule.status = 'Approved' OR schedule.status = 'Discharged')";
		
		if ($filterby) {
			if ($viewby) {
				if ($filterby == "hospital") {
					$sql .= " AND hospital.id = :viewby";
				} elseif ($filterby == "ortho") {
					$sql .= " AND surgeon.id = :viewby";
				} elseif ($filterby == "case_manager") {
					$sql .= " AND case_manager.id = :viewby";
				} elseif ($filterby == "pcp") {
					$sql .= " AND pcp.id = :viewby";
				} elseif ($filterby == "physician") {
					$sql .= " AND physician.id = :viewby";
				} elseif ($filterby == "zip_code") {
					$sql .= " AND patient_admit.zip = :viewby";
				}
				$params[':viewby'] = $viewby;
			} else {
			
				if ($filterby == "hospital" ) {
					$sql .= " ORDER BY hospital.name ASC";
				}
				
				if ($filterby == "physician") {
					$sql .= " ORDER BY physician.last_name ASC";
				}

				if ($filterby == "pcp") {
					$sql .= " ORDER BY pcp.last_name ASC";
				}
				
				if ($filterby == "case_manager") {
					$sql .= " ORDER BY case_manager.last_name ASC";
				}
				
				if ($filterby == "ortho") {
					$sql .= " ORDER BY surgeon.last_name ASC";
				}
			}
		}
		
		if ($orderby == 'room') {
			$sql .= " order by room.number asc";
		} elseif ($orderby == 'name') {
			$sql .= " order by patient_admit.last_name asc";
		} elseif ($orderby == 'admit_date') {
			$sql .= " order by schedule.datetime_admit asc";
		} elseif ($orderby == 'hospital') {
			$sql .= " order by hospital.name asc";
		} elseif ($orderby == 'physician') {
			$sql .= " order by physician.last_name asc";
		} elseif ($orderby == 'surgeon') {
			$sql .= " order by surgeon.last_name asc";
		} elseif ($orderby == 'case_manager') {
			$sql.= " order by case_manager.last_name asc";
		}
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);		
	}
	
	
	
	public function getAdmitFromName($filterby = false, $viewby = false) {
		
		if ($filterby == "surgeon") {
			$filterby = "physician";
		}
		$sql = "select * from {$filterby} where {$filterby}.id = {$viewby}";
		
/*
		if ($orderby) {
			$sql .= " orderby {$orderby}";
		}
*/
		
		$obj = static::generate();
		return $obj->fetchCustom($sql);		
		
	}
	
	public static function fetchAdmitsByZip($date_start = false, $date_end = false, $facility_id = false, $filterby = false, $viewby = false, $orderby = false) {
		if ($date_start != '') {
			$params[":date_start"] = $date_start;
		}
		if ($date_end != '') {
			$params[":date_end"] = $date_end;
		}
		if ($facility_id != '') {
			$params[":facility_id"] = $facility_id;
		}
		if ($viewby != '') {
			$params[":viewby"] = $viewby;
		}
		if ($filterby == "surgeon") {
			$filterby = "ortho";
		}
		
		$sql = "SELECT patient_admit.id, patient_admit.last_name, patient_admit.first_name, patient_admit.address, patient_admit.city, patient_admit.state, patient_admit.zip, room.number, hospital.name AS hospital_name, physician.last_name AS physician_last, physician.first_name AS physician_first, surgeon.last_name AS surgeon_last, surgeon.first_name AS surgeon_first, schedule.datetime_admit, case_manager.last_name AS cm_last, case_manager.first_name AS cm_first FROM patient_admit LEFT JOIN hospital ON patient_admit.hospital_id=hospital.id LEFT JOIN physician ON patient_admit.physician_id=physician.id LEFT JOIN physician AS surgeon ON patient_admit.ortho_id = surgeon.id INNER JOIN schedule ON patient_admit.id = schedule.patient_admit INNER JOIN room ON schedule.room=room.id LEFT JOIN case_manager on case_manager.id = patient_admit.case_manager_id WHERE schedule.datetime_admit >= :date_start AND schedule.datetime_admit <= :date_end AND schedule.facility = :facility_id AND (schedule.status = 'Approved' OR schedule.status = 'Discharged') and patient_admit.zip = :viewby";
		
				if ($orderby == 'room') {
			$sql .= " order by room.number asc";
		} elseif ($orderby == 'name') {
			$sql .= " order by patient_admit.last_name asc";
		} elseif ($orderby == 'admit_date') {
			$sql .= " order by schedule.datetime_admit asc";
		} elseif ($orderby == 'hospital') {
			$sql .= " order by hospital.name asc";
		} elseif ($orderby == 'physician') {
			$sql .= " order by physician.last_name asc";
		} elseif ($orderby == 'surgeon') {
			$sql .= " order by surgeon.last_name asc";
		} elseif ($orderby == 'case_manager') {
			$sql.= " order by case_manager.last_name asc";
		}
				
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);		

	}

	
	public static function fetchFilterData($date_start = false, $date_end = false, $facility_id = false, $filterby = false) {
		$params = array(
			':date_start' => $date_start,
			':date_end' => $date_end,
			':facility_id' => $facility_id
		);
						
		if ($filterby == "hospital") {
			$sql = "SELECT hospital.id, hospital.name";
		} elseif ($filterby == "case_manager") {
			$sql = "SELECT case_manager.id, case_manager.last_name, case_manager.first_name";
		} else {
			$sql = "SELECT physician.id, physician.last_name, physician.first_name";
		}
		
		$sql .= " FROM patient_admit";
		
		if ($filterby == "hospital") {
			$sql .= " LEFT JOIN hospital ON patient_admit.hospital_id = hospital.id AND patient_admit.hospital_id IS NOT NULL";
		} elseif ($filterby == "case_manager") {
			$sql .= " LEFT JOIN case_manager ON patient_admit.{$filterby}_id = case_manager.id";
		} else {
			if ($filterby == "surgeon") {
				$filterby = "ortho";
			} elseif ($filterby == "pcp") {
				$filterby = "doctor";
			}
			$sql .= " LEFT JOIN physician ON patient_admit.{$filterby}_id = physician.id";
		} 
				
		$sql .= " INNER JOIN schedule ON patient_admit.id = schedule.patient_admit WHERE schedule.datetime_admit >= :date_start AND schedule.datetime_admit <= :date_end AND schedule.facility = :facility_id AND (schedule.status = 'Approved' OR schedule.status = 'Discharged' OR schedule.status = 'Transferred')";
		
		if ($filterby == "hospital") {
			$sql .= " GROUP BY hospital.id ORDER BY hospital.name ASC";
		} elseif ($filterby == "case_manager") {
			$sql .= " AND case_manager.id IS NOT NULL GROUP BY case_manager.id ORDER BY case_manager.last_name ASC";	
		} else {
			$sql .= " AND physician.id IS NOT NULL GROUP BY physician.id ORDER BY physician.last_name ASC";
		}
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	public static function fetchInfoByZip($date_start = false, $date_end = false, $facility_id = false) {
		$params = array(
			':date_start' => $date_start,
			':date_end' => $date_end,
			':facility_id' => $facility_id
		);
		
		$sql = "select count(schedule.id) as count, patient_admit.zip from schedule inner join patient_admit on patient_admit.id = schedule.patient_admit where schedule.datetime_admit >= :date_start and schedule.datetime_admit <= :date_end and schedule.facility = :facility_id and (schedule.status = 'Approved' OR schedule.status = 'Discharged') group by patient_admit.zip order by count desc";
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	public static function fetchDataForFilter($dateStart = false, $dateEnd = false, $facility = false, $patientStatus = false, $filterby = false, $viewby = false) {
		if ($filterby == 'ortho') {
			$table = "physician";
			$column = "ortho_id";
		} elseif ($filterby == 'discharge_disposition' || $filterby == 'service_disposition') {
			$table = "schedule";	
		} else  {
			$table = $filterby;
			$column = $filterby . "_id";
		}
		
		$sql = "SELECT * FROM `{$table}`";
		
		if ($filterby != "discharge_disposition" && $filterby != "service_disposition") {
			$sql .= " inner join `patient_admit` on `patient_admit`.`{$column}`=`{$table}`.`id`";
			$sql .= " inner join `schedule` on `schedule`.`patient_admit`=`patient_admit`.`id`";
		} 
					
		$params = array();

		if ($dateStart != '') {
			$dateStart = date("Y-m-d G:i:s", strtotime($dateStart) . " 00:00:00");
		}
		if ($dateEnd != '') {
			$dateEnd = date("Y-m-d G:i:s", strtotime($dateEnd) . " 23:59:59");
		}

		if ($dateStart != false && $dateEnd == false) {
			$sql .= " where `schedule`.`{$patientStatus}` >= :dateStart";
			$params[":dateStart"] = $dateStart;
		} elseif ($dateStart == false && $dateEnd != false) {
			$sql .= "where `schedule`.`{$patientStatus}` <= :dateEnd";
			$params[":dateEnd"] = $dateEnd;
		} elseif ($dateStart != false && $dateEnd != false) {
			$sql .= " where `schedule`.`{$patientStatus}` >= :dateStart AND `schedule`.`{$patientStatus}` <= :dateEnd";
			$params[":dateStart"] = $dateStart;
			$params[":dateEnd"] = $dateEnd;
		} else {
			$sql .= " where `schedule`.`{$patientStatus}` >= '1970-01-01 00:00:01'";
		}
		
		if ($filterby != 'discharge_disposition' && $filterby != 'service_disposition') {
			$sql .= " AND `schedule`.`status` != 'Cancelled'";
		}
		
		if ($facility != false) {
			$sql .= " AND `schedule`.`facility`=:facility";
			$params[":facility"] = $facility;
		}
		if ($filterby == 'discharge_disposition' || $filterby == 'service_disposition') {
			$sql .= " GROUP BY `schedule`.`{$filterby}`";
		} else {
			$sql .= " GROUP BY `{$column}`";
		}
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	
	public static function countDischargesByFacility($dateStart = false, $dateEnd = false, $facility = false) {
		$params = array(
			":date_start" => $dateStart,
			":date_end" => $dateEnd,
			":facility" => $facility
		);
		
		$sql = "SELECT `schedule`.`discharge_to` AS 'dc_to',
				(SELECT count(*) FROM `schedule` WHERE `schedule`.`facility` = :facility AND `schedule`.`datetime_discharge` >= :date_start AND `schedule`.`datetime_discharge` <= :date_end) AS 'dc_count',
				(SELECT count(*) FROM `schedule` WHERE `schedule`.`facility` = :facility AND `schedule`.`datetime_discharge` >= :date_start AND `schedule`.`datetime_discharge` <= :date_end AND `schedule`.`discharge_to` = `dc_to`) AS 'dc_to_count',
				(SELECT count(*) FROM `schedule` WHERE `schedule`.`facility` = :facility AND `schedule`.`datetime_discharge` >= :date_start AND `schedule`.`datetime_discharge` <= :date_end AND schedule.discharge_to IS NULL) AS 'empty_dc_count',

				`schedule`.`discharge_disposition` AS 'dc_disp',
				(SELECT count(*) FROM `schedule` WHERE `schedule`.`facility` = :facility AND `discharge_disposition` = `discharge_disposition` AND `schedule`.`datetime_discharge` >= :date_start AND `schedule`.`datetime_discharge` <= :date_end AND `schedule`.`discharge_to` = `dc_to` AND `schedule`.`discharge_disposition` = `dc_disp`) AS 'dc_disp_count'
			FROM `schedule` WHERE `schedule`.`facility` = :facility AND `schedule`.`datetime_discharge` >= :date_start AND `schedule`.`datetime_discharge` <= :date_end GROUP BY `schedule`.`discharge_to`,  `schedule`.`discharge_disposition`";
		
		//$sql = "SELECT count(`schedule`.`id`) AS 'dischargeCount', `schedule`.`discharge_to`, `schedule`.`discharge_disposition` FROM `schedule` WHERE `schedule`.`datetime_discharge` >= :date_start AND `schedule`.`datetime_discharge` <= :date_end AND `schedule`.`facility` = :facility GROUP BY `schedule`.`discharge_to` ORDER BY 'dischargeCount'";
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	
	
	public static function fetchDischargesByFacility($dateStart = false, $dateEnd = false, $facility = false, $discharge_to = false, $dc_disp = false, $filterby = false, $orderby = false, $viewby = false, $status = false) {
		$table = "schedule";
		
		$params = array(
			":date_start" => $dateStart,
			":date_end" => $dateEnd,
			":facility" => $facility
		);
		
		$sql = "SELECT ";
		
		if ($discharge_to == false) {
			$sql .= " count(`schedule`.`id`) AS 'dc_count',";
		}
		
		$sql .= " `schedule`.`discharge_to` ";
		
		if ($discharge_to != false) {
			
			$params[":discharge_to"] = $discharge_to;
			
			$sql .= ", `schedule`.`pubid` AS 'schedule_id', `schedule`.`discharge_disposition`, `schedule`.`service_disposition`, `schedule`.`discharge_location_id`, `schedule`.`transfer_from_facility` AS 'facility_name', `hospital`.`name` AS 'hospital_name', `patient_admit`.`last_name`, `patient_admit`.`first_name` FROM `schedule` LEFT JOIN `hospital` ON `hospital`.`id`=`schedule`.`discharge_location_id` LEFT JOIN `facility` ON `facility`.`id`=`schedule`.`transfer_from_facility` INNER JOIN `patient_admit` ON `patient_admit`.`id`=`schedule`.`patient_admit` WHERE `schedule`.`discharge_to` = :discharge_to AND";
		} else {
			$sql .= " FROM `schedule` WHERE ";
		}
		
		$sql .= " `schedule`.`datetime_discharge` >= :date_start AND `schedule`.`datetime_discharge` <= :date_end AND `schedule`.`facility` = :facility";
		
		if ($dc_disp != false) {
			$params[":dc_disp"] = $dc_disp;
			$sql .= " AND `schedule`.`discharge_disposition` = :dc_disp";
		}
		
		if ($filterby != false && $filterby != "All Results") {
			$params[":filterby"] = $filterby;
			$sql .= " AND `schedule`.`service_disposition` = :filterby";
		}
		
		if ($discharge_to == false) {
			$sql .= " GROUP BY `schedule`.`discharge_to` ORDER BY 'dischargeCount'";
		} elseif ($orderby != false) {
			//$params[":orderby"] = $orderby;
			$sql .= " ORDER BY ({$orderby} IS NULL), {$orderby} ASC";
		} else {
			$sql .= " ORDER BY `last_name` ASC";
		}
				
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	
	
	/**
	 * Changed when initial discharge report format was changed on 2013-02-27 by kwh
	 */

/*
	public static function fetchDischargesByFacility($dateStart = false, $dateEnd = false, $status = false, $facility = false, $orderby = false, $filterby = false, $viewby = false) {
	
		$table = "schedule";
		$column = $filterby;	
		
		$sql = "select `patient_admit`.`id`, `patient_admit`.`pubid` as PatientPubId, `patient_admit`.`first_name`, `patient_admit`.`last_name`, `patient_admit`.`physician_id`, `patient_admit`.`doctor_id`, `patient_admit`.`physician_name`, `patient_admit`.`surgeon_name`, `schedule`.`pubid` as SchedulePubid, `schedule`.`facility`, `schedule`.`datetime_admit`, `schedule`.`datetime_first_seen`, `schedule`.`first_seen_by_id`, `schedule`.`datetime_discharge`, `schedule`.`discharge_to`, `schedule`.`discharge_disposition`, `schedule`.`service_disposition`, `schedule`.`discharge_location_id` from `schedule`, `patient_admit`";
		
		if ($filterby == 'physician') {
			$sql .= " inner join `physician` on `physician`.`id`=`patient_admit`.`physician_id`";
		}
		$sql .= " where `schedule`.`patient_admit`=`patient_admit`.`id`";
		
		
		$params = array();

		if ($dateStart != '') {
			$dateStart = date("Y-m-d G:i:s", strtotime($dateStart) . " 00:00:00");
		}
		if ($dateEnd != '') {
			$dateEnd = date("Y-m-d G:i:s", strtotime($dateEnd) . " 23:59:59");
		}

		if ($dateStart != false && $dateEnd == false) {
			$sql .= " and datetime_discharge >= :dateStart";
			$params[":dateStart"] = $dateStart;
		} elseif ($dateStart == false && $dateEnd != false) {
			$sql .= "and datetime_discharge <= :dateEnd";
			$params[":dateEnd"] = $dateEnd;
		} elseif ($dateStart != false && $dateEnd != false) {
			$sql .= " and datetime_discharge >= :dateStart && datetime_discharge <= :dateEnd";
			$params[":dateStart"] = $dateStart;
			$params[":dateEnd"] = $dateEnd;
		} else {
			$sql .= " and datetime_discharge >= '1970-01-01 00:00:01'";
		}

		if ($status != false) {
			$sql .= " AND status=:status";
			$params[":status"] = $status;
		} else {
			$sql .= " AND status != 'Cancelled'";
		}

		if ($facility != false) {
			$sql .= " AND schedule.facility=:facility";
			$params[":facility"] = $facility;
		}
		
		if ($filterby != '' && $filterby != 'physician') {
			$sql .= " AND `schedule`.`{$column}` = :viewby";
			$params[":viewby"] = $viewby;
		} elseif ($filterby == 'physician') {
			$sql .= " AND `patient_admit`.`physician_id` = :viewby";
			$params[":viewby"] = $viewby;
		}
		
		if ($orderby == false) {
			$sql .= " ORDER BY room ASC";
		} else {
			$sql .= " ORDER BY {$orderby}";
		}
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);

	}
*/



	public function fetchHHDischargesByFacility($dateStart = false, $dateEnd = false, $facility = false) {
		$table = "schedule";
		
		$params = array(
			":date_start" => $dateStart,
			":date_end" => $dateEnd,
			":facility" => $facility
		);
		
		$sql = "select 
			count(schedule.id) as discharges, 
			(select count(schedule.id) from schedule where schedule.datetime_discharge >= :date_start and schedule.datetime_discharge <= :date_end and schedule.facility = :facility and schedule.service_disposition = 'AHC Home Health'
	) AS AhcHomeHealth,
			(select count(schedule.id) from schedule where schedule.datetime_discharge >= :date_start and schedule.datetime_discharge <= :date_end and schedule.facility = :facility and schedule.service_disposition = 'Other Home Health'
	) AS OtherHomeHealth,
			(select count(schedule.id) from schedule where schedule.datetime_discharge >= :date_start and schedule.datetime_discharge <= :date_end and schedule.facility = :facility and schedule.service_disposition = 'Outpatient Therapy'
	) AS OutpatientTherapy,
			(select count(schedule.id) from schedule where schedule.datetime_discharge >= :date_start and schedule.datetime_discharge <= :date_end and schedule.facility = :facility and schedule.service_disposition = 'No Services'
	) AS NoServices
			from schedule where schedule.datetime_discharge >= :date_start and schedule.datetime_discharge <= :date_end and schedule.facility = :facility and (schedule.discharge_to = 'General Discharge' OR schedule.discharge_to = 'Transfer to other facility' OR schedule.discharge_to = 'In-Patient Hospice')";
			
			
			$obj = static::generate();
			return $obj->fetchCustom($sql, $params);
		
	}
	

	
	public static function fetchLengthOfStay($view = false, $dateRange = array(), $facility = false) {
		
		
		// The filterby var contains whether to group data by month, quarter or year.  I would like to have this fetched and organized by date group in the returned array
		$params = array();
		if ($facility != false) {
			$params[":facility"] = $facility;
		}	
		
		if (!empty ($dateRange)) {
			$params[":dateStart"] = $dateRange["dateStart"];
			$params["dateEnd"] = $dateRange["dateEnd"];
		}	
		
		$obj = static::generate();
		if (!empty ($dateRange)) {
			$sql = "SELECT (SELECT count(schedule.id) FROM schedule WHERE schedule.discharge_to='General Discharge' AND datetime_discharge >= :dateStart AND datetime_discharge <= :dateEnd";
			if ($facility != false) {
				$sql .= " AND schedule.facility=:facility";
			}
			$sql .= ") AS GeneralDischarge";
			
			$sql .= ", (SELECT count(schedule.id) FROM schedule WHERE schedule.discharge_to='Transfer to another AHC facility' AND datetime_discharge >= :dateStart AND datetime_discharge <= :dateEnd";
			if ($facility != false) {
				$sql .= " AND schedule.facility=:facility";
			}
			$sql .= ") AS AhcTransfer";
			
			$sql .= ", (SELECT count(schedule.id) FROM schedule WHERE schedule.discharge_to='Transfer to other facility' AND datetime_discharge >= :dateStart AND datetime_discharge <= :dateEnd";
			if ($facility != false) {
				$sql .= " AND schedule.facility=:facility";
			}
			$sql .= ") AS OtherTransfer";
			
			$sql .= ", (SELECT count(schedule.id) FROM schedule WHERE schedule.discharge_to='Discharge to Hospital'AND datetime_discharge >= :dateStart AND datetime_discharge <= :dateEnd";
			if ($facility != false) {
				$sql .= " AND schedule.facility=:facility";
			}
			$sql .= ") AS Hospital";	
								
		}	
				
		return $obj->fetchCustom($sql, $params);
	}

	public static function fetchAllDischarges($dateStart = false, $dateEnd = false, $facility = false, $filterby = false) {
		$params = array();
		if ($dateStart != false) {
			$params[":dateStart"] = $dateStart;
		}
		if ($dateEnd != false) {
			$params[":dateEnd"] = $dateEnd;
		}
		if ($facility != false) {
			$params[":facility"] = $facility;
		}
		
		$sql = "SELECT `schedule`.`id`, `schedule`.`datetime_admit`, `schedule`.`datetime_discharge`, `schedule`.`discharge_to` FROM `schedule`";
		
		if ($filterby != false) {
			$params[":filterby"] = $filterby;
			$sql .= "INNER JOIN `patient_admit` ON `patient_admit`.`id` = `schedule`.`patient_admit` WHERE `patient_admit`.`paymethod` = :filterby AND ";
		} else {
			$sql .= " WHERE ";
		}
		
		$sql .= " `datetime_discharge` >= :dateStart AND `datetime_discharge` <= :dateEnd AND `schedule`.`facility` = :facility AND (`status` = 'Approved' OR `status` = 'Discharged')";
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}	
	
	

	public static function fetchLosDischarges($dateStart = false, $dateEnd = false, $facility = false, $filterby = false) {
		$params = array();
		if ($dateStart != false) {
			$params[":dateStart"] = $dateStart;
		}
		if ($dateEnd != false) {
			$params[":dateEnd"] = $dateEnd;
		}
		if ($facility != false) {
			$params[":facility"] = $facility;
		}
		
		$sql = "SELECT `schedule`.`id`, `schedule`.`datetime_admit`, `schedule`.`datetime_discharge`, `schedule`.`discharge_to` FROM `schedule`";
		
		if ($filterby != false) {
			$params[":filterby"] = $filterby;
			$sql .= "INNER JOIN `patient_admit` ON `patient_admit`.`id` = `schedule`.`patient_admit` WHERE `patient_admit`.`paymethod` = :filterby AND ";
		} else {
			$sql .= " WHERE ";
		}
		
		$sql .= " `datetime_discharge` >= :dateStart AND `datetime_discharge` <= :dateEnd AND `schedule`.`facility` = :facility AND (`status` = 'Approved' OR `status` = 'Discharged')";
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	public function fetchLosDetails($dischargeTo = false, $dateStart = false, $dateEnd = false, $facility = false) {
			$params = array(
			":dateStart" => $dateStart . " 00:00:01",
			":dateEnd" => $dateEnd . " 23:59:59",
			":facility" => $facility
		);
					
		$sql = "SELECT `patient_admit`.`id`, `patient_admit`.`last_name`, `patient_admit`.`first_name`, `schedule`.`datetime_admit`, `schedule`.`datetime_discharge`, `schedule`.`discharge_location_id`, `schedule`.`discharge_disposition`, `schedule`.`service_disposition`, `schedule`.`discharge_comment` FROM `patient_admit`, `schedule` WHERE `schedule`.`patient_admit` = `patient_admit`.`id` AND `schedule`.`datetime_discharge` >= :dateStart AND `schedule`.`datetime_discharge` <= :dateEnd AND `schedule`.`facility` = :facility AND";
		
		if ($dischargeTo == '') {
			$sql .= " `schedule`.`discharge_to` IS NULL";
		} else {
			$params[":dischargeTo"] = $dischargeTo;
			$sql .= "`schedule`.`discharge_to` = :dischargeTo";
		}
		
		$sql .= " AND (`schedule`.`status` = 'Approved' OR `schedule`.`status` = 'Discharged') ORDER BY `schedule`.`datetime_discharge` ASC";
		$obj = static::generate();
		
		return $obj->fetchCustom($sql, $params);
	}

	public static function fetchHospitalReadmits($patient_id, $date_start = false, $date_end = false) {
		$sql = "select datetime_admit,schedule.patient_admit from schedule where readmit_type='hospital' and schedule.patient_admit=:patient_id";
		$params[":patient_id"] = "{$patient_id}";

		if ($date_start != '' && $date_end == '') {
			$sql .= " and datetime_admit >=:dateStart";
			$params[":dateStart"] = "{$date_start}";
		}
		if ($date_start == '' && $date_end != '') {
			$sql .= " and datetime_admit<=:dateEnd";
			$params[":dateEnd"] = "{$date_end}";
		}
		if ($date_start != '' && $date_end != '') {
			$sql .= " and datetime_admit >=:dateStart && datetime_admit <=:dateEnd";
			$params[":dateStart"] = "{$date_start}";
			$params[":dateEnd"] = "{$date_end}";
		} else {
			$sql .= " and datetime_admit >= '1978-02-17 12:24:00'";
		}

		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}

	public static function fetchReadmitsByFacility($date_start = false, $date_end = false, $facility = false, $orderby = false, $readmit_type = false) {

		if ($date_start !='') {
			$dateStart = date("Y-m-d G:i:s", strtotime($date_start));
		}

		if ($date_end != '') {
			$dateEnd = date("Y-m-d G:i:s", strtotime($date_end));
		}

		$sql = "select * from patient_admit inner join schedule on patient_admit.id=schedule.patient_admit where schedule.readmit_type != ''";
		if ($date_start != '' && $date_end == '') {
			$sql .= " and datetime_admit >= :dateStart";
			$params[":dateStart"] = "{$dateStart}";
		}
		if ($date_start == '' && $date_end != '') {
			$sql .= " and datetime_admit<=:dateEnd";
			$params[":dateEnd"] = "{$dateEnd}";
		}
		if ($date_start != '' && $date_end != '') {
			$sql .= " and datetime_admit >=:dateStart && datetime_admit <=:dateEnd";
			$params[":dateStart"] = "{$dateStart}";
			$params[":dateEnd"] = "{$dateEnd}";
		} else {
			$sql .= " and datetime_admit >= '1978-02-17 12:24:00'";
		}
		if ($facility != '') {
			$sql .= " and schedule.facility = :facility";
			$params[":facility"] = "{$facility}";
		}
		if ($readmit_type != false) {
			$sql .= " and schedule.readmit_type = :readmit_type";
			$params["readmit_type"] = "{$readmit_type}";
		}
		if ($orderby == false) {
			$sql .= " ORDER BY datetime_admit DESC";
		} else {
			$sql .= " ORDER BY {$orderby}";
		}

		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	public static function getCancelledInquiries($date_start = false, $date_end = false, $facility = false, $orderby = false) {
		$sql = "select * from schedule inner join patient_admit on schedule.patient_admit=patient_admit.id left join hospital on patient_admit.hospital=hospital.id where status='Cancelled'";

		if ($dateStart != '' && $dateEnd == '') {
			$sql .= " and datetime_admit >=:dateStart";
			$params[":dateStart"] = "{$date_start}";
		}
		if ($date_start == '' && $date_end != '') {
			$sql .= " and datetime_admit<=:dateEnd";
			$params[":dateEnd"] = "{$date_end}";
		}
		if ($date_start != '' && $date_end != '') {
			$sql .= " and datetime_admit >=:dateStart && datetime_admit <=:dateEnd";
			$params[":dateStart"] = "{$date_start}";
			$params[":dateEnd"] = "{$date_end}";
		} else {
			$sql .= " and datetime_admit >= '1978-02-17 12:24:00'";
		}
		if ($facility != false && $facility != '') {
			$sql .= " and schedule.facility=:facility";
			$params[":facility"] = "{$facility}";
		}
		
		if ($orderby == false) {
			$sql .= " ORDER BY schedule.datetime_admit ASC";
		} else {
			$sql .= " ORDER BY {$orderby}";
		}

		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	public static function getScheduleByPerson($person_id, $datetime, $includeFuture = false) {
		if ($includeFuture == true) {
				$sql = "select schedule.* from `" . static::$table . "` 
				inner join `patient_admit` on `" .static::$table . "`.`patient_admit`=`patient_admit`.`id`
				where `status`!='Cancelled' 
				and `patient_admit`.`person_id`=:personid";
				$params = array(
					":personid" => $person_id
				);
		} else {
				$sql = "select schedule.* from `" . static::$table . "` 
				inner join `patient_admit` on `" .static::$table . "`.`patient_admit`=`patient_admit`.`id`
				where `status`!='Cancelled' 
				and `patient_admit`.`person_id`=:personid 
				and 
				(
					(:datetime between `datetime_admit` and `datetime_discharge` and `datetime_discharge` is not null) 
					or 
					(:datetime >= `datetime_admit` and `datetime_discharge` is null )
				)";				
				$params = array(
					":personid" => $person_id,
					":datetime" => datetime(strtotime($datetime))
				);
		}
		$obj = static::generate();

		return $obj->fetchCustom($sql, $params);
	}
	
	public function hasConflict() {
		return count($this->getConflicts()) > 0;
	}
	
	public function getConflicts() {
		// schedulings without rooms can't have conflicts
		if ($this->room == '') {
			return array();
		}
		
		$sql = "select * from `" . static::$table . "`
		where `id` != :id and `room`=:room
		and (
			(:datetime_admit between `datetime_admit` and `datetime_discharge` and `datetime_discharge` is not null) 
			";
		if ($this->datetime_discharge != '') {
			$sql .= " or 
			(:datetime_discharge between `datetime_admit` and `datetime_discharge` and `datetime_discharge` is not null)";
		}
		$sql .= ")";
		$params = array();
		$params[":id"] = $this->id;
		$params[":room"] = $this->room;
		$params[":datetime_admit"] = $this->datetime_admit;
		if ($this->datetime_discharge != '') {
			$params[":datetime_discharge"] = $this->datetime_discharge;
		}
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	public function atHospitalRecord() {
		$sql = "select schedule_hospital.* from schedule_hospital inner join schedule on schedule.id=schedule_hospital.schedule
		where schedule=:scheduleid and is_complete=0";
		
		$obj = new CMS_Schedule_Hospital;
		$params = array(
				":scheduleid" => $this->id
		);
		
		$rows = $obj->fetchCustom($sql, $params);
		if ($rows == false) {
				return false;
		}
		return current($rows);
	}
	
	public function hospitalStayHasDeterminedDischargeDate() {
		$ahr = $this->atHospitalRecord();
		if ($ahr != false) {
			if ($ahr->bedhold_offered ==1 && $ahr->datetime_bedhold_end == $this->datetime_discharge && ($ahr->datetime_bedhold_end != '' && $this->datetime_discharge != '')) {
				return true;
			}
		}
		
		return false;
	}
	
	public static function getAtHospitalRecords(CMS_Facility $facility = null, $orderbySQL = "datetime_updated DESC") {
		$sql = "select schedule.pubid, schedule_hospital.pubid as schedule_hospital_pubid, patient_admit.last_name, patient_admit.first_name, schedule.`datetime_admit`, `schedule`.`datetime_discharge`, schedule.datetime_admit, room.number, hospital.name, schedule.datetime_discharge_bedhold_end
		from schedule_hospital
		inner join `schedule` on `schedule`.`id` = schedule_hospital.schedule 
		inner join facility on schedule.facility=facility.id 
		inner join site_user on schedule_hospital.discharge_nurse=site_user.id 
		inner join patient_admit on patient_admit.id = schedule.patient_admit 
		inner join room on room.id = `schedule`.room inner join hospital on hospital.id = schedule_hospital.hospital
		";
		
		if ($facility != '' && ! is_null($facility)) {
			$sql .= " where schedule.facility=:facilityid and is_complete=0 ORDER BY {$orderbySQL}";
			$params = array(
					":facilityid" => $facility->id
			);
		} else {
			$sql .= " where is_complete=0 ORDER BY {$orderbySQL}";
			$params = array();
		}
		$obj = new CMS_Schedule_Hospital;
		// return $sql;
		return $obj->fetchCustom($sql, $params);
		
	}
	
	public static function getAtHospitalRecordsForReminder() {
		$past = datetime(strtotime("-23 hours"));
		$sql = "select schedule_hospital.* from schedule_hospital inner join schedule on schedule.id=schedule_hospital.schedule
		where is_complete=0 and (
				(datetime_last_email IS NULL and datetime_updated < '{$past}')
				or
				(datetime_last_email IS NOT NULL and datetime_last_email < '{$past}')
		)
		";
		$obj = new CMS_Schedule_Hospital;
		$params = array(
		);
		
		return $obj->fetchCustom($sql, $params);
		
	}
	
	public function hasBedhold() {
		if ($this->datetime_discharge != '' && $this->discharge_to == 'Discharge to Hospital (Bed Hold)' && $this->datetime_discharge_bedhold_end != '') {
			return true;
		}
		return false;
	}

	public static function getCodeDescriptions($code) {
		if ($code != '') {
			$sql = "select * from icd9_codes where code = :code";
			$params[":code"] = $code;
			$obj = static::generate();
			return $obj->fetchCustom($sql, $params);
		} else {
			return false;
		}
		
	}

	public static function fetchAdmitsByPersonID($person_id) {
		$sql = "select a.* from schedule a inner join patient_admit b on b.id=a.patient_admit
		where b.person_id=:personid";
		
		$params = array(":personid" => $person_id);
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	public static function fetchAdmitsByPatientID($patient_id) {
		$sql = "select a.* from schedule a inner join patient_admit b on b.id=a.patient_admit
		where b.id=:patientid";
		
		$params = array(":patientid" => $patient_id);
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	public static function getAdmitPhysicianIds($facility_id, $datetime) {
		$datetime = datetime(strtotime($datetime));
		$params = array();
		$obj = static::generate();
		$sql = "SELECT `patient_admit`.`physician_id`, `patient_admit`.`id` FROM `patient_admit`
			inner join `schedule` on `patient_admit`.`id`=`schedule`.`patient_admit`
			where `schedule`.`facility`=:facilityid
			and (`schedule`.`status`='Approved')
			and :datetime >= `datetime_admit` 
			and 
			(
				(`datetime_discharge` IS NULL)
				OR
				(
				`discharge_to`!='Discharge to Hospital (Bed Hold)' and (:datetime < `datetime_discharge`)
				)
				or
				(
				`discharge_to`='Discharge to Hospital (Bed Hold)' and :datetime < `datetime_discharge_bedhold_end`
				)
			) group by `patient_admit`.`physician_id`";
			$params[":facilityid"] = $facility_id;
			$params[":datetime"] = $datetime;
						
		$physicianIds = $obj->fetchCustom($sql, $params);
		return $physicianIds;
	}
	
	
	public static function numberOfActivePatientsByPhysician($facility_id = false, $physician_id = false, $datetime = false) {
		$obj = static::generate();
		$datetime = datetime(strtotime($datetime));
		$params = array();
		
		$sql = "select count(`patient_admit`.`id`) from `patient_admit`
				inner join `schedule` on `schedule`.`patient_admit` = `patient_admit`.`id`
				where `schedule`.`facility` = :facilityid
				and `patient_admit`.`physician_id` = :physicianid
				and `schedule`.`status` = 'Approved'";
		
		if ($datetime != false) {
			$sql .= " AND (
				(`schedule`.`datetime_discharge` IS NULL)
				OR 
				(`schedule`.`discharge_to`!='Discharge to Hospital (Bed Hold)' and (:datetime < `schedule`.`datetime_discharge`))
				OR 
				(`schedule`.`discharge_to`='Discharge to Hospital (Bed Hold)' and :datetime < `schedule`.`datetime_discharge_bedhold_end`)
			)";
			$params[":datetime"] = $datetime;
		} elseif ($datetime == false) {
			return false;
		}
		$params[":facilityid"] = $facility_id;
		$params[":physicianid"] = $physician_id;		
		
		$result = $obj->fetchCustom($sql, $params);
		return $result;

	}
	
/*
	public static function numberOfActivePatientsByPhysician($facility_id = false, $physician_id = false, $datetime = false) {
		$obj = static::generate();
		$datetime = datetime(strtotime($datetime));
		$params = array();
		$sql = "select `patient_admit`.`physician_id` from `patient_admit`
			inner join `schedule` on `schedule`.`patient_admit`=`patient_admit`.`id`
			where `schedule`.`facility`=:facilityid
			and `patient_admit`.`physician_id`=:physicianid
			and `schedule`.`status` = 'Approved'";
		if ($datetime != false) {
			$sql .= " AND (
				(`schedule`.`datetime_discharge` IS NULL)
				OR 
				(`schedule`.`discharge_to`!='Discharge to Hospital (Bed Hold)' and (:datetime < `schedule`.`datetime_discharge`))
				OR 
				(`schedule`.`discharge_to`='Discharge to Hospital (Bed Hold)' and :datetime < `schedule`.`datetime_discharge_bedhold_end`)
			)";
			$params[":datetime"] = $datetime;
		} elseif ($datetime == false) {
			return false;
		}
		$params[":facilityid"] = $facility_id;
		$params[":physicianid"] = $physician_id;
		// return $sql;
		
		$result = $obj->fetchCustom($sql, $params);
		return $result;
	}	
*/
		
	public static function getADC($facility_id, $date) {
		$obj = static::generate();
		
		$params[":facility"] = $facility_id;
		$params[":datetime"] = date('Y-m-d 23:59:59', strtotime($date));
		$params[":bedhold_end"] = date('Y-m-d 11:00:00', strtotime($date));
										
		$sql = "SELECT count(`room`.`number`) AS census
					FROM `schedule` 
					INNER JOIN `room` 
						on `room`.`id` = `schedule`.`room`
					INNER JOIN patient_admit on patient_admit.id = schedule.patient_admit
					LEFT JOIN adc on adc.facility = schedule.facility
					WHERE `schedule`.`facility` = :facility 
					AND (`schedule`.`status` = 'Approved' OR `schedule`.`status` = 'Discharged') 
					AND `schedule`.`datetime_admit` <= :datetime
					AND ((`schedule`.`datetime_discharge` >= :datetime OR `schedule`.`datetime_discharge` IS NULL OR `schedule`.`datetime_discharge` = '0000-00-00 00:00:00') OR (`schedule`.`discharge_to` = 'Discharge to Hospital (Bed Hold)' and `schedule`.`datetime_discharge_bedhold_end` > :bedhold_end))
					order by room.number asc";
					
		return $obj->fetchCustom($sql, $params);
	}	
	
	public function LoS($datetime_discharge, $datetime_admit) {

		$d = strtotime($datetime_discharge);
		$a = strtotime($datetime_admit);

		$dateDiff = abs($d - $a);
	
		return round($dateDiff/86400);
	}
	
	
	public function fetchDischargeHistory($facility_id, $start_date, $end_date) {
		$params = array(
			":facilityid" => $facility_id,
			":startdate" => $start_date,
			":enddate" => $end_date
		);
		$sql = "select 
					patient_admit.last_name,
					patient_admit.first_name,
					patient_admit.phone,
					schedule.datetime_discharge, 
					schedule.discharge_to, 
					schedule.discharge_disposition, 
					schedule.service_disposition, 
					hospital.name as hospital,
					schedule.discharge_phone
				from schedule
				inner join patient_admit on patient_admit.id = schedule.patient_admit
				left join hospital on schedule.discharge_location_id = hospital.id
				where schedule.facility = :facilityid 
					and schedule.datetime_discharge >= :startdate
					and schedule.datetime_discharge <= :enddate
					and schedule.datetime_discharge IS NOT NULL
				order by schedule.datetime_discharge asc";
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	
	public function fetchTransfers($facility= false) {
		$sql = "select 
					schedule.pubid,
					schedule.transfer_comment,
					schedule.datetime_admit,
					patient_admit.first_name,
					patient_admit.last_name,
					patient_admit.phone,
					patient_admit.emergency_contact_name1,
					patient_admit.emergency_contact_phone1,
					currentFacility.name as transfer_from,
					transferFacility.name as transfer_to
				from schedule
				inner join patient_admit on patient_admit.id = schedule.patient_admit
				inner join facility as currentFacility on currentFacility.id = schedule.transfer_from_facility
				inner join facility as transferFacility on transferFacility.id = schedule.transfer_to_facility
				where schedule.facility = :facilityid
				and schedule.status = 'Approved'
				and schedule.transfer_request IS true
				and transfer_from_facility IS NOT NULL
				order by schedule.facility, schedule.datetime_admit, patient_admit.last_name";
			
		$params[':facilityid'] = $facility;
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	
	public function fetchTransferReport($facility= false, $date_start = false, $date_end = false) {
		$sql = "select 
					schedule.pubid,
					schedule.transfer_comment,
					schedule.datetime_admit,
					patient_admit.first_name,
					patient_admit.last_name,
					patient_admit.phone,
					patient_admit.emergency_contact_name1,
					patient_admit.emergency_contact_phone1,
					transferFacility.name as transfer_from
				from schedule
				inner join patient_admit on patient_admit.id = schedule.patient_admit
				inner join facility as transferFacility on transferFacility.id = schedule.transfer_from_facility
				where schedule.facility = :facilityid
				and schedule.transfer_from_facility IS NOT NULL
				and schedule.datetime_admit >= :datestart
				and schedule.datetime_admit <= :dateend
				and schedule.status != 'Cancelled'
				order by schedule.datetime_admit, patient_admit.last_name";
			
		$params[':facilityid'] = $facility;
		$params[':datestart'] = $date_start;
		$params[':dateend'] = $date_end;
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	
	public function fetchDischargesByServiceDisposition($date_start = false, $date_end = false, $facility = false, $type = false) {
		
		$params = array(
			":date_start" => $date_start,
			":date_end" => $date_end,
			":facility" => $facility,
			":type" => $type
		);
				
		if ($type == "AHC Home Health" || $type == "No Services" || $type == "Outpatient Therapy") {
			$sql = "select `patient_admit`.`pubid`,  `patient_admit`.`last_name`,  `patient_admit`.`first_name`, `schedule`.`datetime_discharge`, `schedule`.`discharge_disposition`, `schedule`.`discharge_to`, `Facility`.`name` from `schedule` inner join `patient_admit` on `patient_admit`.`id` = `schedule`.`patient_admit` left join `hospital` as `Facility` on `Facility`.`id` = `schedule`.`discharge_location_id` where `schedule`.`datetime_discharge` >= :date_start and `schedule`.`datetime_discharge` <= :date_end and `schedule`.`facility` = :facility and `schedule`.`service_disposition` = :type order by `schedule`.`datetime_discharge` ASC";
		} else {
			$sql = "select `HomeHealth`.`pubid`, `HomeHealth`.`name`, count(*) as `count` from `schedule` left join `hospital` as `HomeHealth` on `HomeHealth`.`id` = `schedule`.`home_health_id` where `schedule`.`datetime_discharge` >= :date_start and `schedule`.`datetime_discharge` <= :date_end and `schedule`.`facility` = :facility and `schedule`.`service_disposition` = :type group by `name` order by `count` DESC";
		}
												
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
		
	}
	
	
	public function fetchDischargesByHomeHealthName($date_start = false, $date_end = false, $facility = false, $location_id = false) {
		$params = array(
			":date_start" => $date_start,
			":date_end" => $date_end,
			":facility" => $facility,
			":location_id" => $location_id
		);
		
		$sql = "select `patient_admit`.`pubid`,  `patient_admit`.`last_name`,  `patient_admit`.`first_name`, `schedule`.`datetime_discharge`, `schedule`.`discharge_disposition`, `schedule`.`discharge_to`, `HomeHealth`.`name` from `schedule` inner join `patient_admit` on `patient_admit`.`id` = `schedule`.`patient_admit` left join `hospital` as `HomeHealth` on `HomeHealth`.`id` = `schedule`.`home_health_id` where `schedule`.`datetime_discharge` >= :date_start and `schedule`.`datetime_discharge` <= :date_end and `schedule`.`facility` = :facility and `schedule`.`service_disposition` = 'Other Home Health' and `HomeHealth`.`pubid` = :location_id order by `schedule`.`datetime_discharge` ASC";
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
		
	}
	
	
	public function lastDay($date = false, $view = false) {
		if ($view == "month") {
			return date('Y-m-d', strtotime('last day of ' . $date));
		} elseif ($view == "quarter") {
			return date('Y-m-d', strtotime('last day of quarter for ' . $date));
		}
	
		
	}
	
	
	public function fetchPhoneCallReport($date_start = false, $date_end = false, $facility = false) {
		$params = array(
			":date_start" => $date_start,
			":date_end" => $date_end,
			":facility" => $facility,
		);
		
		$sql = "select `patient_admit`.`last_name`, `patient_admit`.`first_name`, `patient_admit`.`phone`, `schedule`.`datetime_discharge`, `schedule`.`home_health_id`, `patient_admit`.`other_diagnosis`, `hospital`.`name`, `schedule`.`discharge_location_id`, `schedule`.`discharge_disposition`, `schedule`.`service_disposition`, `room`.`number` from `patient_admit` inner join `hospital` on `patient_admit`.`hospital_id` = `hospital`.`id` inner join `schedule` on `patient_admit`.`id` = `schedule`.`patient_admit` inner join `room` on `room`.`id`=`schedule`.`room` where `schedule`.`datetime_discharge` >= :date_start and `schedule`.`datetime_discharge` <= :date_end and `schedule`.`facility` = :facility and `schedule`.`discharge_to` != 'Discharge to Hospital' and `schedule`.`discharge_to` != 'Discharge to Hospital (Bed Hold)' and `schedule`.`discharge_to` != 'Expired' and `schedule`.`discharge_to` != 'Transfer to other facility' order by `schedule`.`datetime_discharge` desc, `patient_admit`.`last_name`";
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
		
	}


	public function fetchAdcReport($time_period = false, $year = false, $facility = false) {
		
		$params = array(
			":time_period" => $time_period,
			":datetime_start" => $year . "-01-01 00:00:01",
			":datetime_end" => $year . "-12-31 23:59:59",
			":facility" => $facility
		);

		
		switch ($time_period) {
									
			 case "month":
				
				$sql = "select time_period, admission_count, discharge_count, census from 
				( 
					(
						select count(id) as admission_count, month(datetime_admit) as admit_period  from schedule where datetime_admit >= :datetime_start and datetime_admit <= :datetime_end and facility = :facility and (schedule.status = 'Approved' OR schedule.status = 'Discharged') group by admit_period
					) as admissions 
					INNER JOIN 
					(
					select count(id) as discharge_count, month(datetime_discharge) as discharge_period from schedule where datetime_discharge >= :datetime_start and datetime_discharge <= :datetime_end and facility = :facility and schedule.status = 'Discharged' group by discharge_period
					) as discharges ON admissions.admit_period=discharges.discharge_period
					INNER JOIN
					(
					select time_period, month(time_period) as adc_period, census_value as census, facility_id from census_data where time_period >= :datetime_start and time_period <= :datetime_end and facility_id = :facility  group by adc_period
					) as adc ON discharges.discharge_period = adc.adc_period
				)";

				break;
				
			case "quarter":
				
				$sql = "select time_period, admission_count, discharge_count, census from 
				( 
					(
						select count(id) as admission_count, quarter(datetime_admit) as admit_period  from schedule where datetime_admit >= :datetime_start and datetime_admit <= :datetime_end and facility = :facility and (schedule.status = 'Approved' OR schedule.status = 'Discharged') group by admit_period
					) as admissions 
					INNER JOIN 
					(
					select count(id) as discharge_count, quarter(datetime_discharge) as discharge_period from schedule where datetime_discharge >= :datetime_start and datetime_discharge <= :datetime_end and facility = :facility and schedule.status = 'Discharged' group by discharge_period
					) as discharges ON admissions.admit_period=discharges.discharge_period
					INNER JOIN
					(
					select time_period, quarter(time_period) as adc_period, census_value as census, facility_id from census_data where time_period >= :datetime_start and time_period <= :datetime_end and facility_id = :facility  group by adc_period
					) as adc ON discharges.discharge_period = adc.adc_period
				)";
				
				break;
				
			case "year":
				
				$sql = "select time_period, admission_count, discharge_count, census from 
				( 
					(
						select count(id) as admission_count, year(datetime_admit) as admit_period  from schedule where datetime_admit >= :datetime_start and datetime_admit <= :datetime_end and facility = :facility and (schedule.status = 'Approved' OR schedule.status = 'Discharged') group by admit_period
					) as admissions 
					INNER JOIN 
					(
					select count(id) as discharge_count, year(datetime_discharge) as discharge_period from schedule where datetime_discharge >= :datetime_start and datetime_discharge <= :datetime_end and facility = :facility and schedule.status = 'Discharged' group by discharge_period
					) as discharges ON admissions.admit_period=discharges.discharge_period
					INNER JOIN
					(
					select time_period, year(time_period) as adc_period, census_value as census, facility_id from census_data where time_period >= :datetime_start and time_period <= :datetime_end and facility_id = :facility  group by adc_period
					) as adc ON discharges.discharge_period = adc.adc_period
				)";
				
				break;
			
			
		}

					
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);

	}
	
	
	
	
}