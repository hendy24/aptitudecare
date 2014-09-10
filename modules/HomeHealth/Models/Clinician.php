<?php

class Clinician extends Data {
	protected $table = 'clinician';


	public function fetchClinicians($filter = false) {
		$params = array();
		$sql = "SELECT * FROM user INNER JOIN user_clinician ON user_clinician.user_id=user.id INNER JOIN clinician ON clinician.id=user_clinician.clinician_id";

		if ($filter) {
			$sql .= " WHERE clinician.name = :filter";
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