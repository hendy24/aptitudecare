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

	public function fetchOtherLocations() {
		if (auth()->valid()) {
			$user = auth()->getRecord();
		} else {
			return false;
			exit;
		}	

		$sql = "SELECT {$this->table}.* FROM {$this->table} INNER JOIN user_location ON user_location.location_id = {$this->table}.id WHERE user_location.user_id = :user_id GROUP BY location.id";
		$params[":user_id"] = $user->id;
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

		if ($location_id) {
			$sql .= " AND hh_facility_link.home_health_id = :location_id";
			$params[":location_id"] = $location_id;
		}

			
		$params[':user_id'] = $user->id;

		return $this->fetchAll($sql, $params);
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


	public function fetchHomeHealthLocations($locations = false) {

		$sql = "SELECT * FROM location WHERE location_type = 2";

		//	Need to get only those locations for which the user has permission to access.
		if ($locations) {
			$locs = array();
			$sql .= " AND id IN (";
			foreach ($locations as $k => $l) {
				$locs[$k] = $l->id;
				$sql .= ":id{$k}, ";
				$params[":id{$k}"] = $l->id;
			}
			$sql = trim($sql, ', ');
			$sql .= ")";
		}
		
		return $this->fetchAll($sql, $params);
		

	}
}