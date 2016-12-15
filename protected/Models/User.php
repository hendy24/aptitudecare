<?php

class User extends AppData {
	protected $table = 'user';
	private $username_field = 'email';
	private $password_field = 'password';

	protected $belongsTo = array(
		'Group' => array(
			'table' => 'ac_group',
			'join_type' => 'LEFT',
			'inner_key' => 'group_id',
			'foreign_key' => 'id',
			'join_field' => array(
				'column' => 'description',
				'name' => 'group_name'
			)
		),
		'Location' => array(
			'table' => 'ac_location',
			'join_type' => 'LEFT',
			'inner_key' => 'default_location',
			'foreign_key' => 'id',
			'join_field' => array(
				'column' => 'name',
				'name' => 'default_location'
			)
		),
		'Module' => array(
			'table' => 'ac_module',
			'join_type' => 'LEFT',
			'inner_key' => 'default_module',
			'foreign_key' => 'id',
			'join_field' => array(
				'column' => 'name',
				'name' => 'default_module'
			)
		)
	);

	protected $hasMany = array(
		'UserLocation' => array(
			'table' => 'ac_user_location',
			'join_type' => 'LEFT',
			'inner_key' => 'id',
			'foreign_key' => 'user_id',
			'join_key' => 'location_id'
		)
	);

	protected $_manage_fields = array(
		'public_id',
		'first_name',
		'last_name',
		'phone',
		'group_name',
		'default_location',
		'email',
		'default_module'
	);

	protected $_add_fields = array(
		'first_name',
		'last_name',
		'email',
		'password',
		'phone'
	);



	public function fetchUserLocations($id = null) {
		$sql = "SELECT `ac_location`.* FROM `ac_location` LEFT JOIN `ac_user_location` ON `ac_user_location`.`location_id` = `ac_location`.`id` WHERE `ac_user_location`.`user_id`=:id";
		if ($id != null) {
			$params[':id'] = $id;
		} else {
			$params[':id'] = auth()->getRecord()->id;
		}
		
		return $this->fetchAll($sql, $params);
	}


	public function fetchUserAssignedLocations() {
		$sql = "SELECT `ac_location`.* FROM `ac_location` LEFT JOIN `ac_user_location` ON `ac_user_location`.`location_id` = `ac_location`.`id` WHERE `ac_user_location`.`user_id`=:id";
		$params[':id'] = $this->id;
		
		return $this->fetchAll($sql, $params);
	}


	public function fetchLocationStates() {
		$locations = $this->fetchUserLocations();
		$locString = null;
		foreach ($locations as $l) {
			$locString .= "{$l->id}, ";
		}

		$locString = trim($locString, ", ");
		$sql = "(SELECT state FROM ac_location WHERE ac_location.id IN (:location)) UNION (SELECT state FROM ac_location_link_state WHERE ac_location_link_state.location_id IN (:location))";
		$params[":location"] = $locString;

		return $this->fetchAll($sql, $params);
	}



	public function fetchByType($type, $location_id) {
		$sql = "SELECT {$this->tableName()}.*, {$this->tableName()}.id AS user_id, home_health_clinician.* FROM {$this->tableName()} INNER JOIN home_health_user_clinician ON home_health_user_clinician.user_id = ac_user.id INNER JOIN home_health_clinician ON home_health_user_clinician.clinician_id = home_health_clinician.id INNER JOIN ac_user_location ON ac_user_location.user_id = ac_user.id WHERE home_health_clinician.name = :type AND ac_user_location.location_id = :location_id";
		$params[":type"] = $type;
		$params[":location_id"] = $location_id;
		return $this->fetchAll($sql, $params);
	}


	public function findByEmail($email) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE email = :email";
		$params[":email"] = $email;
		return $this->fetchOne($sql, $params);
	}


	public function userCount($location_id) {
		$sql = "SELECT count('id') AS items FROM {$this->tableName()} INNER JOIN ac_user_location ON ac_user_location.user_id = {$this->tableName()}.id WHERE ac_user_location.location_id = :location_id";
		$params[":location_id"] = $location_id;
		return $this->fetchOne($sql, $params);
	}

	public function fetchNSD($location) {
		$sql = "SELECT CONCAT(first_name, ' ', last_name) AS name FROM {$this->tableName()} WHERE default_location = :location_id AND group_id = 11 LIMIT 1";
		$params[":location_id"] = $location;
		return $this->fetchOne($sql, $params);
	}

}