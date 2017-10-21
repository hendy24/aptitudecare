<?php

class CMS_Case_Manager extends CMS_Table {
	public static $table = 'case_manager';
	
	public function findCaseManagers($state) {
		$params[":state"] = $state;
		$sql = "select case_manager.pubid, case_manager.first_name, case_manager.last_name, case_manager.phone, hospital.name from case_manager left join hospital on case_manager.hospital_id = hospital.id WHERE hospital.state = :state order by case_manager.last_name asc";
		return $this->fetchCustom($sql, $params);
	}
	
	public function deleteCaseManager($cm_id) {
		db()->query("delete from case_manager where id=:cmid", array(
			":cmid" => $cm_id
		));
		
		return true;
	}
	
}