<?php

class FaceToFace extends AppModel {
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
		$sql = "SELECT {$this->table}.*, physician.last_name, physician.first_name, physician.id AS physician_id FROM {$this->table} INNER JOIN physician ON physician.id = {$this->table}.physician_id WHERE home_health_schedule = :schedule";
		$params[":schedule"] = $schedule;
		return $this->fetchOne($sql, $params);
	}

}