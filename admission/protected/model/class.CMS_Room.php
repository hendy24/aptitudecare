<?php


class CMS_Room extends CMS_Table {
	
	public static $table = "room";
	protected static $metadata = array();
	
	public static $modelTitle = "AHC Facility Rooms";
	
	public function getTitle() {
		return $this->related("facility")->name . " #" . $this->number;
	}
	
	public static function fetchRooms($facility_id, $datetime, $type = false) {
		$datetime = datetime(strtotime($datetime));
		$obj = static::generate();
		
		$params = array(
			":facilityid" => $facility_id,
			":datetime" => $datetime,
		);
		
		
		$sql = "select distinct
				`room`.*,
				`patient_admit`.`pubid` as `patient_admit_pubid`,
				`patient_admit`.`physician_id`,
				`schedule`.`pubid` as `schedule_pubid`,
				`schedule`.`datetime_admit` as `datetime_admit`,
				`schedule`.`datetime_discharge` as `datetime_discharge`,
				`schedule`.`discharge_to` as `discharge_to`,
				`schedule`.`datetime_discharge_bedhold_end` as `datetime_discharge_bedhold_end`,
				`schedule`.`status` as `status`,
				`schedule`.`transfer_request`,
				`schedule_hospital`.`is_complete`,
				`schedule_hospital`.`datetime_sent`
				from `room` 
				inner join `schedule` on `schedule`.`room`=`room`.`id` 
				inner join `patient_admit` on `schedule`.`patient_admit`=`patient_admit`.`id`
				left join `schedule_hospital` on `schedule_hospital`.`schedule`=`schedule`.`id`
				where `room`.`facility`=:facilityid 
				and (`schedule`.`status`='Approved' OR `schedule`.`status`='Under Consideration' OR `schedule`.`status` = 'Discharged')
				and :datetime >= `datetime_admit` 
				and 
				(
					(`datetime_discharge` IS NULL)
					OR
					(
					`datetime_discharge` >= :datetime
					)
					OR
					(
					`discharge_to`!='Discharge to Hospital (Bed Hold)' and (:datetime < `datetime_discharge`)
					)
					or
					(
					`discharge_to`='Discharge to Hospital (Bed Hold)' and :datetime < `datetime_discharge_bedhold_end`
					)
				)";
		
		if ($type) {
			if ($type == "long_term") {
				$params[":type"] = 1;
			} else {
				$params[":type"] = 0;
			}
			
			$sql .= " and `schedule`.`long_term` = :type";
		}
		
		$sql .= " GROUP BY `id`
				ORDER BY `number`
				";		
		return $obj->fetchCustom($sql, $params);

	}
	
	
	public static function fetchEmptyByFacility($facility_id, $datetime) {
		return static::_fetchByFacility($facility_id, "empty", $datetime);
	}
	
	public static function fetchScheduledByFacility($facility_id, $datetime) {
		return static::_fetchByFacility($facility_id, "scheduled", $datetime);
	}
	
	public static function fetchDischargedByFacility($facility_id, $datetime) {
		return static::_fetchByFacility($facility_id, "discharged", $datetime);
	}
	
	
	public static function fetchScheduledDischargesByFacility($facility_id, $datetime) {
		return static::_fetchByFacility($facility_id, "scheduled_discharge", $datetime);
	}
	
	public static function fetchLongTermByFacility($facility_id, $datetime) {
		return static::_fetchByFacility($facility_id, "long_term", $datetime);
	}
	
	public static function fetchShortTermByFacility($facility_id, $datetime) {
		return static::_fetchByFacility($facility_id, "short_term", $datetime);
	}
	
	
	public static function _fetchByFacility($facility_id, $status, $datetime) {
		$datetime = datetime(strtotime($datetime));
		$obj = static::generate();
		
		switch ($status) {
			
			// 2011-09-07
			// Note that discharge_bedhold_end logic remains here so that old records don't break the schedule history.
			// Going forward, this column's value will always be NULL.  We now implement bedhold by setting
			// datetime_discharge to the bedhold-end-date via the facility/sendToHospital.
			case "empty":
				$params[":datetime"] = $datetime;
				$sql = "select * from `room` where facility=:facilityid and id not in (
					select `room`.`id` from `room` 
					inner join `schedule` on `schedule`.`room`=`room`.`id` 
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
				break;
				
			case "scheduled":
				$params[":datetime"] = $datetime;
				$sql = "select distinct
				`room`.*,
				`patient_admit`.`pubid` as `patient_admit_pubid`,
				`patient_admit`.`physician_id`,
				`schedule`.`pubid` as `schedule_pubid`,
				`schedule`.`datetime_admit` as `datetime_admit`,
				`schedule`.`datetime_discharge` as `datetime_discharge`,
				`schedule`.`discharge_to` as `discharge_to`,
				`schedule`.`datetime_discharge_bedhold_end` as `datetime_discharge_bedhold_end`,
				`schedule`.`status` as `status`,
				`schedule`.`transfer_request`,
				`schedule_hospital`.`is_complete`,
				`schedule_hospital`.`datetime_sent`
				from `room` 
				inner join `schedule` on `schedule`.`room`=`room`.`id` 
				inner join `patient_admit` on `schedule`.`patient_admit`=`patient_admit`.`id`
				left join `schedule_hospital` on `schedule_hospital`.`schedule`=`schedule`.`id`
				where `room`.`facility`=:facilityid 
				and (`schedule`.`status`='Approved' OR `schedule`.`status`='Under Consideration' OR `schedule`.`status` = 'Discharged')
				and :datetime >= `datetime_admit` 
				and 
				(
					(`datetime_discharge` IS NULL)
					OR
					(
					`datetime_discharge` >= :datetime
					)
					OR
					(
					`discharge_to`!='Discharge to Hospital (Bed Hold)' and (:datetime < `datetime_discharge`)
					)
					or
					(
					`discharge_to`='Discharge to Hospital (Bed Hold)' and :datetime < `datetime_discharge_bedhold_end`
					)
				)
				GROUP BY `id`
				ORDER BY `number`
				";
				
				break;
				
			case "discharged":
				$params[":datetime_start"] = date('Y-m-d 00:00:00', strtotime($datetime));
				$params[":datetime_end"] = date('Y-m-d 23:59:59', strtotime($datetime));
				$sql = "select distinct
				`room`.*,
				`patient_admit`.`pubid` as `patient_admit_pubid`,
				`patient_admit`.`physician_id`,
				`schedule`.`pubid` as `schedule_pubid`,
				`schedule`.`datetime_admit` as `datetime_admit`,
				`schedule`.`datetime_discharge` as `datetime_discharge`,
				`schedule`.`discharge_to` as `discharge_to`,
				`schedule`.`datetime_discharge_bedhold_end` as `datetime_discharge_bedhold_end`,
				`schedule`.`status` as `status`,
				`schedule_hospital`.`is_complete`,
				`schedule_hospital`.`datetime_sent`
				from `room` 
				inner join `schedule` on `schedule`.`room`=`room`.`id` 
				inner join `patient_admit` on `schedule`.`patient_admit`=`patient_admit`.`id`
				left join `schedule_hospital` on `schedule_hospital`.`schedule`=`schedule`.`id`
				where `room`.`facility`= :facilityid
				and (
					`schedule`.`status`='Approved'
					OR
					`schedule`.`status`='Discharged'
				)
				and datetime_discharge >= :datetime_start
				and datetime_discharge <= :datetime_end
					
				
				ORDER BY `number`";
				break;
			
			case "scheduled_discharge":
				$params[":datetime_start"] = date('Y-m-d 00:00:00', strtotime($datetime));
				$params[":datetime_end"] = date('Y-m-d 23:59:59', strtotime($datetime));
				$sql = "select distinct
				room.*,
				patient_admit.pubid as patient_admit_pubid,
				patient_admit.last_name,
				patient_admit.first_name,
				schedule.datetime_admit,
				schedule.datetime_discharge
				from room
				inner join schedule on schedule.room = room.id
				inner join patient_admit on patient_admit.id = schedule.patient_admit
				where room.facility = :facilityid
				and schedule.datetime_discharge >= :datetime_start
				and schedule.datetime_discharge <= :datetime_end
				ORDER BY `number`";
				break;
				
			case "long_term":
				$params[": datetime"] = $datetime;
				$sql = "select * from `room` where facility=:facilityid and id not in (
					select `room`.`id` from `room` 
					inner join `schedule` on `schedule`.`room`=`room`.`id` 
					where `room`.`facility`=:facilityid 
					and :datetime >= `datetime_admit` 
					and (`schedule`.`status`='Approved' || `schedule`.`status`='Under Consideration' || `schedule`.`status` = 'Discharged')
					and `schedule`.`long_term` = 1
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

			break;
			
			case "short_term":
				$params[": datetime"] = $datetime;
				$sql = "select * from `room` where facility=:facilityid and id not in (
					select `room`.`id` from `room` 
					inner join `schedule` on `schedule`.`room`=`room`.`id` 
					where `room`.`facility`=:facilityid 
					and :datetime >= `datetime_admit` 
					and (`schedule`.`status`='Approved' || `schedule`.`status`='Under Consideration' || `schedule`.`status` = 'Discharged')
					and `schedule`.`long_term` = 0
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
			break;
		}
		
		if ($sql != '') {
			$params[":facilityid"] = $facility_id;
			$records = $obj->fetchCustom($sql, $params);
		} else {
			$records = array();
		}
		return $records;
	}
	
	// takes results from the above and orders them by room #
	public static function mergeFetchedRooms($empty, $scheduled) {
		$temp = array();
		$index = array();
		foreach ($empty as $idx => $r) {
			$temp[$r->number] = $r->number;
			$index[$r->number] = array("empty", $idx);
		}
		foreach ($scheduled as $idx => $r) {
			$temp[$r->number] = $r->number;
			$index[$r->number] = array("scheduled", $idx);
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

	
	public function isEmpty($datetime) {
		if ($datetime != '') {
			$sql = "select * from `room` where id not in (
				select `room`.`id` from `room` 
				inner join `schedule` on `schedule`.`room`=`room`.`id` 
				and :datetime >= `datetime_admit` and (:datetime < `datetime_discharge` or `datetime_discharge` IS NULL) 
			) and id=:roomid";
			
			$obj = static::generate();
			$records = $obj->fetchCustom($sql, array(':datetime' => $datetime, ':roomid' => $this->id));
			return count($records > 0);
		} else {
			throw new Exception("Tried to check status of room with no date/time specified.");
		}
	}
	
	
	public function getRoomNumbersByFacility() {
		$sql = "select * from `room` inner join `schedule` on `schedule`.`room`=`room`.`id` where `schedule`.`room`=:roomid";
		$obj = static::generate();
		$rooms = $obj->fetchCustom($sql, array(':roomid' => $this->id));
		return $rooms;
	}

	public static function getEmptyRoomDate($room, $facility) {
		$sql = "select max(datetime_discharge) as empty_date from `schedule` where `room` = :room and `schedule`.`facility` = :facility";
		$params[":room"] = $room;
		$params[":facility"] = $facility;

		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	
	public static function fetchRoomCount($facility) {
		$obj = static::generate();
		$sql = "SELECT count(room.number) as roomCount from room where room.facility = :facility";
		$params[":facility"] = $facility;
		
		return $obj->fetchCustom($sql, $params);
	}
	
	public static function fetchCurrentCensus($date = false) {
		$sql = "SELECT count(`room`.`number`) AS census, schedule.facility FROM `schedule` INNER JOIN `room` on `room`.`id` = `schedule`.`room`INNER JOIN patient_admit on patient_admit.id = schedule.patient_admit WHERE (`schedule`.`status` = 'Approved' OR `schedule`.`status` = 'Discharged') AND `schedule`.`datetime_admit` <= :datetime AND ((`schedule`.`datetime_discharge` >= :datetime OR `schedule`.`datetime_discharge` IS NULL OR `schedule`.`datetime_discharge` = '0000-00-00 00:00:00') OR (`schedule`.`discharge_to` = 'Discharge to Hospital (Bed Hold)' and `schedule`.`datetime_discharge_bedhold_end` > :bedhold_end)) group by schedule.facility order by schedule.facility asc";

		
		$params[":datetime"] = date('Y-m-d 23:59:59', strtotime($date));
		$params[":bedhold_end"] = date('Y-m-d 11:00:00', strtotime($date));
		
		$obj = static::generate();
		$census[] = $obj->fetchCustom($sql, $params);
		return $census;

	}
	
	public static function fetchRoom($number = false, $facility = false) {
		$sql = "select * from room where number = :number and facility = :facility";
		$params = array(
			":number" => $number,
			":facility" => $facility
		);
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	public static function checkRoomStatus($id = false, $datetime = false) {
		$sql = "select id from schedule WHERE (`schedule`.`status` = 'Approved' OR `schedule`.`status` = 'Discharged') AND `schedule`.`datetime_admit` <= :datetime AND ((`schedule`.`datetime_discharge` >= :datetime OR `schedule`.`datetime_discharge` IS NULL OR `schedule`.`datetime_discharge` = '0000-00-00 00:00:00') OR (`schedule`.`discharge_to` = 'Discharge to Hospital (Bed Hold)' and `schedule`.`datetime_discharge_bedhold_end` > :bedhold_end)) and room = :room";
		$params = array(
			":room" => $id,
			":datetime" => date('Y-m-d 23:59:59', strtotime($datetime)),
			":bedhold_end" => date('Y-m-d 11:00:00', strtotime($datetime))
		);
		
		$obj = static::generate();
		$result = $obj->fetchCustom($sql, $params);		
		
		if (!empty($result)) {
			return false;
		}		
		
		return true;
	}

}