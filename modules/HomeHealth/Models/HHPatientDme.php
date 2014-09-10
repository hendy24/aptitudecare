<?php

class HHPatientDme extends AppModel {
	protected $table = "hh_patient_dme";
	public $home_health_schedule_id;
	public $dme_id;


	public function fetchPatientEquipment($schedule_id) {
		$sql = "SELECT * FROM {$this->table} WHERE {$this->table}.home_health_schedule_id = :schedule_id";
		$params[":schedule_id"] = $schedule_id;
		return $this->fetchAll($sql, $params);
	}


	public function deleteCurrentDme($schedule_id) {
		$sql = "DELETE FROM {$this->table} WHERE home_health_schedule_id = :schedule_id";
		$params[":schedule_id"] = $schedule_id;

		db()->update($sql, $params);
		return true;
	}

}