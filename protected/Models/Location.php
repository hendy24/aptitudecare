<?php

class Location extends AppModel {
	
	protected $table = 'location';

	protected $hasMany = array(
		'LocationLinkState' => array(
			'table' => 'location_link_state',
			'join_type' => 'LEFT',
			'inner_key' => 'id',
			'foreign_key' => 'location_id'
		)
	);


	public function fetchDefaultLocation() {
		if (auth()->valid()) {
			$user = auth()->getRecord();
		} else {
			return false;
			exit;
		}	

		$sql = "SELECT {$this->table}.* FROM {$this->table} INNER JOIN user on user.default_location = location.id WHERE user.id = :user_id";
		$params[":user_id"] = $user->id;

		return $this->fetchOne($sql, $params);

	}

	public function fetchOtherLocations($module = null) {
		if (auth()->valid()) {
			$user = auth()->getRecord();
		} else {
			return false;
			exit;
		}	

		$sql = "SELECT {$this->table}.* FROM {$this->table} INNER JOIN user_location ON user_location.location_id = {$this->table}.id";

		if ($module != '') {
			$sql .= " INNER JOIN modules_enabled ON modules_enabled.location_id = location.id INNER JOIN module ON module.id = modules_enabled.module_id";
			$params[":module_name"] = $module;
		}

		$sql .= " WHERE user_location.user_id = :user_id";

		if ($module != '') {
			$sql .= " AND module.name = :module_name";
		}

		$sql .= "  GROUP BY location.id";

		
		$params[":user_id"] = $user->id;
				debug ($sql, $params);

		return $this->fetchAll($sql, $params);
	}

	public function fetchLinkedFacilities($location_id = false) {
		if (auth()->valid()) {
			$user = auth()->getRecord();
		} else {
			return false;
			exit;
		}		

		$sql = "SELECT {$this->table}.* FROM {$this->table} INNER JOIN hh_facility_link ON hh_facility_link.facility_id = {$this->table}.id WHERE hh_facility_link.home_health_id IN (SELECT {$this->table}.id FROM {$this->table} INNER JOIN user_location ON user_location.location_id = {$this->table}.id WHERE user_location.user_id = :user_id)";

		$sql .= " AND hh_facility_link.home_health_id = :location_id";
		if ($location_id) {
			$params[":location_id"] = $location_id;
		} else {
			$params["location_id"] = $this->id;
		}

			
		$params[':user_id'] = $user->id;

		debug ($sql, $params);
		return $this->fetchAll($sql, $params);
	}


	public function fetchFacilitiesByHomeHealthId($id) {
		$sql = "select location.* FROM location INNER JOIN hh_facility_link ON hh_facility_link.facility_id = location.id WHERE hh_facility_link.home_health_id = :id";
		$params[":id"] = $id;
		return $this->fetchAll($sql, $params);
	}



	public function fetchHomeHealthLocation() {
		$sql = "SELECT * FROM {$this->table} WHERE {$this->table}.id = (SELECT home_health_id FROM hh_facility_link WHERE hh_facility_link.facility_id = :facility_id)";
		$params[":facility_id"] = $this->id;
		return $this->fetchOne($sql, $params);
	}

	public function fetchLinkedFacility($location_id = false) {
		if (auth()->valid()) {
			$user = auth()->getRecord();
		} else {
			return false;
			exit;
		}		

		$sql = "SELECT {$this->table}.* FROM {$this->table} INNER JOIN hh_facility_link ON hh_facility_link.facility_id = {$this->table}.id WHERE hh_facility_link.home_health_id IN (SELECT {$this->table}.id FROM {$this->table} INNER JOIN user_location ON user_location.location_id = {$this->table}.id WHERE user_location.user_id = :user_id)";

		if ($location_id) {
			$sql .= " AND hh_facility_link.home_health_id = :location_id";
			$params[":location_id"] = $location_id;
		}

			
		$params[':user_id'] = $user->id;

		return $this->fetchOne($sql, $params);
	}
	
	
	public function fetchLocation($id) {
		$sql = "SELECT * FROM {$this->table} WHERE {$this->table}.";
		
		if (is_numeric($id)) {
			$sql .= "`id`=:id";
		} else {
			$sql .="`public_id`=:id";
		}
		
		$params[':id'] = $id;
		return $this->fetchOne($sql, $params);
	}

	public function fetchLocationStates() {
		$sql = "(SELECT `{$this->table}`.`state` FROM `{$this->table}` WHERE `{$this->table}`.`id` = :id) UNION (SELECT  `location_link_state`.`state` FROM `{$this->table}` INNER JOIN `location_link_state` ON `location_link_state`.`location_id` = `{$this->table}`.`id` WHERE `{$this->table}`.`id` = :id)";
		$params[':id'] = $this->id;
		return $this->fetchAll($sql, $params);
	}


	// public function fetchHomeHealthLocations($locations = false) {

	// 	$sql = "SELECT * FROM location WHERE location_type = 2";

	// 	//	Need to get only those locations for which the user has permission to access.
	// 	if ($locations) {
	// 		$locs = array();
	// 		$sql .= " AND id IN (";
	// 		foreach ($locations as $k => $l) {
	// 			$locs[$k] = $l->id;
	// 			$sql .= ":id{$k}, ";
	// 			$params[":id{$k}"] = $l->id;
	// 		}
	// 		$sql = trim($sql, ', ');
	// 		$sql .= ")";
	// 	}
		
	// 	return $this->fetchAll($sql, $params);
		

	// }

	public function fetchHomeHealthLocations() {
		$sql = "SELECT * FROM {$this->table} LEFT JOIN user_location ON user_location.location_id = location.id WHERE user_location.user_id = :id AND {$this->table}.location_type = 2";
		$params[":id"] = auth()->getRecord()->id;
		return $this->fetchAll($sql, $params);
	}
}