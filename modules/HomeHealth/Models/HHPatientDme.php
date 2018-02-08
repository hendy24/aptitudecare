<?php

class HHPatientDme extends HomeHealth {
	protected $table = "patient_dme";
	public $home_health_schedule_id;
	public $dme_id;


	public function fetchPatientEquipment($schedule_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE {$this->tableName()}.home_health_schedule_id = :schedule_id";
		$params[":schedule_id"] = $schedule_id;
		return $this->fetchAll($sql, $params);
	}


	public function deleteCurrentDme($schedule_id) {
		$sql = "DELETE FROM {$this->tableName()} WHERE home_health_schedule_id = :schedule_id";
		$params[":schedule_id"] = $schedule_id;

		db()->update($sql, $params);
		return true;
	}

}