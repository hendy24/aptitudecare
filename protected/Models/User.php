<?php

class User extends AppModel {
	protected $table = 'user';
	private $username_field = 'email';
	private $password_field = 'password';
	public $public_id;

	protected $belongsTo = array(
		'Group' => array(
			'table' => 'group',
			'join_type' => 'INNER',
			'inner_key' => 'group_id',
			'foreign_key' => 'id',
			'join_field' => array(
				'column' => 'description',
				'name' => 'group_name'
			)
		),
		'Location' => array(
			'table' => 'location',
			'join_type' => 'INNER',
			'inner_key' => 'default_location',
			'foreign_key' => 'id',
			'join_field' => array(
				'column' => 'name',
				'name' => 'default_location'
			)
		),
		'Module' => array(
			'table' => 'module',
			'join_type' => 'INNER',
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
			'table' => 'user_location',
			'join_type' => 'INNER',
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



	public function fetchUserLocations() {
		$sql = "SELECT `location`.* FROM `location` INNER JOIN `user_location` ON `user_location`.`location_id` = `location`.`id` WHERE `user_location`.`user_id`=:id";
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
		$sql = "(SELECT state FROM location WHERE location.id IN (:location)) UNION (SELECT state FROM location_link_state WHERE location_link_state.location_id IN (:location))";
		$params[":location"] = $locString;

		return $this->fetchAll($sql, $params);
	}



	public function fetchByType($type, $location_id) {
		$sql = "SELECT user.*, user.id AS user_id, clinician.* FROM user INNER JOIN user_clinician ON user_clinician.user_id = user.id INNER JOIN clinician ON user_clinician.clinician_id = clinician.id INNER JOIN user_location ON user_location.user_id = user.id WHERE clinician.name = :type AND user_location.location_id = :location_id";
		$params[":type"] = $type;
		$params[":location_id"] = $location_id;
		return $this->fetchAll($sql, $params);
	}


	public function findByEmail($email) {
		$sql = "SELECT * FROM {$this->table} WHERE email = :email";
		$params[":email"] = $email;
		return $this->fetchOne($sql, $params);
	}


	public function userCount($location_id) {
		$sql = "SELECT count('id') AS items FROM `{$this->table}` INNER JOIN user_location ON user_location.user_id = user.id WHERE user_location.location_id = :location_id";
		$params[":location_id"] = $location_id;
		return $this->fetchOne($sql, $params);
	}


}