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
	
	public function move($location_id, $patient_id, $old_number, $new_number) {
		$sql = "UPDATE admit_schedule AS s 
INNER JOIN admit_room AS r ON s.room_id = r.id
SET room_id = (SELECT id FROM admit_room WHERE location_id = :location_id AND number = :new_number)
WHERE :new_number IN (SELECT number from admit_room WHERE location_id = :location_id) AND r.number = :old_number AND patient_id = :patient_id AND s.location_id = :location_id AND (s.status = 'Approved' AND (s.datetime_discharge IS NULL OR s.datetime_discharge >= now()) OR (s.status = 'Discharged' AND s.datetime_discharge >= now()));";
		$params[":location_id"] = $location_id;
		$params[":patient_id"] = $patient_id;
		$params[":old_number"] = $old_number;
		$params[":new_number"] = $new_number;
		
		if ($this->update($sql, $params)) {
			return true;
		}
		return false;
	}
	
}
