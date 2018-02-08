<?php

class Schedule extends Admission {

	protected $table = "schedule";


	public function fetchByPatientId($patient_id) {
		$room = $this->loadTable("Room");
		$sql = "SELECT s.*, r.number FROM {$this->tableName()} s INNER JOIN {$room->tableName()} AS r ON r.id = s.room_id WHERE patient_id = :patient_id LIMIT 1";
		$params[":patient_id"] = $patient_id;
		return $this->fetchOne($sql, $params);
	}


	public function discharge($patient_id) {
		$sql = "UPDATE {$this->tableName()} SET datetime_discharge = :now, status = 'Discharged' WHERE patient_id = :patient_id";
		$params[":patient_id"] = $patient_id;
		$params[":now"] = mysql_date("now");
		if ($this->update($sql, $params)) {
			return true;
		}
		return false;
	}
	
}