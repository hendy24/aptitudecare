<?php

class UserLocation extends AppData {

	protected $table = 'user_location';


	public function fetchUserLocations($user_id) {
		$sql = "SELECT * FROM {$this->tableName()} INNER JOIN ac_location ON ac_location.id = ac_user_location.location_id WHERE user_id = :id AND ac_location.location_type = 1";
		$params[":id"] = $user_id;
		return $this->fetchAll($sql, $params);
	}


	public function fetchUserFacility($location = null) {
		$sql = "SELECT * FROM {$this->tableName()} INNER JOIN ac_location ON ac_location.id = ac_user_location.location_id WHERE ac_location.location_type = 1 AND ac_location.id = :location_id";

		if ($location != null) {
			$params[":location_id"] = $location;
		} else {
			$params[":location_id"] = auth()->getRecord()->default_location;
		}

		return $this->fetchOne($sql, $params);
	}

}