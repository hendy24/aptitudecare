<?php 

class Physician extends AppData {

	protected $table = 'physician';

	protected $_manage_fields = array(
		'public_id',
		'first_name',
		'last_name',
		'city',
		'state',
		'phone',
		'email'
	);

	protected $_add_fields = array(
		'first_name',
		'last_name',
		'address',
		'city',
		'state',
		'zip',
		'phone',
		'fax',
		'email'
	); 


	public function searchByName($term = false, $location = false, $additional_states = array()) {

		if ($term) {
			$tokens = explode(" ", $term);
			$params = array();

			$sql = "SELECT p.* FROM {$this->tableName()} p WHERE ";
			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$sql .= " p.first_name like :term{$idx} OR p.last_name like :term{$idx} AND";
				$params[":term{$idx}"] = "%{$token}%";
			}
			$sql = rtrim($sql, ' AND');
		}

		if ($location) {
			$params[':location_state'] = $location->state;
			$sql .= " AND (p.`state` = :location_state";

			foreach ($additionalStates as $k => $s) {
				$params[":add_states{$k}"] = $s->state;
				$sql .= " OR p.`state` = :add_states{$k}";
			}
			$sql .= ")";
		}

		$sql .= " ORDER BY p.`last_name` ASC";

		return $this->fetchAll($sql, $params);


	}


}