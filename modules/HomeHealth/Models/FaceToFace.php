<?php

class FaceToFace extends HomeHealth {

	protected $table = 'face_to_face';
	public $id;
	public $home_health_schedule;
	public $f2f_date;
	public $medical_condition;
	public $home_health_services;
	public $home_health_reasons;
	public $homebound_reason;
	public $physician_id;


	public function fetchBySchedule($schedule) {
		if (!$schedule) {
			return false;
		}
		$sql = "SELECT {$this->tableName()}.*, ac_physician.last_name, ac_physician.first_name, ac_physician.id AS physician_id FROM {$this->tableName()} INNER JOIN ac_physician ON ac_physician.id = {$this->tableName()}.physician_id WHERE home_health_schedule = :schedule";
		$params[":schedule"] = $schedule;
		return $this->fetchOne($sql, $params);
	}

}