<?php

class CMS_Hospital extends CMS_Table {
	
	public static $table = "hospital";
	public static $modelTitle = "Hospitals";
	
	protected static $metadata = array(
		"state" => array(
			"widget" => "state"
		)
	);

	public static function getHospitalName($id) {
		$sql = "select name from `hospital` where id = :id";
		$params[":id"] = $id;

		return static::fetchCustom($sql, $params);
	}
	
	public function findHospitals($state) {
		$params[":state"] = $state;
		$sql = "select * from hospital WHERE state = :state";
		return $this->fetchCustom($sql, $params);
	}
		
	public function deleteHospital($id) {
		db()->query("delete from hospital where id=:id", array(
			":id" => $id
		));
		
		return true;
	}
		
}