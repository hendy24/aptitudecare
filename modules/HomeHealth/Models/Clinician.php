<?php

class Clinician extends Data {
	protected $table = 'clinician';


	public function fetchClinicians($location, $filter = false) {
		$params = array();
		$sql = "SELECT * FROM user INNER JOIN user_clinician ON user_clinician.user_id=user.id INNER JOIN clinician ON clinician.id=user_clinician.clinician_id INNER JOIN user_location ON user_location.user_id = user.id WHERE user_location.location_id = :location_id";
		$params[":location_id"] = $location->id;

		if ($filter) {
			$sql .= " AND clinician.name = :filter";
			$params[":filter"] = $filter;
		}
		return $this->fetchAll($sql, $params);
	}


	public function fetchClinicianTypes($filter = false) {
		$params = array();
		$sql = "SELECT * FROM {$this->table}";

		if ($filter) {
			$sql .= " WHERE {$this->table}.name = :filter";
			$params[":filter"] = $filter;
		}
		return $this->fetchAll($sql, $params);
	}

}