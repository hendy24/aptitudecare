<?php

class Clinician extends HomeHealth {
	protected $table = 'clinician';


	public function fetchClinicians($location, $filter = false) {
		$params = array();
		$sql = "SELECT * FROM ac_user INNER JOIN home_health_user_clinician ON home_health_user_clinician.user_id = ac_user.id INNER JOIN {$this->tableName()} ON {$this->tableName()}.id = home_health_user_clinician.clinician_id INNER JOIN ac_user_location ON ac_user_location.user_id = ac_user.id WHERE ac_user_location.location_id = :location_id";
		$params[":location_id"] = $location->id;

		if ($filter) {
			$sql .= " AND {$this->tableName()}.name = :filter";
			$params[":filter"] = $filter;
		}

		return $this->fetchAll($sql, $params);
	}


	public function fetchClinicianTypes($filter = false) {
		$params = array();
		$sql = "SELECT * FROM {$this->tableName()}";

		if ($filter) {	
			$sql .= " WHERE {$this->tableName()}.name = :filter";
			$params[":filter"] = $filter;
		}
		return $this->fetchAll($sql, $params);
	}

}