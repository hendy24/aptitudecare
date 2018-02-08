<?php

class CaseManager extends AppData {
	protected $table = 'case_manager';
	protected $belongsTo = array(
		'HealthcareFacility' => array(
			'table' => 'ac_healthcare_facility',
			'join_type' => 'INNER',
			'inner_key' => 'healthcare_facility_id',
			'foreign_key' => 'id',
			'join_field' => array(
				'column' => 'name',
				'name' => 'ac_healthcare_facility'
			)
		)
	);
	protected $_manage_fields = array(
		'public_id',
		'first_name',
		'last_name',
		'phone',
		'email',
		'healthcare_facility'
	);

	protected $_add_fields = array(
		'first_name',
		'last_name',
		'email',
		'address',
		'city',
		'state',
		'zip',
		'phone',
		'fax'
	); 


	public function fetchCMCount($states) {
		$state = null;
		foreach ($states as $k => $s) {
			$state .= "'{$s->state}', ";
		}
		$state = trim($state, ", ");

		$sql = "SELECT count({$this->tableName()}.id) AS items FROM {$this->tableName()} INNER JOIN ac_healthcare_facility ON ac_healthcare_facility.id = {$this->tableName()}.healthcare_facility_id WHERE ac_healthcare_facility.state IN ({$state})";

		return $this->fetchOne($sql);
	}


	public function searchByName($term = false, $location = false, $additional_states = array()) {

		$healthcare_facilities = $this->loadTable("HealthcareFacility");

		if ($term) {
			$tokens = explode(" ", $term);
			$params = array();

			$sql = "SELECT cm.* FROM {$this->tableName()} cm INNER JOIN {$healthcare_facilities->tablename()} f ON f.id = cm.healthcare_facility_id WHERE ";
			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$sql .= " cm.first_name like :term{$idx} OR cm.last_name like :term{$idx} AND";
				$params[":term{$idx}"] = "%{$token}%";
			}
			$sql = rtrim($sql, ' AND');
		}

		if ($location) {
			$params[':location_state'] = $location->state;
			$sql .= " AND (f.`state` = :location_state";

			foreach ($additionalStates as $k => $s) {
				$params[":add_states{$k}"] = $s->state;
				$sql .= " OR f.`state` = :add_states{$k}";
			}
			$sql .= ")";
		}

		$sql .= " ORDER BY cm.`last_name` ASC";

		return $this->fetchAll($sql, $params);


	}



}