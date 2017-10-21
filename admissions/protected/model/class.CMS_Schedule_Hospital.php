<?php

class CMS_Schedule_Hospital extends CMS_Table {

	public static $table = "schedule_hospital";
	public static $modelTitle = "ScheduleHospital";
	protected static $metadata = array();
	public static $in_admin = true;
	
	

	public function dischargeNurse() {
		return new CMS_Site_User($this->discharge_nurse);
	}
	
	public static function fetchActiveByPersonId($person_id) {
		$sql = "select a.* from schedule_hospital a inner join schedule b
		on b.id=a.schedule inner join patient_admit c on c.id=b.patient_admit
		where is_complete=0 and c.person_id=:personid";
		
		$params = array(":personid" => $person_id);
		
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	public static function alreadyExists($schedule_id) {
		$params = array();
		$sql = "select * from `schedule_hospital` where `schedule` = :scheduleid";
		$params[":scheduleid"] = $schedule_id;
		
		$obj = static::generate();
		$result = $obj->fetchCustom($sql, $params);
		if ($result != '') {
			
		}
	}
	
	public function cancelHospitalVisitInfo($schedule) {
		$sql = "select `schedule_hospital`.`id` from `schedule_hospital` inner join `schedule` on `schedule`.`id`=`schedule_hospital`.`schedule` where `schedule`.`pubid`=:schedule";
		
		$params = array(":schedule" => $schedule);
		
		$obj = new CMS_Schedule_Hospital();
		$result = $obj->fetchCustom($sql, $params);
		return $result;
	}
	
	
	public function deleteVisit($id) {
		db()->query("delete from schedule_hospital where id=:pid", array(
			":pid" => $id
		));
		
		return true;
	}



	
}