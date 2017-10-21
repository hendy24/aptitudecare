<?php

class CMS_Onsite_Visit extends CMS_Table {
	
	public static $table = "onsite_visit";
	public static $modelTitle = "Onsite Visit";
	
	public static function findVisitByPatientAdmit($schedule) {
		$params = array();
		$sql = "select * from `onsite_visit` where `onsite_visit`.`schedule` = :schedule";
		$params[":schedule"] = $schedule;
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	public static function fetchVisitInfo($schedule) {
		$params = array();
		$sql = "select * from `onsite_visit` where `onsite_visit`.`schedule` = :schedule group by `onsite_visit`.`schedule` order by `onsite_visit`.`datetime_visit` desc";
		$params[":schedule"] = $schedule;
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}


	
}