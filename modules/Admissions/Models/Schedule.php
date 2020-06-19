<?php

class Schedule extends Admission {

	protected $table = "schedule";


	public function fetchByClientId($patient_id) {
		$room = $this->loadTable("Room");
		$sql = "SELECT s.*, r.number FROM {$this->tableName()} s INNER JOIN {$room->tableName()} AS r ON r.id = s.room WHERE client = :patient_id LIMIT 1";
		$params[":patient_id"] = $patient_id;
		return $this->fetchOne($sql, $params);
	}

	public function fetchSchedule($client_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE client = :client LIMIT 1";
		$params[":client"] = $client_id;
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