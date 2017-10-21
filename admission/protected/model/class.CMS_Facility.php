<?php


class CMS_Facility extends CMS_Table {
	
	public static $table = "facility";
	protected static $metadata = array();
	
	public static $modelTitle = "AHC Facilities";
	
	public function getTitle() {
		return $this->name;
	}
	
	public function getEmptyRooms() {
		return CMS_Room::fetchEmptyByFacility($this->id);
	}
	
	public function getScheduledRooms() {
		return CMS_Room::fetchScheduledByFacility($this->id);
	}
	
	public function getUsers() {
		return $this->related("site_user", true, false, "first");
	}
	
	public static function findByFacilityId($facility_id) {
		$sql = "select * from facility where id = {$facility_id}";
		$obj = static::generate();
		return $obj->fetchCustom($sql);	
	}
	
	public function findAll() {
		$sql = "select * from facility";
		$obj = static::generate();
		return $obj->fetchCustom($sql);
	}
	
	public function findByFacilityPubid($pubid = false) {
		$sql = "select * from facility where facility.pubid = '{$pubid}'";
		$obj = static::generate();
		
		return $obj->fetchCustom($sql);
		
	}
	
	public static function getFacilityHospitalId($facility_id) {
		if ($facility_id == 1) {
			return 115;
		} 
		
		if ($facility_id == 2) {
			return 76;
		}
		
		if ($facility_id == 3) {
			return 3572;
		}
	}
	
	
	public static function fetchCurrentCensus($facility_id, $datetime) {
		
		$params[":datetime"] = $datetime;
		$params[":facilityid"] = $facility_id;
		$params[":weekstart"] = date('Y-m-d 00:00:00', strtotime("Last Sunday", strtotime($datetime)));
				
		$sql = "select distinct
				`room`.*,
				`patient_admit`.`pubid` as `patient_admit_pubid`,
				`patient_admit`.`first_name`,
				`patient_admit`.`last_name`,
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
				where `room`.`facility`=:facilityid 
				and (`schedule`.`status`='Approved' OR `schedule`.`status` = 'Discharged')
				and :datetime >= `datetime_admit` 
				and (`datetime_discharge` >= :weekstart OR `datetime_discharge` IS NULL)
				ORDER BY `number`
				";
			
			
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);

	}
	
	public static function getStates($facilities) {
		$ids = array();
		foreach ($facilities as $k => $f) {
			$ids[$k] = $f->id;			
		}
		$i = implode(",", $ids);
		
		$sql = "SELECT facility.state, facility_link_states.state as add_state FROM facility LEFT JOIN facility_link_states ON facility_link_states.facility_id = facility.id WHERE id in({$i}) group by facility.state, add_state";
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
}